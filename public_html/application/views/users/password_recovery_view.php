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
		
		<?=validation_errors('<div class="alert alert-error">', '</div>')?>
		
		<?=form_open_multipart(current_url(), array('class' => 'form-inline')); ?>
			<filedset>
				<div class="control-group <? if(form_error('email')) echo "error";?>">
					<div class="controls">
						<input type="text" name="username" placeholder="Utente" value="<?=set_value('username')?>"/>
					</div>
				</div>
				
				<div class="control-group <? if(form_error('email')) echo "error";?>">
					<div class="controls">
						<input type="email" name="email" placeholder="eMail" value="<?=set_value('email')?>"/>
					</div>
				</div>
				
				<div class="help-block">Inserisci il tuo Nome Utente ed email per reimpostare la passowrd.</div>
				
				<div class="control-group">
					<div class="controls">
						<input type="submit" name="submit" class="btn btn-primary" value="Reimposta Password &rarr;"/>
					</div>
				</div>
			</filedset>
		</form>
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