<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("�����������");

	if(!$USER->IsAuthorized())
	{?>
	<?$APPLICATION->IncludeFile(SITE_DIR."include/register_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("REGISTER_INCLUDE_AREA"), ));?>
	<?
		$APPLICATION->IncludeComponent(
			"bitrix:main.register",
			"", //there was main
			Array(
				"USER_PROPERTY_NAME" => "",
				"SHOW_FIELDS" => array("NAME","EMAIL","PERSONAL_PHONE"),
				"REQUIRED_FIELDS" => array("NAME","EMAIL", "PERSONAL_PHONE"),
				"AUTH" => "Y",
				"USE_BACKURL" => "Y",
				"SUCCESS_PAGE" => "",
				"SET_TITLE" => "N",
				"USER_PROPERTY" => array()
			)
		);
		$_REQUEST["REGISTER[LOGIN]"] = $_REQUEST["REGISTER[EMAIL]"];
	} elseif(!empty( $_REQUEST["backurl"] )) {LocalRedirect( $_REQUEST["backurl"] );} 
    else { LocalRedirect(SITE_DIR.'personal/');}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>