<div class="content">
	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	<?=form_open(uri_string(), array('class'=>'form-horizontal'), array('website_id'=>$website_id))?>
		<h3>Change subscription expiration</h3>
		<span class="description">Select the new date of expiration of the <?=$website_name?> subscription to <?=$service_name?></span>
		<div id="expires_input">
			<label for="expire">Insert the expiration day. <em>(Use notation: YYYY-MM-DD)</em></label><br/>
			<input type="text" name="expire" id="expire" value="<?=$subscr_expire?>" placeholder="YYYY-MM-DD"/>
			
			<input type="submit" name="submit" value="Continue &rarr;"/>
		</div>
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