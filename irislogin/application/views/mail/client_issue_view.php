<html lang="it">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Contatto da IRISLogin</title>
	<meta name="author" content="Esolitos Marlon">
	<link rel="stylesheet" href="<?=base_url()?>/public/bootstrap/css/bootstrap.min.css" type="text/css" media="all" charset="utf-8">
</head>
<body>
	<h1>Contatto da irislogin.it</h1>

	<p>Il cliente ha indicato i seguenti dettagli al momento del contatto:</p>

	<form class="form-horizontal">
		<fieldset>
			<div class="control-group">
				<label class="control-label">Username</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['username']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Nome e Cognome</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['name']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Azienda</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['company']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">eMail di contatto</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['email']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Telefono di contatto</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['tel']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Tipologia Problema</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="<?=$data['contact_reason']?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Pagina Sorgente</label>
				<div class="controls">
					<input type="text" class="input-xlarge uneditable-input" value="http://irislogin.it/<?=$data['from']?>.html">
					<p class="help-block">Questo campo &egrave; salvato dal sistema ed indica da quale pagina &egrave; partita la richiesta del cliente.</p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Messaggio</label>
				<div class="controls">
					<p><?= set_value('message')?></p>
				</div>
			</div>
		</fieldset>
	</form>
</body>
</html>