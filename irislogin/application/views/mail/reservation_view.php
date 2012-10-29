<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="http://iris-engine.dev.eso/public/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="http://iris-engine.dev.eso/public/bootstrap/css/bootstrap-eso.css" type="text/css" media="screen" charset="utf-8">
	<title>Richiesta di preonotazione</title>
	
</head>
<body id="reservation_view">
	<h1>Richiesta di prenotazione ricevuta attraverso IrisLogin.</h1>

<p>
	<h2>Informazioni di Contatto</h2>
	<strong>Nome</strong>: <?=$data['name']?><br/>
	<strong>Cognome</strong>: <?=$data['surname']?><br/>
	<strong>eMail</strong>: <?=$data['email']?><br/>
	<strong>Tel</strong>: <?=$data['tel']?><br/>

	<h2>Prenotazione Richiesta</h2>
	<strong>Dal giorno</strong> <span style="color:green;"><?=$data['from_date']?> al giorno <?=$data['to_date']?>.</span><br/>
	<strong>Per adulti: </strong>: <span style="color:green;"><?=$data['adults']?></span><br/>
	<?php if ($data['babies']): ?>
		<strong>..e bambini: </strong>: <span style="color:green;"><?=$data['babies']?></span><br/>
	<?php endif ?>

<?php if (strlen($data['notes'])): ?>
<br/>
	<h2>Note del Cliente</h2>
	<blockquote><?=$data['notes']?></blockquote>
<?php endif ?>
</p>
<p>
	<strong>Richiesta inviata il </strong>: <?=date('r')?><br/>
	<strong>Sottoscrizione alla newsletter</strong>: <?=$data['newsletter']?>
</p>

</body>
</html>