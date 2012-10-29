<div class="content clearfix">
	<div id="login" <?if(validation_errors() OR $error OR $message) echo 'class="error"'?>>
		<?=form_open_multipart('admin/login', array('class' => 'form-vertical'), $form_login_hidden); ?>
			<filedset>
				<?if($error):?>
					<div class="alert alert-error">
						<?=$error?>
					</div>
				<?endif;?>
				<?if($message):?>
					<div class="alert alert-info">
						<?=$message?>
					</div>
				<?endif;?>
			</filedset>
			<filedset>
				<p>Inserisci il tuo nome Utente e la Password per iniziare a gestire il tuo sito.</p>
				<div class="control-group">
					<div class="controls">
						<input type="text" name="username" placeholder="Utente"/>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<input type="password" name="pass" placeholder="Password"/>
					</div>
				</div>
				
				<span class="help-block">Dimenticato la password? <?=anchor('user/password/lost', "Clicca qui")?>.</span>

				<div class="control-group">
					<div class="controls">
						<input type="submit" name="submit" class="btn btn-primary" value="Login &rarr;"/><br>
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