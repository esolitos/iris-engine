<div class="content">
	<div class="row">
		<h1>Gestisci Utenti</h1>
		<p>In questa pagina puoi gestire gli utenti di un sito.</p>

		<?if($error):?>
			<div class="alert alert-error"><?=$error?></div>
		<?endif;?>
		<?if($message):?>
			<div class="alert alert-info"><?=$message?></div>
		<?endif;?>
	</div> <!-- /.row -->

	<div class="row">
		<h3>Utenti Attuali</h3>
		<?php if (count($all_users) > 1): ?>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Website</th>
						<th>Nome Utente</th>
						<th>eMail</th>
						<th>Data Registrazione</th>
						<th>Ultimo Accesso</th>
					</tr>
				</thead>
				<tbody>
					<? foreach($all_users as $user): ?>
						<? if($user->user_id != USER_ID_MASTER_ADMIN): ?>
							<tr>
								<td><?=$user->user_id?></td>
								<td><?=$user->user_website?></td>
								<td><?=$user->username?></td>
								<td><?=$user->email?></td>
								<td><?=$user->reg_date?></td>
								<td><?=$user->user_last_login?></td>
							</tr>
						<? endif; ?>
					<? endforeach; ?>
				</tbody>
			</table>	
		<?php else: ?>
			<p>Non ci sono utenti per questo sito. Consigliamo vivamente di aggiungerne uno, altrimenti esso non può essere gestito</p>
		<?php endif ?>
	</div> <!-- /.row -->

	<? if(validation_errors()): ?>
		<div class="row clearfix"><?=validation_errors('<div class="alert alert-error">','</div>')?></div>
	<? endif; ?>

	<div class="row">
		<h3>Aggiunta Utente</h3>
		<p>Aggiungerai un utente al sito <?=$website['info']->website_name?>.</p>
		<?=form_open('admin/users/add', array('class' => 'well inline-form'), $form_add_hidden);?>
			<input type="text" name="username" value="<?=set_value('username')?>" placeholder="Nome Utente"/>
			<input type="text" name="email" value="<?=set_value('email')?>" placeholder="Indirizzo eMail"/>

			<input type="submit" name="submit" value="Aggiungi &rarr;" />
		</form> <!-- /Add User -->
	</div> <!-- /.row -->

	<div class="row">
		<h3>Modifica Utente</h3>
		<p>Aggiungerai un utente al sito <?=$website['info']->website_name?>.</p>
			<?=form_open('admin/users/edit', array('class' => 'well inline-form'), $form_edit_hidden)?>
			<select name="user_id">
				<option disabled selected>-Utente-</option>
				<? foreach($all_users as $user): ?>
					<? if($user->user_id != USER_ID_MASTER_ADMIN): ?>
						<option value="<?=$user->user_id?>"><?=$user->username?></option>
					<? endif; ?>
				<? endforeach; ?>
			</select>

			<select name="action">
				<option disabled selected>-Azione-</option>
				<option value="set_pass">Reset Password</option>
				<option value="delete">Elimina Utente</option>
			</select>

			<input type="submit" name="submit" value="Modifica &rarr;" />
		</form> <!-- /Edit User -->
	</div> <!-- /.row -->
	
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