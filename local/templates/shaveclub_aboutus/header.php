<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
    $wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
    CUtil::InitJSCore();
    CJSCore::Init(array("fx"));
    $curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--    <meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">  -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>/favicon.ico" />
    <?//$APPLICATION->ShowHead();
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.(true ? ' /':'').'>'."\n";
        $APPLICATION->ShowMeta("robots", false, true);
        $APPLICATION->ShowMeta("keywords", false, true);
        $APPLICATION->ShowMeta("description", false, true);
        $APPLICATION->ShowCSS(true, true);
    ?>
    <link rel="stylesheet" type="text/css" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH."/colors.css")?>" />
    <?
        $APPLICATION->ShowHeadStrings();
        $APPLICATION->ShowHeadScripts();
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/script.js");
    ?>
    <title><?$APPLICATION->ShowTitle()?></title>

    <script src="https://code.jquery.com/jquery-1.8.0.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/responsive.css" >
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/inside.css">
    <link rel="stylesheet" href="/css/contacts.css" >
    <link rel="stylesheet" href="/css/cusel.css" >

    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

    <script src="/js/wow.js"></script>

    <script type="text/javascript" src="/js/cusel.js"></script>
    <script type="text/javascript" src="/js/jScrollPane.js"></script>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>

    <link type="text/css" href="/css/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>
    <link type="text/css" href="/css/jquery.fancybox.css" rel="stylesheet" media="all"/>

    <script src="/js/index.js"></script>
    <script type="text/javascript">
        //alert(screen.width) ;
        if (screen.width<=360) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.3, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=960){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if (screen.width<1024) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">');
        }
    </script>


</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="wrapper">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/forms.php");?>

<?include($_SERVER["DOCUMENT_ROOT"]."/include/left_menu.php");?>

<div class="main-container inside-page oform-page">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/mobile_top.php");?>

<script type="text/javascript">
    $(function()
        {
            $(".jspContainer").hover(function () {
                $(this).find(".jspVerticalBar").fadeIn(500);
                }, function () {
                    $(this).find(".jspVerticalBar").fadeOut(500);
            });
    });
</script>


<div class="aboutus">

    <div class="column">
        <h1 class="column-header">�������&nbsp��������</h1>
        <div class="column_inner">
            <div class="scroll-pane">
                <div class="scroll-pane-inner">
                    <?$APPLICATION->IncludeFile(SITE_DIR."include/about_history.php", Array(),Array("MODE"=>"html"));?>
                </div>
            </div>
        </div>
    </div>

    <div class="column column-right">
        <h1 class="column-header">����&nbsp������</h1>
        <div class="column_inner">
            <div class="scroll-pane">
                <div class="scroll-pane-inner">
                    <?$APPLICATION->IncludeFile(SITE_DIR."include/about_progress.php", Array(),Array("MODE"=>"html"));?>
                </div>
            </div>
        </div>
    </div>

    <div  class="bottom_column">
        <h1 class="column-header">����&nbsp������</h1>
        <div class="column_inner">
            <div class="scroll-pane">
                <div class="scroll-pane-inner">
                    <?$APPLICATION->IncludeFile(SITE_DIR."include/about_mission.php", Array(),Array("MODE"=>"html"));?>
                </div>
            </div>
        </div>
    </div>

</div>