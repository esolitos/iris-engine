<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nuova Offerta</title>
<style type="text/css">
body {
	background-color: #F8F8F8;
	position: inherit;
	left: 30px;
	top: 0px;
	right: 30px;
	bottom: 10px;
	padding: 2px;
}
#header {
	padding: 5px;
	min-height: 50;
	font-family: Arial, Helvetica, sans-serif;
	text-align: center;
	color: #06F;
	vertical-align: top;
}
#msg {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-style: normal;
	color: #434343;
	padding: 30px;
	text-align: center;
	height: 340px;
	background-image: url(<?=base_url("/public/img/email-bg.png")?>);
	background-repeat: no-repeat;
	background-position-y: 0;
	background-position: center;
}
#msg p {
	margin-bottom:50px;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #2E2E2E;
}
#footer {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #6D6D6D;
	text-align: center;
}
#signature {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #585858;
	text-align: center;
	padding: 10px;
}
</style>
</head>

<body>
<div class="body" id="body">
  <div class="header" id="header">
	<?php if (isset($website->website_logo) AND $website->website_logo): ?>
		    <div class="logo-or-website"><img src="/public/upload/<?=$website->website_logo?>"></div>
	<?php else: ?>
		    <div class="logo-or-website"><h1><?=$website->website_name?></h1></div>		
	<?php endif ?>
  </div>
  <div class="msg" id="msg">
    <p><strong><?=$website->website_name?></strong><br />
		ha inserito una nuova offerta.<br />
    <a href="<?=$offer_url?>">Clicca qui per visualizzarla</a>!</p>

    <p><strong><?=$website->website_name?></strong><br />
      has entered a new offer.<br />
    <a href="<?=$offer_url?>">Click here to view</a>!</p>

    <p><strong><?=$website->website_name?></strong><br />
      eingegeben hat ein neues Gebot.<br />
    <a href="<?=$offer_url?>">Klicken Sie hier, um zu sehen</a>!</p>
  </div>
  <div class="footer" id="footer">
    <address><a href="mailto:<?=$website->website_email?>"><?=$website->website_email?></a></address>
  </div>
  <br>
  <div class="signature" id="signature"><a href="<?=base_url()?>"><img src="<?=base_url("/public/img/logo-powered-by-irislogin.png")?>" width="235" height="60" alt="Powered by IRIS Login" /></a></div>
</div>
</body>
</html>
