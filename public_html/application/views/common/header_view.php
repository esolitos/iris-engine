<!DOCTYPE html>
<html lang='it'>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">

	<?  foreach($head as $heading): ?>
		<?=$heading?>
	<? endforeach; ?>
	
	<title><?=$page_title?></title>
	
	<script type="text/javascript" src="<?=base_url("public/js/jquery.js")?>"></script>
	<script src="<?=base_url("public/bootstrap/js/bootstrap.js")?>" type="text/javascript" charset="utf-8"></script>
<!-- jQuery -->
	<script src="<?=base_url("public/js/jquery-ui.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.dialog2.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.controls.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.form.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.dialog2.helpers.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/jquery.preloadCssImages.js")?>" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
		  $.preloadCssImages();
		});
	</script>
<!-- CKEditor -->
	<script src="<?=base_url("public/js/ckeditor/ckeditor.js")?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?=base_url("public/js/ckeditor/adapters/jquery.js")?>" type="text/javascript" charset="utf-8"></script>
	
	
	<link rel="stylesheet" href="<?=base_url("public/bootstrap/css/bootstrap.min.css")?>" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="<?=base_url("public/bootstrap/css/bootstrap-eso.css")?>" type="text/css" media="screen" charset="utf-8">
	<link rel="shortcut icon" href="/public/img/favicon.ico" type="image/x-icon" />

	<?php if (DEBUG OR TESTING): ?>
		<link rel="stylesheet" href="<?=base_url()?>public/css/testing.css" type="text/css" media="all" charset="utf-8"/>
	<?php endif; ?>
	
	<script src="<?=base_url("/public/js/admin.js")?>" type="text/javascript" charset="utf-8"></script>
	
</head>
<body>
	<div id="wrapper">
		<div id="header" class="clearfix">
			<div class="navbar navbar-fixed-top">
			  <div class="navbar-inner">
			    <div class="container">
					<a class="brand" href="<?=base_url()?>">
						<img src="<?=base_url("/public/img/logo_small.png")?>">
						<!-- IRIS Login -->
					</a>
					<ul class="top-menu nav clearfix <? if(!$user['logged_in']) echo "not-logged"; ?>">
						<?php if ($user['logged_in']): ?>
							<li class="first"><?=anchor('admin', "Admin Home")?></li>
							<li class="divider-vertical"></li>

							<?php foreach ($website['services'] as $service): ?>
								<?if($service->expired):?>
									<li class="disabled"><a href="#"><?=$service->service_name?></a></li>
								<?php else: ?>
									<li><?=anchor($service->service_url, $service->service_name)?></li>
								<?php endif; ?>
							<?php endforeach ?>
						<?php endif ?>

						<?if($user['logged_in'] && $user['user_id'] == USER_ID_MASTER_ADMIN):?>
							<li><?=anchor('admin/websites', "Websites")?></li>
						<?endif;?>

						<?if($user['logged_in']):?>
							<li class="divider-vertical"></li>
							<li class="last dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<?=$user['username']?>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><?=anchor('user/password/update', "Cambia Password")?></li>
									<li><?=anchor('logout', "Logout")?></li>
								</ul>
							</li>
						<?else:?>
							<li class="active"><?=anchor('admin/login', "Log In")?></li>
						<?endif;?>

					</ul>
			    </div>
			  </div>
			</div>
			
			<div id="title-and-logo">
				<img src="<?=base_url()?>/public/img/logo.png">
			</div>
			
		</div><!-- /#header -->
