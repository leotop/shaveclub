<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?


global $USER;
//arshow($arResult);
    global $KShopSectionID;
    $KShopSectionID = $arResult["IBLOCK_SECTION_ID"];

    if(($arParams["SHOW_MEASURE"] == "Y") && ($arResult["CATALOG_MEASURE"])){
        $arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
    }

    /*$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
    $arNotify = unserialize($notifyOption);*/

    $totalCount = CKShop::GetTotalCount($arResult);
    $arQuantityData = CKShop::GetQuantityArray($totalCount);
    $arAddToBasketData = CKShop::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);
    $useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
    // arshow($arAddToBasketData, true);
    $arBrand = array();
    if(strlen($arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]) && $arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"]){
        $resBrand = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"], "ID" => $arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]));
        if($arBrand = $resBrand->GetNext()){
            if($arParams["SHOW_BRAND_PICTURE"] == "Y" && ($arBrand["PREVIEW_PICTURE"] || $arBrand["DETAIL_PICTURE"])){
                $arBrand["IMAGE"] = CFile::ResizeImageGet(($arBrand["PREVIEW_PICTURE"] ? $arBrand["PREVIEW_PICTURE"] : $arBrand["DETAIL_PICTURE"]), array("width" => 120, "height" => 40), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
            }
        }
    }

    $arImages = array();
    if(is_array($arResult["DETAIL_PICTURE"])){
        $arImages[] = $arResult["DETAIL_PICTURE"];
    }
    foreach($arResult["MORE_PHOTO"] as $arPhoto){
        $arImages[] = $arPhoto;
    }
    $bIsOneImage = count($arImages) == 1;
    if($arImages){
        foreach($arImages as $i => $arImage){
            $arImages[$i]["BIG"] = CFile::ResizeImageGet($arImage, array("width" => 800, "height" => 600), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
            $arImages[$i]["SMALL"] = CFile::ResizeImageGet($arImage, array("width" => 310, "height" => 310), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
            $arImages[$i]["THUMB"] = CFile::ResizeImageGet($arImage, array("width" => 72, "height" => 72), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
        }
    }
    $edit_link="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arResult["IBLOCK_ID"]."&type=catalog&ID=".$arResult['ID']."&lang=ru&force_catalog=&filter_section=0&bxpublic=Y&from_module=iblock&return_url=%2Fcatalog%2F";
    $delete_link="/bitrix/admin/iblock_element_admin.php?IBLOCK_ID=".$arResult["IBLOCK_ID"]."&type=catalog&lang=ru&action=delete&ID=".$arResult['ID']."&return_url=%2Fcatalog%2F";
    $this->AddEditAction($arResult['ID'], $edit_link, CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arResult['ID'], $delete_link, CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
<div class="item_main_info" id="<?=$this->GetEditAreaId($arResult['ID']);?>">
    <div class="item_slider">
        <div class="ribbons">
            <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="ribon_hit"></span><?}?>
                <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="ribon_action"></span><?}?>
                <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="ribon_new"></span><?}?>
                <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="ribon_recomend"></span><?}?>
                <?endif;?>
        </div>
        <ul class="slides">
            <?foreach($arImages as $i => $arImage):?>
                <li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : '')?>>
                    <a href="<?=$arImage["BIG"]["src"]?>" <?=($bIsOneImage ? '' : 'rel="item_slider"')?> class="fancy">
                        <span class="zoom" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>"><i></i></span><b class="triangle"></b>
                        <div class="marks">
                            <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                                <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ){?><span class="mark hit"></span><?}?>
                                <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark share"></span><?}?>
                                <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark hit"></span><?}?>
                                <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark like"></span><?}?>
                                <?endif;?>
                        </div>
                        <img border="0" src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
                    </a>
                </li>
                <?endforeach;?>
            <?if(!$arImages):?>
                <li class="current">
                    <div class="marks">
                        <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                            <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark share"></span><?}?>
                            <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ){?><span class="mark hit"></span><?}?>
                            <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark like"></span><?}?>
                            <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark new"></span><?}?>
                            <?endif;?>
                    </div>
                    <img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
                </li>
                <?endif;?>
        </ul>
        <?if(count($arImages) > 1):?>
            <div class="thumbs">
                <ul id="thumbs">
                    <?foreach($arImages as $i => $arImage):?>
                        <li <?=(!$i ? 'class="current"' : '')?>>
                            <i class="triangle"><b></b></i>
                            <a><img border="0" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" /></a>
                        </li>
                        <?endforeach;?>
                </ul>
            </div>
            <span class="thumbs_navigation"></span>
        <?endif;?>

    </div>

    <div class="item_slider flex">
        <div class="ribbons">
            <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;?>
                <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
                <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
                <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
                <?endif;?>
        </div>
        <ul class="slides">
            <?foreach($arImages as $i => $arImage):?>
                <li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : '')?>>
                    <a href="<?=$arImage["BIG"]["src"]?>" rel="item_slider" class="fancy">
                        <span class="zoom" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>"><i></i></span><b class="triangle"></b>
                        <div class="marks">
                            <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                                <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark share"></span><?}?>
                                <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ){?><span class="mark hit"></span><?}?>
                                <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark like"></span><?}?>
                                <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark new"></span><?}?>
                                <?endif;?>
                        </div>
                        <img border="0" src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
                    </a>
                </li>
                <?endforeach;?>
            <?if(!$arImages):?>
                <li class="current">
                    <div class="marks">
                        <?if(is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                            <?if(in_array("STOCK", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark share"></span><?}?>
                            <?if(in_array("HIT", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ){?><span class="mark hit"></span><?}?>
                            <?if(in_array("RECOMMEND", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark like"></span><?}?>
                            <?if(in_array("NEW", $arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])){?><span class="mark new"></span><?}?>
                            <?endif;?>
                    </div>
                    <img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
                </li>
                <?endif;?>
        </ul>

    </div>

    <script type="text/javascript">
        $(".thumbs").flexslider({
            animation: "slide",
            selector: "#thumbs > li",
            slideshow: false,
            animationSpeed: 600,
            directionNav: true,
            controlNav: false,
            pauseOnHover: true,
            itemWidth: 99,
            animationLoop: false,
            controlsContainer: ".thumbs_navigation",
        });

        $(".item_slider.flex").flexslider({
            animation: "slide",
            slideshow: true,
            slideshowSpeed: 10000,
            animationSpeed: 600,
            directionNav: false,
            pauseOnHover: true,
            animationLoop: false,
        });

        $('.item_slider .thumbs li').first().addClass('current');

        $('.item_slider .thumbs').delegate('li:not(.current)', 'click', function(){
            $(this).addClass('current').siblings().removeClass('current').parents('.item_slider').find('.slides li').fadeOut(333);
            $(this).parents('.item_slider').find('.slides li').eq($(this).index()).addClass('current').stop().fadeIn(333);
        });
    </script>
    <div class="right_info">
        <table width="100%" border="0">
            <tr><td>
                    <div class="info_block clearfix">
                    <?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"){?>
                        <div class="props_block">
                            <?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
                                <?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
                                    <?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>

                                    <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
                                        <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                            <div class="char <?if($arProp["CODE"] == "COMPATIBILITY"){?>_COMPATIBILITY_<?}?>">
                                                <div class="char_name">
                                                    <span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
                                                </div>
                                                <div class="char_value">
                                                    <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                        <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                        <?else:?>
                                                        <?=$arProp["DISPLAY_VALUE"];?>
                                                        <?endif;?>
                                                </div>
                                            </div>
                                            <?endif;?>
                                        <?endif;?>
                                    <?endif;?>
                                <?endforeach;?>
                        </div>
                        <?};?>
                        <?if($arParams["USE_RATING"] == "Y"):?>
                       <?/*?>     <div class="rating">
                                <b class="block_title"><?=GetMessage("RATING");?>:</b>
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:iblock.vote",
                                        "element_rating",
                                        Array(
                                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                            "IBLOCK_ID" => $arResult["IBLOCK_ID"],
                                            "ELEMENT_ID" =>$arResult["ID"],
                                            "MAX_VOTE" => 5,
                                            "VOTE_NAMES" => array(),
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "DISPLAY_AS_RATING" => 'vote_avg'
                                        ),
                                        $component, array("HIDE_ICONS" =>"Y")
                                    );?>
                            </div>  <?*/?>
                            <?endif;?>
                        <?if(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
                            <div class="article">
                                <b class="block_title"><?=GetMessage("ARTICLE");?>:</b>
                                <?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?>
                            </div>
                            <?endif;?>
                        <?if($arBrand):?>
                            <div class="brand">
                                <?if(!$arBrand["IMAGE"]):?>
                                    <b class="block_title"><?=GetMessage("BRAND");?>:</b>
                                    <a href="<?=$arBrand["DETAIL_PAGE_URL"]?>"><?=$arBrand["NAME"]?></a>
                                    <?else:?>
                                    <a class="brand_picture" href="<?=$arBrand["DETAIL_PAGE_URL"]?>">
                                        <img border="0" src="<?=$arBrand["IMAGE"]["src"]?>" alt="<?=$arBrand["NAME"]?>" title="<?=$arBrand["NAME"]?>" />
                                    </a>
                                    <?endif;?>
                            </div>
                            <?endif;?>
                    </div>
                    <?if($arResult["OFFERS"] || $arResult["PRICES"]):?>
                        <hr />
                        <?endif;?>
                </td></tr>

            <?if($arResult["OFFERS"] || $arResult["PRICES"]):?>
                <tr><td>
                        <div class="price_block_wrapp">
                            <?if($arResult["OFFERS"]):?>
                                <div class="price_block">
                                    <div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arResult["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
                                </div>
                                <?elseif($arResult["PRICES"]):?>
                                <?
                                    $arCountPricesCanAccess = 0;
                                    foreach($arResult["PRICES"] as $key => $arPrice){
                                        if($arPrice["CAN_ACCESS"]){
                                            $arCountPricesCanAccess++;
                                        }
                                    }
                                ?>
                                <?foreach($arResult["PRICES"] as $key => $arPrice):?>
                                    <div class="price_block">
                                        <?if($arPrice["CAN_ACCESS"]):?>
                                            <?$price = CPrice::GetByID($arPrice["ID"]); ?>
                                            <?if($arCountPricesCanAccess > 1):?>
                                                <div class="price_name">
                                                    <?=$price["CATALOG_GROUP_NAME"];?>
                                                </div>
                                                <?endif;?>
                                            <?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
                                                <div class="price">
                                                    <?=$arPrice["PRINT_DISCOUNT_VALUE"]?>
                                                    <?if(($arParams["SHOW_MEASURE"] == "Y") && $arMeasure["SYMBOL_RUS"]):?>
                                                        <small>/<?=$arMeasure["SYMBOL_RUS"]?></small>
                                                        <?endif;?>
                                                </div>
                                                <div class="price discount">
                                                    <?=GetMessage("WITHOUT_DISCOUNT")?>:&nbsp;<strike><?=$arPrice["VALUE"]?> <?= GetMessage('PRICE_RUB') ?></strike>
                                                </div>
                                                <?else:?>
                                                <div class="price">
                                                    <?=$arPrice["PRINT_VALUE"]?>
                                                    <?if(($arParams["SHOW_MEASURE"] == "Y") && $arMeasure["SYMBOL_RUS"]):?>
                                                        <small>/<?=$arMeasure["SYMBOL_RUS"]?></small>
                                                        <?endif;?>
                                            </div>
                                            <?endif;?>
                                            <?endif;?>
                                    </div>
                                    <?endforeach;?>
                                <?endif;?>
                        </div>
                    </td></tr>
                <?endif;?>

            <?if(!$arResult["OFFERS"]):?>
                <tr><td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="buttons_block" style="<?=(($arResult["OFFERS"] || $arResult["PRICES"]) ? '' : 'margin-top:0;')?>"><tr>
                                <!--noindex-->
                                <?if($arAddToBasketData["ACTION"] == "ADD"):?>
                                    <td class="counter_block" data-item="<?=$arResult["ID"];?>">
                                        <?if($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && !count($arResult["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD"):?>
                                            <span class="minus">-</span>
                                            <input type="text" class="text" name="count_items"  id="count_items_sel" value="<?=($arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : "1");?>" />
                                            <span class="plus">+</span>
                                            <select onchange="$('.buttons_block #count_items_sel').val($('#count_items_sel_phone').val());" id="count_items_sel_phone" style="display: none;">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                            </select>
                                            <?endif;?>
                                    </td>
                                    <?endif;?>
                                <?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
                                    <td class="buy_buttons_wrapp clearfix<?=(($arAddToBasketData["ACTION"] == "SUBSCRIBE" || $arAddToBasketData["ACTION"] == "ORDER") ? " subscribe" : "")?>">
                                        <?=$arAddToBasketData["HTML"]?>
                                        <?if($arAddToBasketData["ACTION"] == "ADD"):?>
                                            <a class="basket_button button30 one_click" data-item="<?=$arResult["ID"]?>" data-quantity="<?=($totalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $totalCount)?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                                                <span><?=GetMessage('ONE_CLICK_BUY')?></span>
                                            </a>
                                            <?endif;?>
                                    </td>
                                    <?endif;?>
                                <?if($arAddToBasketData["ACTION"] == "SUBSCRIBE"):?>
                                    <td class="description">
                                        <?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_description.php", array(), array("MODE" => "text", "NAME" => GetMessage("SUBSCRIBE_DESCRIPTION")));?>
                                    </td>
                                    <?endif;?>
                                <?if($arAddToBasketData["ACTION"] == "ORDER"):?>
                                    <td class="description">
                                        <?$APPLICATION->IncludeFile(SITE_DIR."include/order_description.php", array(), array("MODE" => "text", "NAME" => GetMessage("ORDER_DESCRIPTION")));?>
                                    </td>
                                    <?endif;?>
                                <!--/noindex-->
                            </tr></table>
                    </td></tr>
                <?endif;?>

            <tr><td>
                    <div class="extended_info clearfix<?=($arParams["USE_STORE"] == "Y" ? " open_stores" : "")?>">
                        <?if(strlen($arQuantityData["TEXT"])):?>
                            <div class="availability-row" style="display:none;"><?=$arQuantityData["HTML"]?></div>
                            <?endif;?>
                        <?if((!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arResult["CAN_BUY"]) || ($arParams["DISPLAY_COMPARE"] == "Y")):?>
                            <div class="like_icons">
                                <!--noindex-->
                                <?if(!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arResult["CAN_BUY"]):?>
                                    <a rel="nofollow" class="wish_item" data-item="<?=$arResult["ID"]?>">
                                        <span class="icon"><i></i></span><b class="triangle"></b>
                                        <span class="value pseudo"><?=GetMessage('CT_BCE_CATALOG_IZB')?></span>
                                        <span class="value pseudo added"><?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?></span>
                                    </a>
                                    <?endif;?>
                                <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                    <a rel="nofollow" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" href="<?=$arResult["COMPARE_URL"]?>" class="compare_item">
                                        <span class="icon"><i></i></span><b class="triangle"></b>
                                        <span class="value pseudo"><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></span>
                                        <span class="value pseudo added"><?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?></span>
                                    </a>
                                    <?endif;?>
                                <!--/noindex-->
                            </div>
                            <?endif;?>
                    </div>
                    <div class="adaptive_extended_info_wrapp">
                        <?if(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || strlen($arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"])):?>
                            <div class="adaptive_extended_info">
                                <?if(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
                                    <div class="article">
                                        <b class="block_title"><?=GetMessage("ARTICLE");?>:</b>
                                        <?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?>
                                    </div>
                                    <?endif;?>
                                <?if($arBrand):?>
                                    <div class="brand">
                                        <?if(!$arBrand["IMAGE"]):?>
                                            <b class="block_title"><?=GetMessage("BRAND");?>:</b>
                                            <a href="<?=$arBrand["DETAIL_PAGE_URL"]?>"><?=$arBrand["NAME"]?></a>
                                            <?else:?>
                                            <a class="brand_picture" href="<?=$arBrand["DETAIL_PAGE_URL"]?>">
                                                <img border="0" src="<?=$arBrand["IMAGE"]["src"]?>" alt="<?=$arBrand["NAME"]?>" title="<?=$arBrand["NAME"]?>" />
                                            </a>
                                            <?endif;?>
                                    </div>
                                    <?endif;?>
                            </div>
                            <?endif;?>
                        <img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png">
                    </div>
                </td></tr>
            <?if(strlen($arResult["PREVIEW_TEXT"])):?>
                <!--<tr><td class="preview_text"><?=$arResult["PREVIEW_TEXT"]?></td></tr>  -->
            <?endif;?>
        </table>
        <hr class="separator" />
        <div class="element_detail_text">
            <?=$arResult["PREVIEW_TEXT"]?>

<!--            <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div data-yashareType="button" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir" class="yashare-auto-init" data-limit="1" data-description="<?=$arResult["NAME"]?>" data-title="<?=$arResult["NAME"]?>" data-yashareL10n="ru" ></div>     -->
            <?//$APPLICATION->IncludeFile(SITE_DIR."include/element_detail_text.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('CT_BCE_CATALOG_DOP_DESCR')));?>
            <?//$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
        </div>
        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
        <div class="ya-share2"  data-title="<?=$arResult["NAME"]?>" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter" data-limit="5"></div>
    </div>
    <div class="clearleft"></div>

    <div class="recommended">           
        <?if($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"]){?>
        <h2 style="font-size: 18px; margin-top: 20px;">� ���� ������� �����������:</h2>
            <?
            foreach($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"] as $item_product){
                $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE","*","DETAIL_PAGE_URL");
                $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ID" => $item_product, "ACTIVE"=>"Y");
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                {
                 $arFields = $ob->GetFields();

                 $url = explode('/', $arFields["DETAIL_PAGE_URL"]);
                 $url_image = CFile::GetPath($arFields["PREVIEW_PICTURE"]);?>

                 <div class="catalog_item image_recomend">
                        <div class="image">
                            <a href="<?='/'.$url[1].'/'.$url[2].'/'.$arFields["ID"].'/'?>" class="thumb">
                                <img border="0" src="<?=$url_image?>">
                            </a>
                        </div>
                        <div class="item_info">
                            <div class="item-title">
                                <a href="<?='/'.$url[1].'/'.$url[2].'/'.$arFields["ID"].'/'?>"><span><?=mb_strimwidth($arFields["NAME"], 0, 45, "..." )?></span></a>  <!--'/'.$url[3]. -->
                            </div>
                        </div>

                    </div>
                <?}
            }?>
        <?}?>
    </div>

    <?if($arParams["SHOW_KIT_PARTS"] == "Y" && $arResult["SET_ITEMS"]):?>
        <div class="set_wrapp">
            <div class="title"><?=GetMessage("GROUP_PARTS_TITLE")?></div>
            <ul>
                <?
                foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):
                ?>
                    <li class="item">
                        <div class="image">
                            <a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
                                <?if($arSetItem["PREVIEW_PICTURE"]):?>
                                    <?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                                        <img border="0" src="<?= $img["src"] ?>" alt="<?= $arSetItem["NAME"]; ?>" title="<?= $arSetItem["NAME"]; ?>" />
                                    <?elseif($arSetItem["DETAIL_PICTURE"]):?>
                                    <?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                        <img border="0" src="<?= $img["src"] ?>" alt="<?= $arSetItem["NAME"]; ?>" title="<?= $arSetItem["NAME"]; ?>" />
                                    <?else:?>
                                        <img border="0" src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo_small.png" alt="<?= $arSetItem["NAME"]; ?>" title="<?= $arSetItem["NAME"]; ?>" />
                                    <?endif;?>
                            </a>
                        </div>
                        <div class="item_info">
                            <div class="item-title">
                                <a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>"><span><?=$arSetItem["NAME"]?></span></a>
                                <p>����������: <b><?=$arSetItem["QUANTITY"];?> ��.</b></p>
                            </div>
                            <?if($arParams["SHOW_KIT_PARTS_PRICES"] == "Y"):?>
                                <div class="cost clearfix">
                                    <?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
                                        <?
                                            $arCountPricesCanAccess = 0;
                                            if(!empty($arItem["PRICES"])){
                                            foreach($arItem["PRICES"] as $key => $arPrice){
                                                if($arPrice["CAN_ACCESS"]){
                                                    $arCountPricesCanAccess++;
                                                }
                                            }
                                            }
                                        ?>
                                            <?if($arSetItem["MIN_PRICE"]["CAN_ACCESS"]):?>
                                                <?$price = CPrice::GetByID($arSetItem["MIN_PRICE"]["ID"]);?>
                                                <?if($arCountPricesCanAccess > 1):?>
                                                    <div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
                                                    <?endif;?>
                                                <?if($arSetItem["MIN_PRICE"]["VALUE"] > $arSetItem["MIN_PRICE"]["DISCOUNT_VALUE"]):?>
                                                    <div class="price">
                                                        <?=$arSetItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"];?>
                                                        <?if(($arParams["SHOW_MEASURE"] == "Y") && $arMeasure["SYMBOL_RUS"]):?>
                                                            <small>/<?=$arMeasure["SYMBOL_RUS"]?></small>
                                                            <?endif;?>
                                                    </div>
                                                    <div class="price discount">
                                                        <?=GetMessage("WITHOUT_DISCOUNT")?>:&nbsp;<strike><?=$arSetItem["MIN_PRICE"]["VALUE"]?> <?= GetMessage('PRICE_RUB') ?></strike>
                                                    </div>
                                                    <?else:?>
                                                    <div class="price">
                                                        <?=$arSetItem["MIN_PRICE"]["PRINT_VALUE"];?>
                                                        <?if(($arParams["SHOW_MEASURE"] == "Y") && $arMeasure["SYMBOL_RUS"]):?>
                                                            <small>/<?=$arMeasure["SYMBOL_RUS"]?></small>
                                                            <?endif;?>
                                                </div>
                                                <?endif;?>
                                                <?endif;?>
                                    <?endforeach;?>
                                </div>
                                <?endif;?>
                        </div>
                    </li>
                    <?if($arResult["SET_ITEMS"][$iii + 1]):?>
                        <li class="separator">&nbsp;</li>
                        <?endif;?>
                    <?endforeach;?>
            </ul>
        <div class="total_wrapp result">
        <div class="total_price bx_item_set_result_block">
            <span class="total_title"><?= GetMessage("CATALOG_SET_SUM") ?>:</span>
            <span class="price_block">
                <div class="price bx_item_set_current_price"> <?= $arResult["SET_ITEM"]["PRICE"] ?> <?= GetMessage('PRICE_RUB') ?></div>
                <?if ($arResult["SET_ITEM"]["PRICE_DISCOUNT_VALUE"] < $arResult["SET_ITEM"]["PRICE_VALUE"]):?>
                    <div class="price discount">
                        <?= GetMessage("CATALOG_SET_WITHOUT_DISCOUNT") ?>: <strike class="bx_item_set_old_price"><?= $arResult["SET_ITEM"]["PRICE_VALUE"] ?> <?= GetMessage('PRICE_RUB') ?></strike>&nbsp;
                        <?if ($arResult["SET_ITEM"]["PRICE_DISCOUNT_VALUE"]):?>
                            <div class="bx_item_set_economy_price"><?= GetMessage("CATALOG_SET_DISCOUNT_DIFF", array("#PRICE#" => $arResult["SET_ITEM"]["SAVING"])) ?></div>
                        <?endif?>
                    </div>
                <?endif?>

            </span>
        </div>
    </div>

        </div>
        <script type="text/javascript">
            $('.set_wrapp').ready(function(){
                //$('.set_wrapp').equalize({children: '.item .cost', reset: true});
                $('.set_wrapp').equalize({children: '.item .item-title', reset: true});
                $('.set_wrapp').equalize({children: 'li', reset: true});
            });
        </script>
        <?endif;?>
</div>

<?if($arResult['OFFERS']):?>
    <?if($arResult['OFFER_GROUP']):?>
        <?foreach($arResult['OFFERS'] as $arOffer):?>
            <?if(!$arOffer['OFFER_GROUP']) continue;?>
            <span id="<?=$arItemIDs['OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
                <?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
                        array(
                            "IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
                            "ELEMENT_ID" => $arOffer['ID'],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        ), $component, array("HIDE_ICONS" => "Y")
                    );?>
            </span>
            <?endforeach;?>
        <?endif;?>
    <?else:?>
    <?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
            array(
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ELEMENT_ID" => $arResult["ID"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            ), $component, array("HIDE_ICONS" => "Y")
        );?>
    <?endif;?>

<div class="tabs_section">
    <ul class="tabs main_tabs">
        <?
            $iTab = 0;
            $showProps = false;
            if($arResult["DISPLAY_PROPERTIES"]){
                foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
                    if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))){
                        if(!is_array($arProp["DISPLAY_VALUE"])){
                            $arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
                        }
                    }

                    if($arProp["DISPLAY_VALUE"]){
                        foreach($arProp["DISPLAY_VALUE"] as $value){
                            if(strlen($value)){
                                $showProps = true;
                                break;
                            }
                        }
                    }
                }
            }
        ?>
        <?if($arResult["OFFERS"]):?>
            <li class="prices_tab<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("OFFER_PRICES")?></span>
            </li>
            <?endif;?>
        <?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"]) || count($arResult["SERVICES"]) || (count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("DESCRIPTION_TAB")?></span>
            </li>
            <?endif;?>
        <?if($arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB" && $showProps):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("PROPERTIES_TAB")?></span>
            </li>
            <?endif;?>
        <?/*if($arParams["USE_REVIEW"] == "Y"):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>" id="product_reviews_tab">
                <span><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></span>
            </li>
            <?endif;*/?>

        <?if($arResult["PROPERTIES"]["COMPATIBILITY"]["VALUE"]):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage('COMPATIBILITY')?></span>
            </li>
            <?endif;?>
        <?if($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"] and empty($arResult["PROPERTIES"]["COMPATIBILITY"]["VALUE"])):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage('compatibleManufacturer')?></span>
            </li>
            <?endif;?>
        <?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage('ASK_TAB')?></span>
            </li>
            <?endif;?>

        <?if($arParams["USE_REVIEW"] == "Y"):?>
        <?if($USER->IsAuthorized()){?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>" id="product_reviews_tab">
                <span><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></span>
            </li>
        <?}?>
            <?endif;?>
        <?/*if($useStores && !$arResult["OFFERS"]):?>
            <li class="stores_tab<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("STORES_TAB");?></span>
            </li>
            <?endif;?>
        <?if(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"]) || strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"]) || $arResult["SECTION_FULL"]["UF_VIDEO"] || $arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"]):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("VIDEO_TAB")?></span>
            </li>
            <?endif;?>
        <?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <span><?=GetMessage("ADDITIONAL_TAB");?></span>
            </li>
            <?endif;*/?>
    </ul>

    <ul class="tabs_content">
        <?$show_tabs = false;?>
        <?$iTab = 0;?>
        <?$showSkUImages = (in_array('PREVIEW_PICTURE', $arParams['OFFERS_FIELD_CODE']) || in_array('DETAIL_PICTURE', $arParams['OFFERS_FIELD_CODE']));?>
        <?if($arResult["OFFERS"]):?>
            <li class="prices_tab<?=(!($iTab++) ? ' cur' : '')?>">
                <table class="colored offers_table" cellspacing="0" cellpadding="0" width="100%" border="0">
                    <thead>
                        <tr>
                            <?if($useStores):?>
                                <td></td>
                                <?endif;?>
                            <?if($showSkUImages):?>
                                <td class="property" width="50"></td>
                                <?endif;?>
                            <?foreach ($arResult["SKU_PROPERTIES"] as $key => $arProp){?>
                                <?if(!$arProp["IS_EMPTY"]):?>
                                    <td class="property">
                                        <span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><a class="tooltip_close">&times;</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
                                    </td>
                                    <?endif;?>
                                <?}?>
                            <td class="price_th"><?=GetMessage("CATALOG_PRICE")?></td>
                            <?if($arQuantityData["RIGHTS"]["SHOW_QUANTITY"]):?>
                                <td class="count_th"><?=GetMessage("AVAILABLE")?></td>
                                <?endif;?>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?$numProps = count($arResult["SKU_PROPERTIES"]);?>
                        <?foreach ($arResult["SKU_ELEMENTS"] as $key => $arSKU){?>
                            <?
                                if($arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"]){
                                    $sMeasure = $arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"].".";
                                }
                                else{
                                    $sMeasure = GetMessage("MEASURE_DEFAULT").".";
                                }
                                $skutotalCount = CKShop::CheckTypeCount($arSKU["CATALOG_QUANTITY"]);
                                $arskuQuantityData = CKShop::GetQuantityArray($skutotalCount, array('quantity-wrapp', 'quantity-indicators'));
                                $arskuAddToBasketData = CKShop::GetAddToBasketArray($arSKU, $skutotalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false);
                            ?>
                            <?
                                if(($arParams["SHOW_MEASURE"]=="Y")&&($arSKU["CATALOG_MEASURE"])){
                                    $symb = substr($arSKU["PRICE"], strrpos($arSKU["PRICE"], ' '));
                                    $arSCUMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arSKU["CATALOG_MEASURE"]), false, false, array())->GetNext();
                                }
                            ?>
                            <?$collspan = 1;?>
                            <tr>
                                <?if($useStores):?>
                                    <td class="opener">
                                        <?$collspan++;?>
                                        <span class="opener_icon"><i></i></span>
                                    </td>
                                    <?endif;?>
                                <?if($showSkUImages):?>
                                    <?$collspan++;?>
                                    <td class="property">
                                        <?if($imgID = ($arResult['OFFERS'][$key]['PREVIEW_PICTURE'] ? $arResult['OFFERS'][$key]['PREVIEW_PICTURE'] : ($arResult['OFFERS'][$key]['DETAIL_PICTURE'] ? $arResult['OFFERS'][$key]['DETAIL_PICTURE'] : false))):?>
                                            <?$arImg = CFile::ResizeImageGet($imgID, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                            <?if($arResult['OFFERS'][$key]['DETAIL_PICTURE']):?>
                                                <a href="<?=CFile::GetPath($arResult['OFFERS'][$key]['DETAIL_PICTURE'])?>" class="fancy">
                                                    <?endif;?>
                                                <img src="<?=$arImg['src']?>" alt="" />
                                                <?if($arResult['OFFERS'][$key]['DETAIL_PICTURE']):?>
                                                </a>
                                                <?endif;?>
                                            <?endif;?>
                                    </td>
                                    <?endif;?>
                                <?for( $i = 0; $i < $numProps; $i++ ){?>
                                    <?if(!$arResult["SKU_PROPERTIES"][$i]["IS_EMPTY"]):?>
                                        <?$collspan++;?>
                                        <td class="property">
                                            <?=!empty($arSKU[$i]) ? $arSKU[$i] : GetMessage('NOT_PROP')?>
                                        </td>
                                        <?endif;?>
                                    <?}?>
                                <td class="price">
                                    <?$collspan++;?>
                                    <?$arCountPricesCanAccess = 0;
                                        foreach($arSKU["PRICES"] as $key => $arPrice){
                                            if($arPrice["CAN_ACCESS"]){
                                                $arCountPricesCanAccess++;
                                            }
                                    }?>
                                    <?foreach($arSKU["PRICES"] as $key => $arPrice){?>
                                        <?if($arPrice["CAN_ACCESS"]){?>
                                            <?if($arCountPricesCanAccess > 1):?>
                                                <?$price = CPrice::GetByID($arPrice["ID"]); ?>
                                                <div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
                                                <?endif;?>
                                            <?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
                                                <div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
                                                <div class="price discount">
                                                    <strike><?=$arPrice["PRINT_VALUE"];?></strike>
                                                </div>
                                                <?}else{?>
                                                <div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
                                                <?}?>
                                            <?}?>
                                        <?}?>
                                    <?/*if( intval($arSKU["DISCOUNT_PRICE"]) > 0 && $arSKU["PRICE"] > 0){?>
                                        <span class="price"><?=$arSKU["DISCOUNT_PRICE"]?><?if (($arParams["SHOW_MEASURE"]=="Y")&&$arSCUMeasure["SYMBOL_RUS"]):?><small>/<?=$arSCUMeasure["SYMBOL_RUS"]?></small><?endif;?></span><br />
                                        <span class="price discount"><strike><?=$arSKU["PRICE"]?></strike></span>
                                        <?}else{?>
                                        <span class="price">
                                        <?=$arSKU["PRICE"]?><?if (($arParams["SHOW_MEASURE"]=="Y")&&$arSCUMeasure["SYMBOL_RUS"]):?><small>/<?=$arSCUMeasure["SYMBOL_RUS"]?></small><?endif;?>
                                        </span>
                                    <?}*/?>
                                </td>
                                <?if(strlen($arskuQuantityData["TEXT"])):?>
                                    <?$collspan++;?>
                                    <td class="count">
                                        <?=$arskuQuantityData["HTML"]?>
                                    </td>
                                    <?endif;?>
                                <!--noindex-->
                                <?if($arskuAddToBasketData["ACTION"] == "ADD"):?>
                                    <td class="counter_block" data-item="<?=$arSKU["ID"];?>">
                                        <?$collspan++;?>
                                        <?if($arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && !count($arSKU["OFFERS"]) && $arskuAddToBasketData["ACTION"] == "ADD"):?>
                                            <span class="minus">-</span>
                                            <input type="text" class="text" name="count_items" value="<?=($arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : "1");?>" />
                                            <span class="plus">+</span>
                                            <?endif;?>
                                    </td>
                                    <?endif;?>
                                <td class="buy" <?=($arskuAddToBasketData["ACTION"] !== "ADD" ? 'colspan="3"' : "")?>>
                                    <?if($arskuAddToBasketData["ACTION"] !== "ADD"):?>
                                        <?$collspan += 3;?>
                                        <?else:?>
                                        <?$collspan++;?>
                                        <?endif;?>
                                    <?=$arskuAddToBasketData["HTML"]?>
                                </td>
                                <?if($arskuAddToBasketData["ACTION"] == "ADD"):?>
                                    <td class="one_click_buy">
                                        <?$collspan++;?>
                                        <a class="basket_button one_click" data-item="<?=$arSKU["ID"]?>" data-quantity="<?=($skutotalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $skutotalCount)?>" onclick="oneClickBuy('<?=$arSKU["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                                            <span><?=GetMessage('ONE_CLICK_BUY')?></span>
                                        </a>
                                    </td>
                                    <?endif;?>
                                <!--/noindex-->
                            </tr>

                            <?if($useStores):?>
                                <?$collspan--;?>
                                <tr class="offer_stores"><td colspan="<?=$collspan?>">
                                    <?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "product_stores_amount", array(
                                            "PER_PAGE" => "10",
                                            "USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
                                            "SCHEDULE" => $arParams["SCHEDULE"],
                                            "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
                                            "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                                            "ELEMENT_ID" => $arSKU["ID"],
                                            "STORE_PATH"  =>  $arParams["STORE_PATH"],
                                            "MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
                                            "MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
                                            "SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
                                            "SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                                            "USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
                                            "USER_FIELDS" => $arParams['USER_FIELDS'],
                                            "FIELDS" => $arParams['FIELDS'],
                                            "STORES" => $arParams['STORES'],
                                            ),
                                            $component
                                        );?>
                                </tr>
                                <?endif;?>
                            <?}?>
                    </tbody>
                </table>
            </li>
            <?endif;?>

        <?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"])  || count($arResult["SERVICES"]) || (count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <?if(is_array($arResult["STOCK"]) && !empty($arResult["STOCK"])):?>
                    <?foreach($arResult["STOCK"] as $key => $arStockItem):?>
                        <div class="stock_board">
                            <div class="title"><?=GetMessage("CATALOG_STOCK_TITLE")?></div>
                            <div class="txt"><?=$arStockItem["PREVIEW_TEXT"]?></div>
                            <a class="read_more" href="<?=$arStockItem["DETAIL_PAGE_URL"]?>"><?=GetMessage("CATALOG_STOCK_VIEW")?></a>
                        </div>
                        <?endforeach;?>
                    <?endif;?>

                <?if(strlen($arResult["DETAIL_TEXT"])):?>
                    <div class="detail_text"><?=$arResult["DETAIL_TEXT"]?></div>
                <?endif;?>

                <?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB"):?>
                    <?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>

                        <?else:?>
                        <table class="props_table">
                            <?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
                                <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
                                    <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                        <tr>
                                            <td class="char_name">
                                                <span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
                                        </td>
                                        <td class="char_value">
                                            <span>
                                                <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                    <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                    <?else:?>
                                                    <?=$arProp["DISPLAY_VALUE"];?>
                                                    <?endif;?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?endif;?>
                                <?endif;?>
                            <?endforeach;?>
                    </table>
                    <?endif;?>
                    <?endif;?>
                <?if($arResult["SERVICES"]):?>
                    <div class="services_block">
                        <img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
                        <h4><?=GetMessage("SERVICES_TITLE")?></h4>
                        <?foreach($arResult["SERVICES"] as $arService):?>
                            <span class="item">
                                <a href="<?=$arService["DETAIL_PAGE_URL"]?>">
                                    <i class="arrow"><b></b></i>
                                    <span class="link"><?=$arService["NAME"]?></span>
                                </a>
                            </span>
                            <?endforeach;?>
                    </div>
                    <?endif;?>
                <?
                    $arFiles = array();
                    if($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]){
                        $arFiles = $arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"];
                    }
                    else{
                        $arFiles = $arResult["SECTION_FULL"]["UF_FILES"];
                    }
                    if(is_array($arFiles)){
                        foreach($arFiles as $key => $value){
                            if(!intval($value)){
                                unset($arFiles[$key]);
                            }
                        }
                    }
                ?>
                <?if($arFiles):?>
                    <img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
                    <div class="files_block">
                        <h4><?=GetMessage("DOCUMENTS_TITLE")?></h4>
                        <?foreach($arFiles as $arItem):?>
                            <?$arItem = CFile::GetFileArray($arItem);?>
                            <div class="file_type clearfix<? if( $arItem["CONTENT_TYPE"] == 'application/pdf' ){ echo " pdf"; } elseif( $arItem["CONTENT_TYPE"] == 'application/octet-stream' ){ echo " word"; }
                                    elseif( $arItem["CONTENT_TYPE"] == 'application/xls' ){ echo " excel"; } elseif( $arItem["CONTENT_TYPE"] == 'image/jpeg' ){ echo " jpg"; } elseif( $arItem["CONTENT_TYPE"] == 'image/tiff' ){ echo " tiff"; }?>">
                                <i class="icon"></i>
                                <div class="description">
                                    <?$fileName = substr($arItem["ORIGINAL_NAME"], 0, strrpos($arItem["ORIGINAL_NAME"], '.'));?>
                                    <a target="_blank" href="<?=$arItem["SRC"]?>"><?if ($arItem["DESCRIPTION"]):?><?=$arItem["DESCRIPTION"]?><?elseif($fileName):?><?=$fileName?><?endif;?></a>
                                    <span class="size"><?=GetMessage('CT_NAME_SIZE')?>:
                                        <?
                                            $filesize = $arItem["FILE_SIZE"];
                                            if($filesize > 1024){
                                                $filesize = ($filesize / 1024);
                                                if($filesize > 1024){
                                                    $filesize = ($filesize / 1024);
                                                    if($filesize > 1024){
                                                        $filesize = ($filesize / 1024);
                                                        $filesize = round($filesize, 1);
                                                        echo $filesize.GetMessage('CT_NAME_GB');
                                                    }
                                                    else{
                                                        $filesize = round($filesize, 1);
                                                        echo $filesize.GetMessage('CT_NAME_MB');
                                                    }
                                                }
                                                else{
                                                    $filesize = round($filesize, 1);
                                                    echo $filesize.GetMessage('CT_NAME_KB');
                                                }
                                            }
                                            else{
                                                $filesize = round($filesize, 1);
                                                echo $filesize.GetMessage('CT_NAME_b');
                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <?endforeach;?>
                    </div>
                    <?endif;?>
            </li>
            <?endif;?>

        <?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB"):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
                    <div class="props_block">
                        <?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
                            <?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
                                <?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
                                <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
                                    <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                        <div class="char">
                                            <div class="char_name">
                                                <span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
                                            </div>
                                            <div class="char_value">
                                                <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                    <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                    <?else:?>
                                                    <?=$arProp["DISPLAY_VALUE"];?>
                                                    <?endif;?>
                                            </div>
                                        </div>
                                        <?endif;?>
                                    <?endif;?>
                                <?endif;?>
                            <?endforeach;?>
                    </div>
                    <?else:?>
                    <table class="props_table">
                        <?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
                            <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
                                <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                    <tr>
                                        <td class="char_name">
                                            <span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
                                    </td>
                                    <td class="char_value">
                                        <span>
                                            <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                <?else:?>
                                                <?=$arProp["DISPLAY_VALUE"];?>
                                                <?endif;?>
                                        </span>
                                    </td>
                                </tr>
                                <?endif;?>
                            <?endif;?>
                        <?endforeach;?>
                </table>
                <?endif;?>
            </li>
            <?endif;?>

          <?if($arResult["PROPERTIES"]["COMPATIBILITY"]["VALUE"] ):?>
               <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                   <? foreach($arResult["PROPERTIES"]["COMPATIBILITY"]["VALUE"] as $item_product){
                        $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE","*","DETAIL_PAGE_URL");
                        $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ID" => $item_product);       //, "ACTIVE"=>"Y"
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        while($ob = $res->GetNextElement())
                        {
                         $arFields = $ob->GetFields();
                         $url = explode('/', $arFields["DETAIL_PAGE_URL"]);
                         $url_image = CFile::GetPath($arFields["PREVIEW_PICTURE"]);?>
                         <?// ���� ����� ���������, �� �������� ��� � ������� ?>
                         <div class="catalog_item image_recomend" style=" width: 150px; ">
                                <div class="image">
                                <? if($arFields["ACTIVE"] == "N") { ?>

                                        <img border="0" src="<?=$url_image?>" >
                                                  <?} else {?>
                                     <a href="<?='/'.$url[1].'/'.$url[2].'/'.$arFields["ID"].'/'?>" class="thumb"> <!-- '/'.$url[3].-->
                                        <img border="0" src="<?=$url_image?>" >
                                    </a>
                                    <?}?>
                                </div>

                                <div class="item_info">
                                    <div class="item-title">
                                    <? if($arFields["ACTIVE"] == "N") { ?>
                                        <span><?=$arFields["NAME"];?></br> ��� � �������</span> <?} else {?>
                                        <a href="<?='/'.$url[1].'/'.$url[2].'/'.$arFields["ID"].'/'?>"><span><?=$arFields["NAME"];?></span></a>
                                             <?}?>
                                    </div>
                                </div>

                            </div>
                        <?}
                    }?>
                    <?
                       // if(empty($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"])&&is_array($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"])){
                            foreach($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"] as $propValue){
                                $resCompany = CIBlockElement::GetById($propValue);
                                while($obComp = $resCompany->GetNext()){
                                    $arPrevImage = CFile::GetPath($obComp['PREVIEW_PICTURE']);
                                    ?>
                                    <div class="catalog_item image_recomend" style=" width: 150px; ">
                                    <div class="image">
                                        <img border="0" src="<?=$arPrevImage?>" >
                                    </div>
                                    <div class="item_info">
                                        <div class="item-title">
                                            <span class="compatibilityName"><?=$obComp["NAME"]?></span>
                                        </div>
                                    </div>

                                    </div>
                                <?//}
                            }
                        }
                     ?>
               </li>
          <?endif;?>

          <?if($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"] ):?>
           <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <?foreach($arResult["PROPERTIES"]["compatibleManufacturer"]["VALUE"] as $propValue){
                    $resCompany = CIBlockElement::GetById($propValue);
                    while($obComp = $resCompany->GetNext()){
                        $arPrevImage = CFile::GetPath($obComp['PREVIEW_PICTURE']);
                        ?>
                        <div class="catalog_item image_recomend" style=" width: 150px; ">
                        <div class="image">
                            <img border="0" src="<?=$arPrevImage?>" >
                        </div>
                        <div class="item_info">
                            <div class="item-title">
                                <span class="compatibilityName"><?=$obComp["NAME"]?></span>
                            </div>
                        </div>

                        </div>
                    <?
                    }
                }?>
           </li>
           <?endif;?>
        <?/*if($arParams["USE_REVIEW"] == "Y"):?>
            <li class="<?=(!($iTab++) ? '' : '')?>">
            </li>
            <?endif;*/?>
        <?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <?//$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
                <?
                $APPLICATION->IncludeComponent(
	                "bitrix:form.result.new",
	                "inline",
	                array(
		                "SEF_MODE" => "Y",
		                "WEB_FORM_ID" => "3",
		                "LIST_URL" => "",
		                "EDIT_URL" => "",
		                "SUCCESS_URL" => "/include/complite_new.php",
		                "CHAIN_ITEM_TEXT" => "",
		                "CHAIN_ITEM_LINK" => "",
		                "IGNORE_CUSTOM_TEMPLATE" => "N",
		                "USE_EXTENDED_ERRORS" => "N",
		                "CACHE_TYPE" => "A",
		                "CACHE_TIME" => "3600",
		                "SEF_FOLDER" => "/",
                        "VARIABLE_ALIASES" => Array(
                         "WEB_FORM_ID" => "WEB_FORM_ID",
                         "RESULT_ID" => "RESULT_ID"
                         ),
		                "COMPONENT_TEMPLATE" => "inline"
	                ),
	                false
                );
                ?>
                <div id="ask_block"></div>
            </li>
            <?endif;?>
        <?if($useStores && (!is_array($arResult["OFFERS"]) || empty($arResult["OFFERS"]))):?>
            <li class="stores_tab<?=(!($iTab++) ? ' cur' : '')?>">
                <?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "product_stores_amount", array(
                        "PER_PAGE" => "10",
                        "USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
                        "SCHEDULE" => $arParams["SCHEDULE"],
                        "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
                        "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                        "ELEMENT_ID" => $arResult["ID"],
                        "STORE_PATH"  =>  $arParams["STORE_PATH"],
                        "MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
                        "MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
                        "SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
                        "SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                        "USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
                        "USER_FIELDS" => $arParams['USER_FIELDS'],
                        "FIELDS" => $arParams['FIELDS'],
                        "STORES" => $arParams['STORES'],

                        ),
                        $component
                    );?>
            </li>
            <?endif;?>
        <?if ($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"]||$arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"]):?>
            <li class="video<?=(!($iTab++) ? ' cur' : '')?>">
                <?if (!empty($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"])):?>
                    <?=$arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["~VALUE"];?>
                    <div class="description">
                        <img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
                        <p><?$APPLICATION->IncludeFile(SITE_DIR."include/video_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_VIDEO_DESCRIPTION')));?></p>
                    </div>
                    <?elseif (!empty($arResult["SECTION_FULL"]['UF_VIDEO_YOUTUBE'])):?>
                    <?=$arResult["SECTION_FULL"]['~UF_VIDEO_YOUTUBE'];?>
                    <div class="description">
                        <img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
                        <p><?$APPLICATION->IncludeFile(SITE_DIR."include/video_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_VIDEO_DESCRIPTION')));?></p>
                    </div>
                    <?endif;?>
            </li>
            <?endif;?>
        <?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
            <li class="<?=(!($iTab++) ? ' cur' : '')?>">
                <?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?>
            </li>
            <?endif;?>
    </ul>
</div>

<script type="text/javascript">
    $(".open_stores .availability-row .value").click(function(){
        if($(".stores_tab").length){
            $(".stores_tab").addClass("cur").siblings().removeClass("cur");
        }
        else{
            $(".prices_tab").addClass("cur").siblings().removeClass("cur");
            if($(".prices_tab .property.opener").length && !$(".prices_tab .property.opener .opened").length){
                var item = $(".prices_tab .property.opener").first();
                item.find(".opener_icon").addClass("opened");
                item.parents("tr").next(".offer_stores").find(".stores_block_wrap").slideDown(200);
            }
        }
    });

    $(".opener").click(function(){
        $(this).find(".opener_icon").toggleClass("opened");
        var showBlock = $(this).parents("tr").toggleClass("nb").next(".offer_stores").find(".stores_block_wrap");
        showBlock.slideToggle(200);
    });

    $(".tabs_section .tabs li").live("click", function(){
        if(!$(this).is(".cur")){
            $(".tabs_section .tabs li").removeClass("cur");
            $(this).addClass("cur");
            $(".tabs_section ul.tabs_content li").removeClass("cur");
            if($(this).attr("id") == "product_reviews_tab"){
                $(".shadow.common").hide(); $("#reviews_content").show();
            }
            else{
                $(".shadow.common").show();
                $("#reviews_content").hide();
                $(".tabs_section ul.tabs_content > li:eq("+$(this).index()+")").addClass("cur");
            }
        }
    });

    $(".hint .icon").click(function(e){
        var tooltipWrapp = $(this).parents(".hint");
        tooltipWrapp.click(function(e){e.stopPropagation();})
        if(tooltipWrapp.is(".active")){
            tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200);
        }
        else{
            tooltipWrapp.addClass("active").find(".tooltip").slideDown(200);
            tooltipWrapp.find(".tooltip_close").click(function(e){
                e.stopPropagation(); tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
            });
            $(document).click(function(){
                tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
            });
        }
    });
</script>

<?if(!empty($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"]) || (!empty($arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"]))):?>
    <div class="specials_tabs_section specials_slider_wrapp">
        <img class="shadow " src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
        <ul class="tabs">
            <?$show_tabs = false;?>
            <?if(!empty($arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"])):?>
                <li<?if (!$show_tabs): $show_tabs=true;?> class="cur"<?endif;?>><span><?=GetMessage("EXPANDABLES_TITLE")?></span><i class="triangle"></i></li>
                <?endif;?>
            <?if(!empty($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"])):?>
                <li<?if (!$show_tabs): $show_tabs=true;?> class="cur"<?endif;?>><span><?=GetMessage("ASSOCIATED_TITLE")?></span><i class="triangle"></i></li>
                <?endif;?>
        </ul>
        <ul class="slider_navigation">
            <?$show_tabs = false;?>
            <li class="specials_slider_navigation expandables_nav<?if (!$show_tabs): $show_tabs=true;?> cur<?endif;?>"></li>
            <li class="specials_slider_navigation associated_nav<?if (!$show_tabs): $show_tabs=true;?> cur<?endif;?>"></li>
            <?/*if(!empty($arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"])):?>
                <?if(count($arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"]) > 4):?>
                <li class="specials_slider_navigation expandables_nav<?if (!$show_tabs): $show_tabs=true;?> cur<?endif;?>"></li>
                <?endif;?>
                <?endif;?>
                <?if(!empty($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"])):?>
                <?if(count($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"]) > 4):?>
                <li class="specials_slider_navigation associated_nav<?if (!$show_tabs): $show_tabs=true;?> cur<?endif;?>"></li>
                <?endif;?>
            <?endif;*/?>
        </ul>
        <ul class="tabs_content">
            <?$show_tabs = false;?>
            <?if(!empty($arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"])):?>
                <li class="tab<?if(!$show_tabs):$show_tabs=true;?> cur<?endif;?>">
                    <?$GLOBALS['arrFilterExpandables'] = array( "ID" => $arResult["PROPERTIES"]["EXPANDABLES"]["VALUE"] );?>
                    <?$APPLICATION->IncludeComponent("bitrix:catalog.top", "products_slider", array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "ELEMENT_SORT_FIELD" => "SORT",
                            "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            "ELEMENT_COUNT" => "20",
                            "LINE_ELEMENT_COUNT" => "4",
                            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                            "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                            "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                            "SECTION_URL" => $arParams["SECTION_URL"],
                            "DETAIL_URL" => $arParams["DETAIL_URL"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                            "USE_PRODUCT_QUANTITY" =>$arParams["USE_PRODUCT_QUANTITY"],
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                            "FILTER_NAME" => "arrFilterExpandables",
                            "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                            ),
                            false
                        );?>
                </li>
                <?endif;?>
            <?if(!empty($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"])):?>
                <li class="tab<?if(!$show_tabs):$show_tabs=true;?> cur<?endif;?>">
                    <?$GLOBALS['arrFilterAssociated'] = array( "ID" => $arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"] );?>
                    <?$APPLICATION->IncludeComponent("bitrix:catalog.top", "products_slider", array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "ELEMENT_SORT_FIELD" => "SORT",
                            "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            "ELEMENT_COUNT" => "20",
                            "LINE_ELEMENT_COUNT" => "4",
                            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                            "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                            "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                            "SECTION_URL" => $arParams["SECTION_URL"],
                            "DETAIL_URL" => $arParams["DETAIL_URL"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                            "USE_PRODUCT_QUANTITY" =>$arParams["USE_PRODUCT_QUANTITY"],
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                            "FILTER_NAME" => "arrFilterAssociated",
                            "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                            ),
                            false
                        );?>
                </li>
                <?endif;?>
        </ul>
        <script type="text/javascript">
            var flexsliderItemWidth = 184;
            var flexsliderItemMargin = 14;

            $(".specials_slider_wrapp:first-child").first().flexslider({
                animation: "slide",
                selector: ".specials_slider > li",
                slideshow: false,
                animationSpeed: 600,
                directionNav: true,
                controlNav: false,
                pauseOnHover: true,
                itemWidth: flexsliderItemWidth,
                itemMargin: flexsliderItemMargin,
                animationLoop: false,
                controlsContainer: ".specials_slider_navigation.cur",
                start: function(slider){
                    if(slider.count<5){
                        $('.slider_navigation li.cur').find('.flex-direction-nav').addClass('disabled');
                    }
                    $('.specials_tabs_section .tabs_content').height($('.specials_tabs_section .tabs_content li.active').height());
                }
            });

            if($(".specials_slider_navigation.cur .flex-direction-nav .flex-disabled").length > 1){
                $(".specials_slider_navigation.cur").remove();
            }

            if($(".thumbs_navigation .flex-direction-nav .flex-disabled").length > 1){
                $(".thumbs_navigation").remove();
            }

            $(".specials_tabs_section .tabs > li").live("click", function(){
                if(!$(this).is(".cur")){
                    $(".specials_tabs_section .tabs > li").removeClass("cur");
                    $(this).addClass("cur");
                    $(".specials_tabs_section .tabs_content li.tab").removeClass("active");
                    $(".specials_tabs_section .tabs_content li.tab:eq("+$(this).index()+")").addClass("active");
                    setTimeout(function(){$(".specials_tabs_section .tabs_content li.tab:not(.active)").fadeOut(333);}, 200)
                    $(".specials_tabs_section .tabs_content li.tab:eq("+$(this).index()+")").fadeIn(333);
                    $(".slider_navigation > li").removeClass("cur");
                    $(".slider_navigation > li:eq("+$(this).index()+")").addClass("cur").show();
                    $('.specials_tabs_section .tabs_content').height($('.specials_tabs_section .tabs_content li.active').height());
                }
                if(!$(".tab.active .flex-viewport").length){
                    $(".tab.active").flexslider({
                        animation: "slide",
                        selector: ".specials_slider > li",
                        slideshow: false,
                        animationSpeed: 600,
                        directionNav: true,
                        controlNav: false,
                        pauseOnHover: true,
                        itemWidth: flexsliderItemWidth,
                        itemMargin: flexsliderItemMargin,
                        animationLoop: false,
                        controlsContainer: ".specials_slider_navigation.cur",
                        start: function(slider){
                            if(slider.count<5){
                                $('.slider_navigation li.cur').find('.flex-direction-nav').addClass('disabled');
                            }
                        }
                    });
                    $('.specials_slider_wrapp .tab.active').equalize({children: '.item-title'});
                    $('.specials_slider_wrapp .tab.active').equalize({children: '.item_info'});
                    $('.specials_slider_wrapp .tab.active').equalize({children: '.catalog_item'});
                    if($(".specials_slider_navigation.cur .flex-direction-nav .flex-disabled").length > 1){
                        $(".specials_slider_navigation.cur").hide();
                    }
                }
            });

            $(".specials_slider_wrapp").ready(function(){
                $('.specials_slider_wrapp .tab.cur').equalize({children: '.item-title'});
                $('.specials_slider_wrapp .tab.cur').equalize({children: '.item_info'});
                $('.specials_slider_wrapp .tab.cur').equalize({children: '.catalog_item'});
                //$('.specials_tabs_section .tabs_content').equalize({children: 'li.tab'});
                $('.specials_tabs_section .tabs_content .tab.cur').equalize({children: 'li.catalog_item'});
                //$('.specials_tabs_section .tabs_content').height($('.specials_tabs_section .tabs_content li:first-child').outerHeight());
            });

            if($(window).outerWidth() > 600 && $(window).outerWidth() < 768 && $(".catalog_detail .buy_buttons_wrapp a").length > 1){
                var adapt = false;
                var prev;
                $(".catalog_detail .buy_buttons_wrapp a").each(function(i, element){
                    prev = $(".catalog_detail .buy_buttons_wrapp a:eq("+(i-1)+")");
                    if($(this).offset().top!=$(prev).offset().top && i>0){
                        $(".catalog_detail .buttons_block").addClass("adaptive");
                    }
                });
            }
            else{
                $(".catalog_detail .buttons_block").removeClass("adaptive");
            }
        </script>
    </div>
    <?endif;?>