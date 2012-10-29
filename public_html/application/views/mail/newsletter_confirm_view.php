<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Newsletter Confirmation</title>
	
</head>
<body id="newsletter_confirm_view" onload="">
	<h1>Hi <?=$data['name'].' '.$data['surname']?>!</h1>

<p>
	You have correctly subscribed to the newsletter of the website <?=$website['info']->website_name?>.<br/>
	<span>If it wasn't you <?=anchor("newsletter/unsubscribe/{$data['email']}", 'click here to unsubscribe')?>, and please <?=mailto(IRIS_MAIL, 'report the fact')?> to the administrator!</span>
</p>


<strong>Request sended on</strong>: <?=date('r')?><br/>
</body>
</html>