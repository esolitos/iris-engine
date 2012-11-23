<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Gallerie Fotografiche | <?=$website->website_name?></title>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	

	<script src="<?=base_url("public/js/jquery.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.controls.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.form.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.dialog2.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.dialog2.helpers.js")?>" type="text/javascript" charset="utf-8"></script>

	<script src="<?=base_url("public/js/galleria/galleria-1.2.8.min.js")?>" type="text/javascript" charset="utf-8"></script>

	
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		
		Galleria.loadTheme('<?=base_url("public/js/galleria/themes/classic/galleria.classic.min.js");?>');
		
		$(".modal-ajax-trigger").live( 'click', function(event) {
			event.preventDefault();

			var href = $(this).attr("href");

	        $('<div/>').appendTo("body").dialog2({
	            content: href, 
	            id: "modal-ajax-landing-data",
	
				showCloseHandle: false,
				closeOnEscape: true,
	        	closeOnOverlayClick: true,
				removeOnClose: true,
				
				initialLoadText: "Caricamento in corso..."
	        }).dialog2("removeButton", "Cancel");
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
								<a href="<?=base_url("gallery/single/$image->gallery_id/$index")?>" class="modal-ajax-trigger">
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