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
		<link rel="stylesheet" href="<?=base_url("/public/css/defaults/services-style.min.css")?>" type="text/css" media="screen" charset="utf-8">
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
					<div class="offer clearfix well<? ;if($offer->offer_special) echo " special";?>">
						<strong class="offer_title">
							<?php if($offer->offer_special):?>
								<?php if ($special_img): ?>
									<img src="<?=base_url(PATH_WEB_UPLOAD.$special_img)?>" class="special-img">
								<?php else: ?>
									<img src="<?=base_url("/public/img/icon-star-special.png")?>" class="special-img">
								<?php endif ?>
							<? endif; ?>
							<?
								if(strlen($offer->offer_title) > OFFER_TITLE_MAX_LENGTH)
									echo substr($offer->offer_title, 0, OFFER_TITLE_MAX_LENGTH)."&hellip;";
								else
									echo $offer->offer_title;
							?>
						</strong>
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