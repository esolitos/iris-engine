<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>test</title>
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
	
	<link rel="stylesheet" href="<?=base_url("public/bootstrap/css/bootstrap.min.css")?>" type="text/css" media="screen" charset="utf-8">

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
	<script type="text/javascript" charset="utf-8">
		$().ready(function(){
			
			var pause_ms = 2500;
			
			Galleria.configure({
				autoplay: pause_ms,
				idleMode: false,
				lightbox: false,
				thumbQuality: false,
				debug: false,

				<?php if(isset($start_index)): ?>
				show:<?=$start_index?>,
				<?php endif; ?>
				// dummy: '<?=base_url("public/img/gallery-noimage.jpg")?>,'
				
				extend: function(options) {
					this.bind('mouseover', function(e){
						this.pause();
					});
					this.bind('mouseout', function(e){
						this.play(pause_ms);
					});
				}
			});

			Galleria.run('#galleria');
		
			// $("#galleria").live('mousehover', function(){
			// 	$("#galleria").data('galleria').pause();
			// });
			// $("#galleria").mouseout(function(){
			// 	this.data('galleria').play(500);
			// });
		});
	</script>
	<style>
		body { margin:0; padding:0; background: #000;}
	    #galleria{ width: 100%; height: 500px; margin: 0 auto; }
		.galleria-thumbnails { margin:0 auto;}
	</style>
	
	<div id="galleria">
		<?php foreach ($gallery_img as $image): ?>
			<a href="<?=base_url($image->full_size)?>">
				<img
					<? if($image->title) echo 'data-title="'.$image->title.'"' ?>
					<? if($image->descr) echo 'data-description="'.$image->description.'"' ?>
					src="<?=base_url($image->thumb)?>"
				/>
			</a>
		<?php endforeach ?>
</body>
</html>