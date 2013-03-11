<!DOCTYPE html>
<html lang='it'>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	
	<title>Offerte</title>
</head>
<body>
<div class="content">
	<!-- Including the css -->
	<div style="display:none;visibility:hidden;">
		<?if(isset($css) AND $css):?>
			<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8"/>
			<style type="text/css" media="screen">
			body,
			#offer-tile-list {
				background-color:#<?=$custom_style['bg_color']?> !important;
			}
			.offer {
				color:#<?=$custom_style['text_color']?> !important;
			}
			.offer .offer_title {
				color:#<?=$custom_style['title_color']?> !important;
			}
			</style>
		<?endif;?>
	</div>
	
	<?if($error):?>
		<span class="offers err"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="offers message"><?=$message?></span>
	<?endif;?>
	
	<?if($offers):?>
		<div id="offer-title-list">
			<?foreach($offers as $offer):?>
					<div class="offer clearfix<? ;if($offer->offer_special) echo " special";?>">
						<h3 class="offer_title">
							<?php if($offer->offer_special):?>
								<?php if ($special_img): ?>
									<img src="<?=base_url(PATH_WEB_UPLOAD.$special_img)?>" class="special-img">
								<?php else: ?>
									<img src="<?=base_url("/public/img/icon-star-special.png")?>" class="special-img">
								<?php endif ?>
							<? endif; ?>
							<?=$offer->offer_title?>
						</h3>
					</div>
			<?endforeach;?>
		</div>
	<?else:?>
		<p>Al momento non ci sono offerte disponibili.</p>
	<?endif;?>
	
</div>

<? if(DEBUG): ?>
	<div class="debug">
		<br/><hr/>
		<h1>DEBUG!</h1>
		<?foreach($this->load->get_cached_vars() as $var => $value):?>
			<b>$<?=$var?></b><br/>
			<pre><?print_r($value)?></pre>
			<hr/></br/>
		<?endforeach;?>
	</div>
<? endif; ?>

</body>
</html>