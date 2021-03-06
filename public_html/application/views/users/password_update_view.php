<div class="content">
	<div id="login" <?if(validation_errors() OR $error OR $message) echo 'class="error"'?>>
		<?if($error):?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert">×</a>
				<?=$error?>
			</div>
		<?endif;?>
		<?if($message):?>
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert">×</a>
				<?=$message?>
			</div>
		<?endif;?>
		
		<?php if ( ! $update_success): ?>
			<?=validation_errors('<div class="alert alert-error">', '</div>')?>
		
			<?=form_open(current_url(), array('class' => 'form-inline')); ?>
				<filedset>
					<div class="control-group <? if(form_error('old_password')) echo "error";?>">
						<div class="controls">
							<input type="password" name="old_password" placeholder="Vecchia Password"/>
						</div>
					</div>
					<br>
				
					<div class="control-group <? if(form_error('new_password')) echo "error";?>">
						<div class="controls">
							<input type="password" name="new_password" placeholder="Nuova Password"/>
						</div>
					</div>

					<div class="control-group <? if(form_error('new_password_check')) echo "error";?>">
						<div class="controls">
							<input type="password" name="new_password_check" placeholder="Verifica Nuova Password"/>
						</div>
					</div>
					<br>

					<div class="control-group <? if(form_error('email')) echo "error";?>">
						<div class="controls">
							<input type="email" name="email" placeholder="eMail" value="<?=set_value('email')?>"/>
						</div>
					</div>

					<div class="help-block">Inserisci la tua vecchia password, la nuova password (due volte)<br> ed il tuo indirizzo email per modificare la chiave di acesso.</div>
				
					<div class="control-group">
						<div class="controls">
							<input type="submit" name="submit" class="btn btn-primary" value="Modifica Password &rarr;"/>
						</div>
					</div>
				</filedset>
			</form>			
		<?php endif ?>
		
	</div>
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