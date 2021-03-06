<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" type="text/css" href="/bitrix/js/socialservices/css/ss.css">
<?if( $arResult["FORM_TYPE"] == "login" ){?>
	<?if( !empty( $arResult["ERROR_MESSAGE"] ) ):?><div class="form_description"><?=$arResult["ERROR_MESSAGE"]["MESSAGE"]?></div><?endif;?>
	<?CAjax::Init();?>
	
	<div class="form-wr">
		<form id="avtorization-form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=SITE_DIR?>auth/" onsubmit="return jsAjaxUtil.InsertFormDataToNode(this, 'ajax_auth', true);">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
			<?foreach ($arResult["POST"] as $key => $value):?><input type="hidden" name="<?=$key?>" value="<?=$value?>" /><?endforeach?>
					<input type="hidden" name="AUTH_FORM" value="Y" />
					<input type="hidden" name="TYPE" value="AUTH" />
			<div class="r">
				<label class="" for="avtorization-login"><?=GetMessage("AUTH_LOGIN")?><span class="star">*</span></label>
				<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" placeholder="<?=GetMessage("AUTH_LOGIN")?>" autocomplete="on" tabindex="1"/>
			</div>
			<div class="r">
				<label  class="" for="avtorization-password"><?=GetMessage("AUTH_PASSWORD")?><span class="star">*</span></label>	
				<input type="password" name="USER_PASSWORD" maxlength="50" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" tabindex="2"/>
				<a class="forgot" href="<?=SITE_DIR?>auth/forgot-password/" tabindex="3"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
			</div>
			<?if ($arResult["CAPTCHA_CODE"]):?>
				<div class="captcha_code">
					<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
					<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
					<input type="text" name="captcha_word" maxlength="50" value="" />
				</div>
			<?endif?>
			<div class="but-r cleaarboth">
				<button type="submit" class="button30" name="Login" value="" tabindex="4">
					<span><?=GetMessage("AUTH_LOGIN_BUTTON")?></span>
				</button>
				<div class="prompt remember">
					<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" tabindex="5"/>
					<label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>" tabindex="5"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
				</div>
			</div>
		</form>
	</div>
	<?if($arResult["AUTH_SERVICES"]):?>
		<div class="soc-avt" style="display:none;">
			<?=GetMessage("SOCSERV_AS_USER_FORM");?>
			<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
				array(
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"AUTH_URL" => SITE_DIR."auth/?login=yes",
					"POST" => $arResult["POST"],
					"SUFFIX" => "form",
				), 
				$component, array("HIDE_ICONS"=>"Y")
			);
			?>
		</div>
	<?endif;?> 
	<script>
		$(document).ready(function()
		{ 
			$("#avtorization-form-page").validate({ rules:{ USER_LOGIN: { email:true, required:true } } }); 
			$("form[name=bx_auth_servicesform]").validate(); 
		})
	</script>
	
	<div class="reg-new">
		<!--noindex--><a href="<?=SITE_DIR?>auth/registration/" rel="nofollow" tabindex="6"><?=GetMessage("AUTH_REGISTER_NEW")?></a><!--/noindex-->
	</div>
<?}else{?>
	<script>
			BX.reload(true); //http://shinof.dev.aspro.ru
	</script>
<?}?>