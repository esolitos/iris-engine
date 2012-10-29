<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>test</title>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
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