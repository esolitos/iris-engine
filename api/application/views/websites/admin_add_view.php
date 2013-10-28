<?
	$website_name = ($website_name) ? $website_name : set_value('website_name');
	$website_url = ($website_url) ? $website_url : set_value('website_url');
	$website_email = ($website_email) ? $website_email : set_value('website_email');
?>

<div class="content">
	<h1>Aggiunta/Modifica di un sito al sistema</h1>
	<p>Inserisci i dati necessari per la creazione/modifica del sito.</p>
	
	<?if($error):?>
		<div class="alert alert-error"><?=$error?></div>
	<?endif;?>
	<?if($message):?>
		<div class="alert alert-info"><?=$message?></div>
	<?endif;?>

	<div class="clearfix">
		<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>
	</div>
	
	<?=form_open(uri_string(), array('class' => 'well form-inline'))?>
		<input type="text" name="website_name" value="<?=$website_name?>" placeholder="Nome del Sito"/>
		<input type="text" name="website_email" value="<?=$website_email?>" placeholder="eMail dell'azienda"/>
		<input type="text" name="website_url" value="<?=$website_url?>" placeholder="URL del Sito" />
		<input type="submit" name="submit" value="Continua &rarr;"/>
	</form>
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