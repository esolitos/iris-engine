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
		<h1>Richiedi una Prenotazione</h1>
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
				<h4 class="alert-heading">Attenzione!</h4>
				<?=validation_errors()?>
			</div>
		<?php endif ?>
	</fieldset>
	
	<fieldset>
		<legend>Informazioni Utente</legend>

		<div class="control-group <?php if(form_error('name')) echo "error";?>">
			<label class="control-label" for="name">Nome:</label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="name" value="<?=set_value('name')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('surname')) echo "error";?>">
			<label class="control-label" for="surname">Cognome</label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="surname" value="<?=set_value('surname')?>">
			</div>
		</div>
	</fieldset>
	
	<fieldset id="" class="">
		<legend>Informazioni di Contatto</legend>
		
		<div class="control-group <?php if(form_error('tel')) echo "error";?>">
			<label class="control-label" for="tel">Telefono</label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="tel" value="<?=set_value('tel')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('email')) echo "error";?>">
			<label class="control-label" for="email">eMail</label>
			<div class="controls">
				<input class="input-xlarge" type="email" name="email" value="<?=set_value('email')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('email_check')) echo "error";?>">
			<label class="control-label" for="email_check">Verifica eMail</label>
			<div class="controls">
				<input class="input-xlarge" type="email" name="email_check" value="<?=set_value('email_check')?>">
			</div>
		</div>

		
	</fieldset>

	<fieldset>
		<legend>Richiesta di Prenotazione</legend>
		<p>Inserite qui di seguito i dati riguardanti la vostra richiesta di prenotazione.</p>

		<div class="control-group <?php if(form_error('from_date')) echo "error";?>">
			<label class="control-label" for="from_date">Arrivo</label>
			<div class="controls">
				<input class="input-xlarge detepicker" type="text" name="from_date" id="from_date" value="<?=set_value('from_date')?>" placeholder="Formato: GG-MM-AAAA">
			</div>
		</div>

		<div class="control-group <?php if(form_error('to_date')) echo "error";?>">
			<label class="control-label" for="name">Partenza</label>
			<div class="controls">
				<input class="input-xlarge detepicker" type="text" name="to_date" id="to_date" value="<?=set_value('to_date')?>" placeholder="Formato: GG-MM-AAAA">
			</div>
		</div>

		<div class="control-group <?php if(form_error('adults')) echo "error";?>">
			<label class="control-label" for="adults">Adulti</label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="adults" value="<?=set_value('adults')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('babies')) echo "error";?>">
			<label class="control-label" for="babies">Bambini</label>
			<div class="controls">
				<input class="input-xlarge" type="text" name="babies" value="<?=set_value('babies')?>">
			</div>
		</div>

		<div class="control-group <?php if(form_error('notes')) echo "error";?>">
			<label class="control-label" for="notes">Altre Note</label>
			<div class="controls">
				<textarea class="input-xlarge" id="textarea" name="notes" rows="4"><?=set_value('notes')?></textarea>
			</div>
		</div>
		
	</fieldset>
	
	<fieldset id="accettazione_ed_invio" class="">
		<legend>Accettazione ed Invio</legend>
		
		<?php if ($newsletter): ?>
			<div class="control-group">
				<label class="control-label" for="newsletter">Newsletter</label>
				<div class="controls">
					<label class="checkbox">
						<input id="newsletter" type="checkbox" name="newsletter" value="1" checked>
						Iscriviti alla Newsletter per ricevere novit&agrave; e sconti speciali.
					</label>
				</div>
			</div>
		<?php endif ?>
		
		<div class="control-group <?php if(form_error('law_confirmation')) echo "error";?>">
			<label class="control-label" for="law_confirmation">Trattamento Dati</label>
			<div class="controls">
				<label class="checkbox">
					<input id="newsletter" type="checkbox" name="law_confirmation" value="1">
					I dati personali da te liberamente comunicati saranno trattati in via del tutto riservata nel pieno rispetto della legge 31 dicembre 1996 n.675.
				</label>
			</div>
		</div>

		<p>
			<strong>Importante</strong>: Dopo aver inviato la richiesta dovrai attendere una nostra comunicazione per la disponibilità del periodo richiesto e la conferma della prenotazione.
		</p>
		<input type="submit" name="submit" class="btn btn-primary" value="Invia Richiesta &rarr;"/>
		
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