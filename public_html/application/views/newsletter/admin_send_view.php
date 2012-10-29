<div class="content">
	<script type="text/javascript">
	/* <![CDATA[ */

	$(document).ready(function(){
		$( 'textarea.editor' ).ckeditor();

	});
	/* ]]> */

	</script>
	<div class="block">
		<h2>Crea una news</h2>

		<?if($error):?>
			<span class="err"><?=$error?></span>
		<?endif;?>
		<?if($message):?>
			<span class="message"><?=$message?></span>
		<?endif;?>
	</div>
	
		<?=form_open(uri_string(), array('class' => "form-horizontal clearfix"), $form_send_hidden);?>
			<?php if (validation_errors()): ?>
				<div class="alert alert-error">
					<h4 class="alert-heading">Attenzione!</h4>
					<div><?=validation_errors()?></div>
				</div>
			<?php endif ?>

			<fieldset>
				<legend>Informazioni Generali</legend>
				<div class="control-group <? if(form_error('subject')) echo "error"?>">
					<label class="control-label" for="subject">Oggetto</label>
					<div class="controls">
						<input class="input-xlarge focused" id="subject" name="subject" size="50" maxlength="100" value="<?=set_value('subject')?>" placeholder="Oggetto visualizzato nella eMail">
					</div>
				</div>

				<div class="control-group <? if(form_error('from_email')) echo "error"?>">
					<label class="control-label" for="from_email">eMail Mittente</label>
					<div class="controls">
						<input class="input-xlarge focused" type="email" id="from_email" name="from_email" size="50" maxlength="100" value="<?=set_value('from_email', $user['email'])?>" placeholder="Indirizzo eMail valido">
						<span class="help-inline">Questo indirizzo mail sar&agrave; visualizzato come mittente.</span>
					</div>
				</div>


				<div class="control-group <? if(form_error('from_name')) echo "error"?>">
					<label class="control-label" for="from_name">Nome Mittente</label>
					<div class="controls">
						<input class="input-xlarge focused" name="from_name" id="from_name" placeholder="Nome Visualizzato" value="<?=set_value('from_name')?>">
						<span class="help-inline">La news inviata avr&agrave; questo nome come mittente.</span>
					</div>
				</div>


				<div class="control-group <? if(form_error('to_name')) echo "error"?>">
					<label class="control-label" for="to_name">Nome Destinatario</label>
					<div class="controls">
						<input class="input-xlarge focused" name="to_name" id="to_name" placeholder="Nome Visualizzato" value="<?=set_value('to_name')?>">
						<span class="help-inline">Il lettore della mail vedr&agrave; questo nome come destinatario.</span>
					</div>
				</div>

			</fieldset>
			<fieldset>
				<legend>Contenuto News</legend>
				<div class="control-group  <? if(form_error('news_body')) echo "error"?>">
					<label class="control-label" for="news_body">Contenuto della News</label>
					<div class="controls">
						<textarea class="input-xlarge editor" rows="5" id="news_body" name="news_body"><?=set_value('news_body')?></textarea>
						<span class="help-inline">Inserisci il contenuto della tua news.</span>
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				<input type="submit" class="btn btn-primary" name="action" value="Salva Bozza"/>
				<input type="submit" class="btn btn-success" name="action" value="Invia Subito"/>
				<button type="reset" class="btn">Cancella Form</button>
				<?=anchor('admin/main', "Annulla", array('class'=>'btn'));?>
			</div>
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