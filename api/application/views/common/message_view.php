<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Avviso</title>
	
	<!-- Including the css -->
	<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8"/>
</head>
<body>
	<div id="main-wrapper" class="page">
		<div id="content" class="container">
			<?php if ($title): ?>
				<h1 class="iris-message-title"><?=$title?></h1>
			<?php endif ?>
	
			<?php if ($error): ?>
				<div class="well"><span class="alert alert-error"><?=$error?></span></div>
			<?php endif;?>
	
			<?php if ($message): ?>
				<div class="well"><span class="alert alert-info"><?=$message?></span></div>
			<?php endif;?>
		</div>
	</div>
</body>
</html>
