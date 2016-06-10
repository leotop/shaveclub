<?
    CModule::IncludeModule("forum");
    AddEventHandler("forum", "onAfterMessageAdd", "notifyNewItemFeedback");
    function notifyNewItemFeedback($ID, $arFields) {
        $res = CIBlockElement::GetList(
            array(),
            array(
                'IBLOCK_ID' => 9,
                'PROPERTY_FORUM_TOPIC_ID' => $arFields["TOPIC_ID"]
            ),
            false,
            false,
            array('*', 'IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_FORUM_TOPIC_ID', 'CODE', 'SECTION', 'SECTIONS')
        );
        if($ar_res = $res->GetNext()) {
            $TYPE_MAIL_EVENT = 'NEW_ITEM_REVIEW';
            $arMail = array(
                'ITEM_NAME' => $ar_res['NAME'],
                'AUTHOR_NAME' => $arFields['AUTHOR_NAME'],
                'POST_DATE' => date('d.m.Y H:i:s'),
                'POST_MESSAGE' => $arFields['POST_MESSAGE'],
                'PATH2ITEM' => '/catalog/' . $ar_res["IBLOCK_SECTION_ID"] . '/' . $ar_res['ID'],
            );
            $ID_MAIL_EVENT = "NEW_ITEM_REVIEW";
            print_r($arFields);
            $ok = CEvent::Send($TYPE_MAIL_EVENT, "s2", $arMail,"N");
        }
    }

    AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");

    function OnAfterUserAddHandler(&$arFields)
    {
        $user = new CUser;
        $fields = Array(
            "UF_SITE" => 23,
        );
        $user->Update($arFields["ID"], $fields);
    }

    AddEventHandler('main', 'OnBeforeEventSend', 'SentMail');

    function SentMail(&$arFields, &$arTemplate) {
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("catalog");
        CModule::IncludeModule("sale");
        $path = $_SERVER["HTTP_HOST"] ;
        $arItems = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $arFields["ORDER_ID"]));

        while($item = $arItems -> Fetch()){
            if($item["CODE"] == "store_pickup"){
                $store = CCatalogStore::GetList(array(),array("ID"=>$item["VALUE"]))->Fetch();
                $arOrder_new["pickup"] = ' <a href="http://'.$path.'/store/'.$item["VALUE"].'/">'.$store["TITLE"].'</a>';
            } elseif ($item["CODE"] == "EMAIL") {
                $arOrder_new["email"] = $item["VALUE"];
            } elseif ($item["CODE"] == "quick_order") {
                $arOrder_new["quick_order"] = $item["VALUE"];
            }

        }

        if ($arOrder_new["quick_order"] != "Y") {
            $ORDER_ID = $arFields["ORDER_ID"];
            $arOrderProps = array();
            $order_props = CSaleOrderPropsValue::GetOrderProps($ORDER_ID);//�������� ������
            while ($arProps = $order_props->Fetch()) {
                if (!empty($arProps["CODE"])){
                    $arOrderProps[$arProps["CODE"]] = $arProps;
                } else {
                    $arOrderProps[$arProps["ID"]] = $arProps;
                }
            }

            $arFields["PHONE"] = $arOrderProps["PHONE"]["VALUE"];
            if (!empty($arOrderProps["LOCATION"]["VALUE"])) {
                $arLocs = CSaleLocation::GetByID((int) $arOrderProps["LOCATION"]["VALUE"], LANGUAGE_ID);
                $arFields["ADDRESS"] = $arLocs["COUNTRY_NAME"] . ", " . $arLocs["REGION_NAME_LANG"] . ', ' . $arLocs["CITY_NAME"];
                if (!empty($arOrderProps["ADDRESS"]["VALUE"])){
                    $arFields["ADDRESS"] .= ', ' . $arOrderProps["ADDRESS"]["VALUE"];
                }
            }

            $dbOrder = CSaleOrder::GetList(
                array("ID" => "DESC"),
                array("ID" => $ORDER_ID),
                false,
                false
            );
            $arOrder = $dbOrder -> Fetch();
            //email �� �������� �����



            $arFields["EMAIL"] = $arOrder_new["email"];
            $arFields["ORDER_PRICE"] = round($arOrder["PRICE"], 0);
            $arFields["DISCOUNT_VALUE"] = round($arOrder["DISCOUNT_VALUE"], 0);
            $arFields["BASKET_PRICE"] = round($arOrder["PRICE"] - $arOrder["PRICE_DELIVERY"], 0);


            $arFields["PICKUP"] = $arOrder_new["pickup"];

            $DELIVERY_NAME = '';
            if (strpos($arOrder["DELIVERY_ID"], ":") !== false) {
                $arId = explode(":", $arOrder["DELIVERY_ID"]);
                $dbDelivery = CSaleDeliveryHandler::GetBySID($arId[0]);
                $arDelivery = $dbDelivery -> Fetch();
                $DELIVERY_NAME = htmlspecialcharsEx($arDelivery["NAME"])." - ".htmlspecialcharsEx($arDelivery["PROFILES"][$arId[1]]["TITLE"]);
            }
            elseif (IntVal($arOrder["DELIVERY_ID"]) > 0) {
                $arDelivery = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
                $DELIVERY_NAME = $arDelivery["NAME"];
            }

            $arFields["DELIVERY_NAME"] = $DELIVERY_NAME.'<br>'.$arOrder_new["pickup"];
            $arFields["DELIVERY_PRICE"] = round($arOrder["PRICE_DELIVERY"], 0);
            if (IntVal($arFields["DELIVERY_PRICE"]) <= 0) {
                $arFields["DELIVERY_PRICE"] = '���������';
            }

            if (IntVal($arOrder["PAY_SYSTEM_ID"]) > 0) {
                $arPaySys = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"], $arOrder["PERSON_TYPE_ID"]);
                if ($arPaySys) {
                    $arFields["PAYMENT_NAME"] = htmlspecialcharsEx($arPaySys["NAME"]);
                }
            }

            $arFields["USER_DESCRIPTION"] = '';
            if (!empty($arOrder["USER_DESCRIPTION"])) {
                $arFields["USER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
            }

            $dbBasketTmp = CSaleBasket::GetList(
                array("NAME" => "ASC"),
                array("ORDER_ID" => $ORDER_ID),
                false,
                false
            );
            $DISCOUNT_PRICE = $BASE_PRICE = 0;
            while ($arBasketTmp = $dbBasketTmp->GetNext()) {
                $arBasketId[] = $arBasketTmp["PRODUCT_ID"];
                if(strstr($arBasketTmp['PRODUCT_XML_ID'],'#')) {
                    $arVals = array();
                    $tmp = explode('#',$arBasketTmp['PRODUCT_XML_ID']);
                    $id = (int)$tmp[1];
                    $res = CIblockElement::GetById($id)->GetNextElement();
                    $props = $res->GetProperties();
                    foreach ($props as $val) {
                        if (!strstr($val['CODE'],'CML2_') && !empty($val['VALUE'])) {
                            $arVals[] = $val['NAME'].': '.$val['VALUE'];
                        }
                    }
                    if (!empty($arVals)) {
                        $arBasketTmp['NAME'] .= ' ('.implode(', ',$arVals).')';
                    }
                }
                if ($arBasketTmp['DISCOUNT_PRICE'] > 0){
                    $DISCOUNT_PRICE += $arBasketTmp['DISCOUNT_PRICE'];
                }

                if ($arBasketTmp['BASE_PRICE'] > 0) {
                    $BASE_PRICE += $arBasketTmp['BASE_PRICE'] * $arBasketTmp['QUANTITY'];
                }

                $arBasketValue[] = $arBasketTmp;
            }
            if ($BASE_PRICE > 0) {
                $DISCOUNT_VALUE = $BASE_PRICE - $arOrder["PRICE"] + $arOrder["PRICE_DELIVERY"];
                $arFields["DISCOUNT_VALUE"] = round($DISCOUNT_VALUE, 0);
            }

            $BasketListStr = '';
            if (!empty($arBasketId)) {
                $arLinks = $arPictures = array();
                $res = CIblockElement::GetList(array(),array("ID" => $arBasketId), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
                while ($ob = $res->GetNextElement()) {
                    $arItemFields = $ob->GetFields();
                    $arLinks[$arItemFields["ID"]] = "http://".$_SERVER["SERVER_NAME"] . $arItemFields["DETAIL_PAGE_URL"];
                    $arPictures[$arItemFields["ID"]] = !empty($arItemFields["PREVIEW_PICTURE"]) ? $arItemFields["PREVIEW_PICTURE"] : $arItemFields["DETAIL_PICTURE"];
                }
                foreach ($arBasketValue as $arBasket) {
                    $arFile = CFile::ResizeImageGet($arPictures[$arBasket["PRODUCT_ID"]], array('width' => 130, 'height' => 200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $ItemPrice = round($arBasket["PRICE"], 0) . '<span></span>';
                    if ($arBasket["QUANTITY"] > 1) {
                        $ItemPrice = round($arBasket["QUANTITY"] * $arBasket["PRICE"], 0) . ' (' . round($arBasket["PRICE"], 0) . '/��.)';
                    }

                    $BasketListStr .= "
                    <div class=\"row\" style=\"width: 100%; overflow: hidden; border-bottom: 2px solid #f0f0f0; height: 140px;\">
                    <table style=\"width:800px;\">
                    <tr>
                    <td width=\"20%\">
                    <img src=\"http://" . $_SERVER["SERVER_NAME"] . $arFile['src'] . "\" alt=\"" . str_replace("&quot;","",$arBasket["~NAME"]) . "\" style=\"display:block\" width=\"130\">
                    </td>
                    <td width=\"40%\">
                    <div class=\"title\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;font-weight: bold;\">
                    "  . $arBasket["~NAME"]  . "
                    </div>
                    </td>
                    <td width=\"100px\">
                    <div class=\"count\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;\">
                    <span style=\"font-family: HelveticaBold; font-size: 16px !important;\">".round($arBasket["QUANTITY"], 0)." ��.</span>
                    </div>
                    </td>
                    <td width=\"230px\">
                    <div class=\"price\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;font-weight: bold;text-align: left;\">
                    <span style=\"display: block; padding-right:80px;\">".$ItemPrice."</span>
                    </div>
                    </td>
                    </tr>
                    </table>
                    </div>
                    ";
                }

                $arFields["BASKET_COUNT"] = count($arBasketValue);
            }
            $arFields["ORDER_LIST"] = $BasketListStr;
        }else{
            return false;
        }
    }
?>