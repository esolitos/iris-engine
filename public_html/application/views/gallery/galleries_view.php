<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Gallerie Fotografiche | <?=$website->website_name?></title>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<base target="_parent" />
	<script src="<?=base_url("public/js/jquery.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/keydown.js")?>" type="text/javascript" charset="utf-8"></script>

	
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		
		$(".modal-ajax-trigger").live( 'click', function(event) {
			event.preventDefault();
			element = $(this);

			var href = element.attr("href");
			var gallery_id = element.attr("data-gallery-id");
				
			var message = {
			    url: href,
				gid: gallery_id, 
				id: "modal-ajax-landing-data",
			};
			
			window.parent.postMessage(JSON.stringify(message),'*');
	    });
	});
	</script>
	
	<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8"/>
	
	<style type="text/css" media="screen">
		.modal {
			top: 10%;
			width: 800px;
			margin: 0 0 0 -400px; /* -1 * (width / 2) */
		}
		.modal > .modal-body {
			max-height:100%;
			height:100%;
		}
		.modal > .modal-header,
		.modal > .modal-footer {
			display:none;
		}
	</style>
</head>
<body>
	<div class="container-fluid"><br>
		<div id="messages">
			<?php if (isset($error)): ?>
				<?=$error?>
			<?php endif ?>
		</div>
		<?php if (count($site_galleries) <= 0): ?>
			<p>Nessuna galleria disponibile per <?=$website->website_name?>.</p>
		<?php else: ?>
			<div id="galleries">
				<?php foreach ($site_galleries as $name => $gallery): ?>
					<div class="well">
						<h2><?=$name?></h2>
						<?php if (count($gallery)): ?>
							<?php foreach ($gallery as $index => $image): ?>
								<a href="<?=base_url("gallery/single/$image->gallery_id/$index")?>" data-gallery-id="<?=$image->gallery_id?>" class="modal-ajax-trigger">
									<img src="<?=base_url($image->thumb)?>"/>
								</a>
							<?php endforeach ?>
						<?php else: ?>
							<p>Nessuna immagine ancora caricata in questa galleria.</p>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
		<?php endif // $galleries > 0 ?>
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