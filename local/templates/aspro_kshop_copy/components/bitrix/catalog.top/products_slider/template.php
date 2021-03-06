<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$sliderID  = "specials_slider_wrapp_".rand();
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
?>
<?if($arResult["ITEMS"]):?>
	<div class="specials_slider_wrapp" id="<?=$sliderID?>">
		<span class="slider_navigation"></span>
		<ul class="specials_slider product_slider">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				$totalCount = CKShop::GetTotalCount($arItem);
				$arQuantityData = CKShop::GetQuantityArray($totalCount);
				$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"]);
				?>
				<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="catalog_item">
					<div class="ribbons">
						<?if(is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
							<?if(in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;?>
							<?if(in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
							<?if(in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
							<?if(in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
						<?endif;?>
					</div>
					<div class="image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
							<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
								<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
								<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
								<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?else:?>
								<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?endif;?>
						</a>
					</div>
					<div class="item_info">
						<div class="item-title">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
						</div>
						<div class="cost clearfix">
							<?
							$frame = $this->createFrame()->begin('');
							$frame->setBrowserStorage(true);
							?>
							<?if($arItem["OFFERS"]):?> 
								<div class="price_block">
									<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
								</div>
							<?elseif($arItem["PRICES"]):?>
								<?
								$arCountPricesCanAccess = 0;
								foreach($arItem["PRICES"] as $key => $arPrice){
									if($arPrice["CAN_ACCESS"]){
										++$arCountPricesCanAccess;
									}
								}
								?>
								<?foreach($arItem["PRICES"] as $key => $arPrice):?>
									<?if($arPrice["CAN_ACCESS"]):?>
										<?$price = CPrice::GetByID($arPrice["ID"]); ?>
										<?if($arCountPricesCanAccess > 1):?>
											<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
										<?endif;?>
										<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
											<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
											<div class="price discount">
												<strike><?=$arPrice["VALUE"];?></strike>
											</div>
										<?else:?>
											<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>				
							<?else:?>
								<span class="by_order"><?=GetMessage("BY_ORDER");?></span>
							<?endif;?>
							<?$frame->end();?>
						</div>
						<div class="buttons_block clearfix">
							<!--noindex-->
							<?=$arAddToBasketData["HTML"]?>
							<?if($arItem["CAN_BUY"] && ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y")):?>
								<div class="like_icons">								
									<?if(empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
										<a title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"><i></i></a>
									<?endif;?>
									<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
										<a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a>
									<?endif;?>
								</div>
							<?endif;?>
							<!--/noindex-->
						</div>
					</div>
				</li>
			<?endforeach;?>
		</ul>
		<?if($arParams["INIT_SLIDER"] == "Y"):?>
			<script type="text/javascript">
			$(document).ready(function(){
				var flexsliderItemWidth = 178;
				var flexsliderItemMargin = 15;
				var sliderWidth = $('#<?=$sliderID?>').outerWidth();
				var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
				
				console.log(flexsliderMinItems);
			
				$('#<?=$sliderID?>').flexslider({
					animation: 'slide',
					selector: '.specials_slider > li',
					slideshow: false,
					animationSpeed: 600,
					directionNav: true,
					controlNav: false,
					pauseOnHover: true,
					animationLoop: false, 
					itemWidth: flexsliderItemWidth,
					itemMargin: flexsliderItemMargin, 
					minItems: flexsliderMinItems,
					controlsContainer: '#<?=$sliderID?> .slider_navigation',
				});
				
				$(window).resize(function(){
					var sliderWidth = $('.specials_slider_wrapp').outerWidth();
					if($('#<?=$sliderID?>').length && typeof($('#<?=$sliderID?>').data('flexslider')) !== 'undefined'){
						$('#<?=$sliderID?>').data('flexslider').vars.minItems = flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
						$('#<?=$sliderID?>').flexslider(0);
					}
					if($('#<?=$sliderID?>').find('.product_slider > li').length > flexsliderMinItems){
						$('#<?=$sliderID?> .slider_navigation').show();
						$('#<?=$sliderID?> .slider_navigation > ul').show();
					}
					else{
						$('#<?=$sliderID?> .slider_navigation').hide();
						$('#<?=$sliderID?> .slider_navigation > ul').hide();
					}
					console.log(flexsliderMinItems);
				});
			});
			</script>
			<?$frame = $this->createFrame()->begin('');?>
			<script type="text/javascript">
			$("#<?=$sliderID?>").ready(function(){
				$(window).resize();
				$('#<?=$sliderID?> .product_slider').equalize({children: '.item-title'}); 
				$('#<?=$sliderID?> .product_slider').equalize({children: '.item_info'}); 
				$('#<?=$sliderID?> .product_slider').equalize({children: 'li.catalog_item'});
				$('#<?=$sliderID?> .product_slider').height($('#<?=$sliderID?> .product_slider li:first-child').outerHeight());
				if($('#<?=$sliderID?> .slider_navigation .flex-disabled').length > 1){
					$('#<?=$sliderID?> .slider_navigation').hide();
				}
			});
			</script>
			<?$frame->end();?>
		<?endif;?>
	</div>
<?else:?>
	<?$this->setFrameMode(true);?>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".news_detail_wrapp .similar_products_wrapp").remove();
	}); /* dirty hack, remove this code */
	</script>
<?endif;?>