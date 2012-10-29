<div class="content">
	
	<div class="block">
		<h2>Admin Homepage</h2>
		<p class="description">Welcome <em><?=$user['username']?></em>. You need to select the website to administer.</p>
		
		<?if($error):?>
			<span class="alert alert-error"><?=$error?></span>
		<?endif;?>
		<?if($message):?>
			<span class="alert alert-info"><?=$message?></span>
		<?endif;?>
		
		<?=form_open('admin/main/select_website')?>
			<span class="form_block radio_input">
				<label for="website">Select Website:</label><br>
				<? foreach($websites as $website): ?>
					<span class="radio_button row"><input type="radio" name="website" value="<?=$website->website_id?>"><?=$website->website_name?></span>
				<? endforeach; ?>
			</span>
			
			<span clas="form_row submit"><input type="submit" name="submit" value="Continue &rarr;" /></span>
		</form>
		
		<?if($user['user_id']==USER_ID_MASTER_ADMIN):?>
			<span>... or <?=anchor('admin/websites', "Manage Websites")?></span>
		<?endif;?>
		
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