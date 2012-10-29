<div id="content" class="content clearfix">
	<?=form_open('iris/contact', array('id'=>'issue-contact-form', 'class'=>'form-horizontal'), $form_hidden); ?>
	<fieldset>
		<legend>Scrivici il tuo problema &nbsp;<small>NB:Tutti i campi sono obbligatori!</small></legend>		
		
		<?php if (validation_errors()): ?>
			<div class="control-group error">
				<div class="validation-errors controls span7">
					<h3>Si sono verificati i seguenti errori:</h3>
					<?= validation_errors('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>', '</div>') ?>			
				</div>
			</div>
		<?php endif ?>
		
		<div class="control-group">
			<label class="control-label" for="username">Username</label>
			<div class="controls">
				<input type="text" class="input-xlarge disabled" disabled value="<?=$user['username']?>">
			</div>
		</div>

		<div class="control-group <? if(form_error('name')) echo "error"; ?>">
			<label class="control-label" for="name">Nome e Cognome</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="name" name="name" placeholder="John Smith" value="<?=set_value('name')?>">
				<p class="help-block">Inserisci il tuo nome.</p>
			</div>
		</div>

		<div class="control-group <? if(form_error('company')) echo "error"; ?>">
			<label class="control-label" for="company">Azienda</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="company" name="company" placeholder="Apple Inc." value="<?=set_value('company')?>">
				<p class="help-block">Inserisci il nome dell'azienda per conto della quale ci sontatti.</p>
			</div>
		</div>

		<div class="control-group <? if(form_error('email')) echo "error"; ?>">
			<label class="control-label" for="email">eMail di contatto</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="email" name="email" value="<?=set_value('email', $user['email'])?>">
				<p class="help-block">Inserisci una mail sulla quale sia possibile contattarti.</p>
			</div>
		</div>

		<div class="control-group <? if(form_error('tel')) echo "error"; ?>">
			<label class="control-label" for="tel">Telefono di contatto</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="tel" name="tel"  value="<?=set_value('tel')?>">
				<p class="help-block">Inserisci un numero di telefono sul quale sia possibile contattarti.</p>
			</div>
		</div>

		<div class="control-group <? if(form_error('contact_reason')) echo "error"; ?>">
			<label class="control-label" for="contact_reason">Tipologia Problema</label>
			<div class="controls">
				<select name="contact_reason" id="contact_reason">
					<option value="FALSE" disabled <?= set_select('contact_reason', 'FALSE', TRUE) ?>>-Scegli una voce-</option>
					<option value="admin" <?= set_select('contact_reason', 'admin') ?>>Richiesta di Supporto</option>
					<option value="tech"<?= set_select('contact_reason', 'tech') ?>>Problemi Tecnici</option>
					<option value="ask" <?= set_select('contact_reason', 'ask') ?>>Domande su IrisLogin</option>
					<option value="suggest" <?= set_select('contact_reason', 'suggest') ?>>Suggerimenti</option>
					<option value="oth" <?= set_select('contact_reason', 'oth') ?>>Altro</option>
				</select>
				<p class="help-block">Seleziona il motivo del tuo contatto tra quelli elencati.</p>
			</div>
		</div>

		<div class="control-group <? if(form_error('message')) echo "error"; ?>">
			<label class="control-label" for="message">Messaggio</label>
			<div class="controls">
				<textarea class="input-xlarge" id="message" name="message" rows="5"><?= set_value('message')?></textarea>
				<p class="help-block">Non includere HTML nel testo altrimenti<br> la tua email verr&agrave; catalogata come spam<br> e potrebbe non essere consegnata.</p>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Invia Richiesta</button>
			<button class="btn" id="issue-cancel-btn">Annulla</button>
		</div>
	</fieldset>
</form>
</div>