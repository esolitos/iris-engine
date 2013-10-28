<!DOCTYPE html>
<html>
<head>
	<title>Invia Newsletter di Prova</title>
</head>
<body>
	<h1>Invia Newsletter di Test</h1>

	<?if(isset($error)):?>
		<div class="alert alert-error">
			<span class="close" data-dismiss="alert">×</span>
			<h4 class="alert-heading">Attenzione!</h4>
			<?=$error?>
		</div>
	<?endif;?>
	<?if(isset($message)):?>
		<div class="alert alert-info">
			<span class="close" data-dismiss="alert">×</span>
			<h4 class="alert-heading">Informazione:</h4>
			<?=$message?>
		</div>
	<?endif;?>

	<p>Indica fino a <?=NEWSLETTER_MAX_TEST_MAILS?> indirizzi eMail separati da una virgola a cui inviare la newsleter di prova.</p>

		<?=form_open("/admin/newsletter/{$news_id}/test", array('class' => "ajax inline-form"));?>
		<fieldset>
			<input type="text" name="addresses" id="addresses" placeholder="Indirizzo/i eMail" class="span6"/><br>
			<span class="help-inline">
				(Esempio Corretto: "mario.rossi@email.com,biondi@mori.com";<br>
				Esempio Errato: "mario.rossi@email.com biondi@mori.com, alessio@gmail.it")
			</span>
		</fieldset>
			
		<div class="form-actions">
			<input type="submit" name="submit" value="Invia Test" class="btn btn-primary" />
			<a class="btn close-dialog" href="#">Chiudi</a>
		</div>
	</form>
</body>
</html>