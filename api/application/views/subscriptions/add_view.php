<div class="content">
	<script type="text/javascript">
	/* <![CDATA[ */
	if (datefield.type!="date"){
	    jQuery(function($){
	        $('#expire').datepicker();
	    })
	}
	/* ]]> */
	</script>
	
	
	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	<?=form_open(uri_string())?>
		<h3>Add a subscription for <?=$website->website_name?></h3>
		
		<label for="service_id">Select the service to add: </label>
		<select name="service_id">
			<?foreach($services as $service):?>
				<option value="<?=$service->service_id?>"><?=$service->service_name?></option>
			<?endforeach;?>
		</select>
		
		<div id="expires_input">
			<label for "subscr_expire">Insert the expiration day. (Leave blank for never ending subscription, otherwhise use notation:<em> YYYY-MM-DD</em>)</label><br/>
			<input type="date" name="subscr_expire" id="expire"/>
			
			<input type="submit" name="submit" value="Continue &rarr;"/>
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