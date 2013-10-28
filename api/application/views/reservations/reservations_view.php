<!-- Including the css -->
<?if(isset($css) AND $css):?>
	<div style="display:none;visibility:hidden;">
		<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8"/>
	</div>
<?endif;?>

<div class="content">
	
	<script src="/public/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript">
	/* <![CDATA[ */

	$(document).ready(function(){
		$( "input.detepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
		
		$( "input.detepicker" ).change(function(){
			DateToValue = $("#to_date").val();
			DateFromValue = $("#from_date").val();

			if(DateToValue && DateFromValue){
				if (Date.parse(DateToValue) < Date.parse(DateFromValue)) {
					$("#to_date").attr('value', DateFromValue);
				}
			}
			
		});

	});
	/* ]]> */

	</script>
	
	<? if(count($stilesheets)): ?>
		<? foreach($stilesheets as $sheet):?>
			<link rel="stylesheet" href="<?=PATH_WEB_CSS.$sheet->sheet_name?>" type="text/css" media="screen" charset="utf-8"/>
		<? endforeach; ?>
	<? endif; ?>
	
	<style type="text/css" media="screen">
		.error {
			color:red;
		}
		.input-xlarge {
			height:28px;
		}
		textarea.input-xlarge {
			height:auto;
		}
	</style>

	<?php if ( ! in_array("no-title", $options)): ?>
		<h1><?=$this->lang->line('title_request_booking');?></h1>
	<?php endif ?>
	
	<?if($error):?>
		<p><span class="alert alert-error"><?=$error?></span></p>
	<?endif;?>
	<?if($message):?>
		<p><span class="alert alert-info"><?=$message?></span></p>
	<?endif;?>
	
	
	<?=form_open('reservations/send', array('class'=>"form-horizontal"), $form_hidden); ?>
	<fieldset>
		<?php if (validation_errors()): ?>
			<div class="alert alert-error">
				<h4 class="alert-heading"><?=$this->lang->line('WARN');?>!</h4>
				<?=validation_errors()?>
			</div>
		<?php endif ?>
	</fieldset>
	
	<fieldset>
		<legend><?=$this->lang->line('USER_INFO');?></legend>

		<div class="control-group <?php if(form_error('name')) echo "error";?>">
			<label class="control-label" for="name"><?=$this->lang->line('NAME');?></label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="name" value="<?=set_value('name')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('surname')) echo "error";?>">
			<label class="control-label" for="surname"><?=$this->lang->line('SURNAME');?></label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="surname" value="<?=set_value('surname')?>">
			</div>
		</div>
	</fieldset>
	
	<fieldset id="" class="">
		<legend><?=$this->lang->line('CONTACT_INFO');?></legend>
		
		<div class="control-group <?php if(form_error('tel')) echo "error";?>">
			<label class="control-label" for="tel"><?=$this->lang->line('TEL');?></label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="tel" value="<?=set_value('tel')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('email')) echo "error";?>">
			<label class="control-label" for="email"><?=$this->lang->line('EMAIL');?></label>
			<div class="controls">
				<input class="input-xlarge" type="email" name="email" value="<?=set_value('email')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('email_check')) echo "error";?>">
			<label class="control-label" for="email_check"><?=$this->lang->line('EMAIL_CHECK');?></label>
			<div class="controls">
				<input class="input-xlarge" type="email" name="email_check" value="<?=set_value('email_check')?>">
			</div>
		</div>

		
	</fieldset>

	<fieldset>
		<legend><?=$this->lang->line('title_booking_info');?></legend>
		<p><?=$this->lang->line('description_booking_info');?></p>

		<div class="control-group <?php if(form_error('from_date')) echo "error";?>">
			<label class="control-label" for="from_date"><?=$this->lang->line('arrival');?></label>
			<div class="controls">
				<input class="input-xlarge detepicker" type="text" name="from_date" id="from_date" value="<?=set_value('from_date')?>" placeholder="<?=$this->lang->line('PLACEHOLDER_DATE');?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('to_date')) echo "error";?>">
			<label class="control-label" for="name"><?=$this->lang->line('departure');?></label>
			<div class="controls">
				<input class="input-xlarge detepicker" type="text" name="to_date" id="to_date" value="<?=set_value('to_date')?>" placeholder="<?=$this->lang->line('PLACEHOLDER_DATE');?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('adults')) echo "error";?>">
			<label class="control-label" for="adults"><?=$this->lang->line('adults');?></label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="adults" value="<?=set_value('adults')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('babies')) echo "error";?>">
			<label class="control-label" for="babies"><?=$this->lang->line('babies');?></label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="babies" value="<?=set_value('babies')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('notes')) echo "error";?>">
			<label class="control-label" for="notes"><?=$this->lang->line('NOTES');?></label>
			<div class="controls">
				<textarea class="input-xlarge" id="textarea" name="notes" rows="4"><?=set_value('notes')?></textarea>
			</div>
		</div>
		
	</fieldset>
	
	<fieldset id="accettazione_ed_invio" class="">
		<legend><?=$this->lang->line('title_accept_send');?></legend>
		
		<?php if ($newsletter): ?>
			<div class="control-group">
				<label class="control-label" for="newsletter"><?=$this->lang->line('SERVICE_NEWSLETTER');?></label>
				<div class="controls">
					<label class="checkbox">
						<input id="newsletter" type="checkbox" name="newsletter" value="1" checked>
						<?=$this->lang->line('description_newsletter_subscribe');?>
					</label>
				</div>
			</div>
		<?php endif ?>
		
		<div class="control-group <?php if(form_error('law_confirmation')) echo "error";?>">
			<label class="control-label" for="law_confirmation"><?=$this->lang->line('TOC_PRIVACY');?></label>
			<div class="controls">
				<label class="checkbox">
					<input id="newsletter" type="checkbox" name="law_confirmation" value="1">
					<?=$this->lang->line('PRIVACY_ACKNOWLEDGMENT');?>
				</label>
			</div>
		</div>

		<p>
			<strong><?=$this->lang->line('IMPORTANT');?></strong>: <?=$this->lang->line('wait_for_confirm');?>
		</p>
		<input type="submit" name="submit" class="btn btn-primary" value="<?=$this->lang->line('SEND_REQUEST');?> &rarr;"/>
		
	</fieldset>
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