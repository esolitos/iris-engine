<script type="text/javascript">
/* <![CDATA[ */

$(document).ready(function(){
	$( 'textarea.editor' ).ckeditor();
});
/* ]]> */

</script>
<style type="text/css" media="screen">
.modal{
	width: 800px;
	margin: 0 0 0 -375px;
}
</style>

<div class="content">
		<?=form_open_multipart(uri_string(), array('class' => "form-horizontal clearfix"), $form_hidden);?>
			<fieldset>
				<legend>Informazioni Generali</legend>
				<div class="control-group">
					<label class="control-label" for="subject">Oggetto</label>
					<div class="controls">
						<input <? if ( ! $edit) echo "disabled" ?> class="input-xlarge focused" id="subject" name="subject" size="50" maxlength="100" value="<?=$news->subject?>" placeholder="Oggetto visualizzato nella eMail">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="from_email">eMail Mittente</label>
					<div class="controls">
						<input <? if ( ! $edit) echo "disabled" ?>  class="input-xlarge focused" type="email" id="from_email" name="from_email" size="50" maxlength="100" value="<?=$news->from_email?>" placeholder="Indirizzo eMail valido">
						<span class="help-inline">Questo indirizzo mail sar&agrave; visualizzato come mittente.</span>
					</div>
				</div>


				<div class="control-group">
					<label class="control-label" for="from_name">Nome Mittente</label>
					<div class="controls">
						<input <? if ( ! $edit) echo "disabled" ?>  class="input-xlarge focused" name="from_name" id="from_name" placeholder="Nome Visualizzato" value="<?=$news->from_name?>">
						<span class="help-inline">La news inviata avr&agrave; questo nome come mittente.</span>
					</div>
				</div>


				<div class="control-group">
					<label class="control-label" for="to_name">Nome Destinatario</label>
					<div class="controls">
						<input <? if ( ! $edit) echo "disabled" ?>  class="input-xlarge focused" name="to_name" id="to_name" placeholder="Nome Visualizzato" value="<?=$news->to_name?>">
						<span class="help-inline">Il lettore della mail vedr&agrave; questo nome come destinatario.</span>
					</div>
				</div>

			</fieldset>
			<fieldset>
				<legend>Contenuto News</legend>
				<div class="control-group">
					<label class="control-label" for="news_body_edit">Contenuto della News</label>
					<div class="controls">
						<?php if ($edit): ?>
							<textarea class="input-xlarge editor" rows="5" id="news_body_edit-<?=md5(rand())?>" name="news_body_edit"><?=$news_content->html?></textarea>
							<span class="help-inline">Inserisci il contenuto della tua news.</span>
						<?php else: ?>
							<div class="well"><?=$news_content->html?></div>
						<?php endif ?>
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				<?php if ($news->status != "sent"): ?>
					<? if ($edit): ?>
						<input type="submit" class="btn btn-primary" name="action" value="Salva Modifiche"/>
					<? endif; ?>
					<input type="submit" class="btn btn-success" name="action" value="Invia News"/>
				<?php endif ?>
				<a href="#" class="btn close-dialog">Chiudi</a>
			</div>
		</form>
	
</div>


<? if(DEBUG): ?>
	<div class="debug">
		<br/><hr/>
		<?foreach($this->load->get_cached_vars() as $var => $value):?>
			<b>$<?=$var?></b><br/>
			<pre><?print_r($value)?></pre>
			<hr/></br/>
		<?endforeach;?>
	</div>
<? endif; ?>