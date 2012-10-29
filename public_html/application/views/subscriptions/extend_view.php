<div class="content">
	<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript">
	/* <![CDATA[ */
	    jQuery(function($){
	        $('#expire').datepicker();
	    })
	/* ]]> */
	</script>
	
	
	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	<?=form_open(uri_string())?>
		<h3>Change subscription expiration</h3>
		<span class="description">Select the new date of expiration of the <?=$website->website_name?> subscription to <?=$service->service_name?></span>
		<div id="expires_input">
			<label for "expire">Insert the expiration day. <em>(Use notation: YYYY-MM-DD)</em></label><br/>
			<input type="date" name="expire" id="expire" value="<?=$subscr_expire?>"/>
			
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