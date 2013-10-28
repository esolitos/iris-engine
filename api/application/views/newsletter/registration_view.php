<div class="content">
	<div style="display:none">
		<?php if ( ! isset($css)): ?>
			<link rel="stylesheet" href="<?=base_url(PATH_WEB_CSS."defaults/newsletter.css")?>" type="text/css" media="all" charset="utf-8">
		<?php else: ?>
			<link rel="stylesheet" href="<?=base_url($css)?>" type="text/css" media="all" charset="utf-8">
		<?php endif ?>
	</div>
	<style type="text/css" media="screen">
		.error {
			color:red;
		}
	</style>
	<?php if ( ! in_array("no-title", $options)): ?>
		<h2><?=$this->lang->line('title_subscibe_to_neswletter');?></h2>
	<?php endif ?>
	
	<?if($error):?>
		<div class="alert alert-error">
			<h4 class="alert-heading"><?=$this->lang->line('ERR');?>!</h4>
			<?=$error?>
		</div>
	<?endif;?>
	<?if($message):?>
		<div class="alert alert-warning">
			<h4 class="alert-heading"><?=$this->lang->line('WARN');?>!</h4>
			<?=$message?>
		</div>
	<?endif;?>
	
	<?php if (validation_errors()): ?>
		<div class="alert alert-error">
			<h4 class="alert-heading"><?=$this->lang->line('WARN');?>!</h4>
			<?=validation_errors()?>
		</div>
	<?php endif ?>
	
	<?=form_open(uri_string(), array('class'=>"form-horizontal"), $form_hidden); ?>
		<fieldset>

			
			<div class="control-group <?php if(form_error('name')) echo "error";?>">
				<label class="control-label" for="name"><?=$this->lang->line('NAME');?>*:</label>
				<div class="controls">
					<input type="text" name="name" value="<?=set_value('name')?>"/>
				</div>
			</div>

			<div class="control-group <?php if(form_error('surname')) echo "error";?>">
				<label class="control-label" for="surname"><?=$this->lang->line('SURNAME');?>*:</label>
				<div class="controls">
					<input type="text" name="surname" value="<?=set_value('surname')?>"/>
				</div>
			</div>

			<div class="control-group <?php if(form_error('email')) echo "error";?>">
				<label class="control-label" for="surname"><?=$this->lang->line('EMAIL');?>*:</label>
				<div class="controls">
					<input type="email" name="email" value="<?=set_value('email')?>"/>
				</div>
			</div>

			<div class="control-group <?php if(form_error('email_check')) echo "error";?>">
				<label class="control-label" for="email_check"><?=$this->lang->line('EMAIL_CHECK');?>*:</label>
				<div class="controls">
					<input type="text" name="email_check" value="<?=set_value('email_check')?>"/>
				</div>
			</div>

		</fieldset>
		<fieldset>

			<div class="control-group">
				<label class="control-label" for="law_confirmation"><?=$this->lang->line('TOC_PRIVACY');?>*:</label>
				<div class="controls">
					<label class="checkbox">
						<input id="law_confirmation" type="checkbox" name="law_confirmation" value="1" >
						<?=$this->lang->line('PRIVACY_ACKNOWLEDGMENT');?>
					</label>
				</div>
			</div>
			
			<input type="submit" name="submit" class="btn btn-primary" value="<?=$this->lang->line('button_subscribe');?> &rarr;"/>
		</fieldset>
	</form>
</div>