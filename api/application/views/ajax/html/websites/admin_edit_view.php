<?
	$website_name = ($website_name) ? $website_name : set_value('website_name');
	$website_url = ($website_url) ? $website_url : set_value('website_url');
	$website_email = ($website_email) ? $website_email : set_value('website_email');
?>

<script type="text/javascript" charset="utf-8">
	$().ready(function() {
		$("#add_subscr_link").click(function(e){
			$("#modal-ajax-landing-data").dialog2("close");

			$("#tab li.active").removeClass("active");
			$("#subscr-add-selector").addClass("active");
		});
		
		$(".website-user-add-link").click(function(e){
			$("#modal-ajax-landing-data").dialog2("close");

			$("#tab li.active").removeClass("active");
			$("#website-user-add-selector").addClass("active");
		});
	})
</script>
<div class="content">
	<h1>Gestione Sito Web</h1>
	
	<?if($error):?>
		<div class="alert alert-error"><?=$error?></div>
	<?endif;?>
	<?if($message):?>
		<div class="alert alert-info"><?=$message?></div>
	<?endif;?>

	<div class="clearfix">
		<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>
	</div>
	
	<?=form_open_multipart(uri_string(), array('class' => 'form-horizontal'))?>
		<fieldset>
			<div class="control-group <?php if(form_error('website_name')) echo "error";?>">
				<label class="control-label" for="website_name">Nome del Sito:</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="website_name" value="<?=$website_name?>" placeholder="Nome Visualizzato" >
				</div>
			</div>	

			<div class="control-group <?php if(form_error('website_email')) echo "error";?>">
				<label class="control-label" for="website_email">eMail dell'Azienda:</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="website_email" value="<?=$website_email?>" placeholder="Indirizzo eMail" >
				</div>
			</div>
			
			<div class="control-group <?php if(form_error('website_url')) echo "error";?>">
				<label class="control-label" for="website_url">URL Sito:</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="website_url" value="<?=$website_url?>" placeholder="URL Homepage" >
				</div>
			</div>
			
			<div class="control-group <?php if(form_error('newsletter_key')) echo "error";?>">
				<label class="control-label" for="newsletter_key">Lsita Newsletter:</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="newsletter_key" value="<?=$newsletter_key?>" placeholder="Chiave Lista MAILCHIMP" >
				</div>
			</div>	

		<div class="control-group <?php if(form_error('website_logo')) echo "error";?>">
			<label class="control-label" for="website_logo">Logo:</label>
			<div class="controls">
				<?php if (isset($website_logo)): ?>
					<img src="<?=base_url($website_logo)?>"/>
				<?php endif ?>
				<input class="input-xlarge" type="file" name="website_logo" />
			</div>
		</div>
		</fieldset>
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="website_users">Utenti:</label>
				<ol class="controls" id="website_users">
					<?php if (count($website_data['users'])): ?>
						<?php foreach ($website_data['users'] as $user): ?>
							<li><?=$user->username?></li>
						<?php endforeach ?>
						<li><a href="#website-user-add" data-toggle="tab" class="website-user-add-link">Aggiungi Utente.</a></li>
					<?php else: ?>
						<li>Nessun utente per questo sito. <br><a href="#website-user-add" data-toggle="tab" class="website-user-add-link">Clicca qui</a> per aggiungerne.
					<?php endif ?>
				</ul>
			</div>
			<div class="control-group">
				<label class="control-label" for="website_subscr">Sottoscrizioni:</label>
				<ol class="controls" id="website_subscr">
					<?php if (count($website_data['services'])): ?>
						<?php foreach ($website_data['services'] as $subscr): ?>
							<li><?=$subscr->service_name?> (Scade: <?=$subscr->service_expire?>)</li>
						<?php endforeach ?>
						<li><a href="#subscr-add" data-toggle="tab" id="add_subscr_link">Aggiungi Sottoscrizione</a></li>
					<?php else: ?>
						<li>Nessun servizio sottoscritto da questo sito. <br><a href="#subscr-add" data-toggle="tab" id="add_subscr_link">Clicca qui</a> per aggiungerne.
					<?php endif ?>
				</ol>
			</div>
			
		</fieldset>
		<fieldset>
			<div class="form-actions">
				<input type="reset" class="btn close-dialog" value="Annulla"/>
				<input type="submit" name="submit" class="btn btn-primary" value="Salva &rarr;"/>
			</div>
		</fieldset>
	</form>
	
	
	
</div>









<? if(DEBUG): ?>
	<div class="debug">
		<br/><hr/>
		<h2>DEBUG!</h2>
		<?foreach($this->load->get_cached_vars() as $var => $value):?>
			<b>$<?=$var?></b><br/>
			<pre><?print_r($value)?></pre>
			<hr/></br/>
		<?endforeach;?>
	</div>
<? endif; ?>