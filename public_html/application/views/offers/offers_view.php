<!DOCTYPE html>
<html lang='it'>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	
	<title>Offerte</title>
</head>
<body>
<div class="content container-fluid">
	<h1>Offerte del momento</h1>
	
	<!-- Including the css -->
	<div style="display:none;visibility:hidden;">
		<?if(isset($css) AND $css):?>
			<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8"/>
			<style type="text/css" media="screen">
			body,
			#offer-list {
				background-color:#<?=$custom_style['bg_color']?> !important;
			}
			.offer {
				color:#<?=$custom_style['text_color']?> !important;
			}
			.offer .offer_title a{
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
		<div id="offer-list">
			<?foreach($offers as $o_id=>$offer):?>
				<?if(( ! $offer->expired AND $offer->offer_visible)):?>
					<!-- BEGIN Offer ID <?=$offer->id?> -->
					<div class="well offer clearfix<?if(!$offer->offer_visible) echo " hidden";if($offer->expired) echo " expired";if($offer->offer_special) echo " special";?>">
						<h2 class="offer_title">
							<?php if($offer->offer_special):?>
								<?php if ($special_img): ?>
									<img src="<?=base_url(PATH_WEB_UPLOAD.$special_img)?>" class="special-img">
								<?php else: ?>
									<img src="<?=base_url("/public/img/icon-special.png")?>" class="special-img">
								<?php endif ?>
							<? endif; ?>
							<?=anchor("offers/view/{$offer->id}", $offer->offer_title)?>
							<?if($offer->expired AND $user):?>
								<span class="title_expired">[EXPIRED]</span>
							<?endif;?>
							<?if(! $offer->offer_visible):?>
								<span class="title_hidden">[HIDDEN]</span>
							<?endif;?>
						</h2>

						<? if($is_admin):?>
							<span class="offer_author">Author: <?=$offer->author?></span><br/>
							<span class="offer_website">Website: <?=$offer->website_name?></span><br/>
						<?php endif;?>

						<span class="offer_creation"><?=$offer->offer_creation?></span>

						<p class="offer_body <? if($offer->offer_image) echo "image"; ?>">
							<?php if($offer->offer_image): ?>
								<img class="offer_image small" src="<?=base_url(PATH_WEB_UPLOAD.$offer->offer_image)?>"/>
							<?php endif;?>
							<?
							if(strlen($offer->offer_body) > 200)
								echo substr($offer->offer_body, 0, 200)."...";
								// echo preg_match('/^(.*)\W.*$/', substr($offer->offer_body, 0, 200), $matches) ? $matches[1] : substr($offer->offer_body, 0, 200);
							else
								echo $offer->offer_body;

							echo anchor("offers/view/{$offer->id}", "<br> &rarr; Maggiori informazioni");
							?>
						</p>

						<?if($offer->offer_expire):?>
							Scadenza: <span class="offer_expire"><?=$offer->offer_expire?></span>
						<?php endif;?>	
					</div>
					<!-- END Offer ID <?=$offer->id?> -->

				<?endif;?>
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