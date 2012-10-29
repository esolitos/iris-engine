<div class="content clearfix">
	<script src="/public/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript">
	/* <![CDATA[ */
	// window.location.hash.replace("#", "")

	$(document).ready(function(){
		// $( "#add_offer_expire" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#offer_expire" ).datepicker({ dateFormat: 'dd-mm-yy' });
	});
	/* ]]> */

	</script>
	
	<h2> Mofifica dell'offerta:<br><small class="offset1">"<?=$offer->offer_title?>"<small></h2>

	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	
	
	<div id="offer-edit">
		<?php echo form_open_multipart('admin/offers/edit/save', array('class' => 'form-horizontal clearfix', 'id' => 'form-edit-offer'), $form_edit_hidden); ?>

			<fieldset>
				<?php if (validation_errors()): ?>
					<div class="alert alert-error">
						<h4 class="alert-heading">Attenzione!</h4>
						<?=validation_errors()?>
					</div>
				<?php endif ?>
			</fieldset>

			<fieldset>
				<div class="control-group <?php if(form_error('offer_title')) echo "error";?>">
					<label class="control-label" for="offer_title">Titolo</label>
					<div class="controls">
						<?php if(form_error('offer_title')): ?>
							<input class="input-xlarge focused" id="offer_title" name="offer_title" size="50" maxlength="100" value="<?=set_value('offer_title')?>" placeholder="">
						<?php else: ?>
							<input class="input-xlarge focused" id="offer_title" name="offer_title" size="50" maxlength="100" value="<?=$offer->offer_title?>" placeholder="">
						<?php endif ?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="offer_special">Offerta Speciale</label>
					<div class="controls">
						<label class="checkbox">
							<input id="offer_special" type="checkbox" name="offer_special" value="1" <?if($offer->offer_special):?>checked<?endif;?>>
							Seleziona questa opzione se vuoi che l'offerta abbia l'indicazione "Offerta Speciale".
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="expires">Con Scadenza</label>
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" name="expires" id="expires" value="1" <?if($offer->offer_expire):?>checked<?endif;?> >
							Seleziona questa opzione se desideri che l'offerta abbia una data di scadenza dopo la quale il sistema la disattiver&agrave; in automatico.
						</label>
					</div>
				</div>

				<div id="expires" class="control-group <?php if(form_error('offer_expire')) echo "error";?>">
					<label class="control-label" for="offer_expire">Scadenza</label>
					<div class="controls">
						<?php if(form_error('offer_expire')): ?>
							<input class="input-xlarge focused" name="offer_expire" id="offer_expire" placeholder="GG-MM-AAA"  value="<?=set_value('offer_expire')?>" >
						<?php else: ?>
							<input class="input-xlarge focused" name="offer_expire" id="offer_expire" placeholder="GG-MM-AAA"  value="<?=$offer->offer_expire?>" >
						<?php endif ?>
						<span class="help-inline">Seleziona la data di scadenza. (Utilizza il formato "GG-MM-AA") </span>
					</div>
				</div>

			</fieldset>
			<fieldset>

				<div class="control-group  <?php if(form_error('offer_body')) echo "error";?>">
					<label class="control-label" for="offer_body">Testo</label>
					<div class="controls">
						<?php if(form_error('offer_body')): ?>
							<textarea class="input-xlarge" rows="5" id="offer_body" name="offer_body"><?=set_value('offer_body')?></textarea>
						<?php else: ?>
							<textarea class="input-xlarge" rows="5" id="offer_body" name="offer_body"><?=$offer->offer_body?></textarea>
						<?php endif ?>
						<span class="help-inline">Inserisci il testo che verr&agrave; visualizzato nella pagina delle offerte.</span>
					</div>
				</div>

				<?if($offer->offer_image):?>
					<div class="control-group">
						<label class="control-label" for="offer_image">Immagine Corrente</label>
						<div class="controls">
							<img src="/public/upload/<?=$offer->offer_image?>" class="offer-old-image">
						</div>
					</div>
				<? endif; ?>
				
				<div class="control-group">
					<label class="control-label" for="offer_image">Cambia Immagine dell'Offerta</label>
					<div class="controls">
						<input class="input-file" id="offer_image" name="offer_image" type="file">
						<span class="help-inline"><em>(Opzionale)</em> Seleziona e carica un immagine relativa all'offerta.</span>
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Salva modifiche</button>
				<a href="/admin/offers.html" class="btn">Annulla</a>
			</div>
		</form> 
	
	
	</div>
	
	
</div><!-- /content -->

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