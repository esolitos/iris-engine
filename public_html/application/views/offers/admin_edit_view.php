<? global $LANGUAGES ?>
<div class="content clearfix">
	<script src="/public/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript">
	/* <![CDATA[ */
	// window.location.hash.replace("#", "")

	$(document).ready(function(){
		// $( "#add_offer_expire" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#offer_expire" ).datepicker({ dateFormat: 'dd-mm-yy' });
		
		// Marks a translation to be removed
		$("input.remove_lang").click( function(clicked) {
			var clang = $(this).data("lang");

			if( $(this).is(":checked") ) {
				$("a[href=#title_lang_"+clang+"]").css('color', "red");
				$("#title_lang_"+clang+" input[type=text], #title_lang_"+clang+" textarea").attr('disabled', true);
			} else {
				$("a[href=#title_lang_"+clang+"]").css('color', "");
				$("#title_lang_"+clang+" input[type=text], #title_lang_"+clang+" textarea").attr('disabled', false);
			}
			
		} );
		
		// Add a new translation on request.
		$("#add_lang").click(function(clicked) {
			clicked.preventDefault();
			var nlang = $("#new_lang").val();
			
			$("#title_lang_"+nlang+" input[type=text], #title_lang_"+nlang+" textarea").attr('disabled', false).parents(".accordion-group").removeClass("in hidden");
			
			$("#new_lang option[value="+nlang+"]").remove();
		});
		
		// Removing the "fake removed languages"
		$("form#form-edit-offer").submit( function(event) {
			$("input.remove_lang.new").remove();
		});
		
	});
	/* ]]> */

	</script>
	
	<h2> Mofifica dell'offerta: <small>"<?=$offer->offer_title[LANG_DEFAULT]?>"</small></h2>
	<small>(NOTA: Tutte le modifiche verranno apportate soltanto dopo aver cliccato "Salve Modifiche"!)</small>

	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	
	
	<div id="offer-edit">
		<?php echo form_open_multipart('admin/offers/edit/save', array('class' => 'form-horizontal clearfix', 'id' => 'form-edit-offer'), $form_edit_hidden); ?>

			<fieldset>
				<legend></legend>
				<?php if (validation_errors()): ?>
					<div class="alert alert-error">
						<h4 class="alert-heading">Attenzione!</h4>
						<?=validation_errors()?>
					</div>
				<?php endif ?>
			</fieldset>

			<fieldset>
				
				<div class="accordion" id="title_accordion">
					<?php foreach ($LANGUAGES as $o_lang=>$lang_name): ?>
						<?php $if_hidden = ( in_array($o_lang, $offer->languages) OR form_error('offer_title['.$o_lang.']') OR form_error('offer_body['.$o_lang.']') OR $o_lang == LANG_DEFAULT ) ? FALSE : 'hidden' ;  ?>
						
							<div class="accordion-group <?=$if_hidden?>">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#title_accordion" href="#title_lang_<?=$o_lang?>"><span class="lang-name"><?=$LANGUAGES[$o_lang]?></span></a>
								</div>
								<div id="title_lang_<?=$o_lang?>" class="accordion-body collapse <?if($o_lang == LANG_DEFAULT) echo "in"?>">
									<div class="accordion-inner">
										
										<div class="control-group  <?php if(form_error('offer_title['.$o_lang.']')) echo "error";?>">
											<div class="controls">
												<?php if(set_value('offer_title['.$o_lang.']')): ?>
													<input type="text" class="input-xlarge focused" name="offer_title[<?=$o_lang?>]" size="50" maxlength="100" value="<?=set_value('offer_title['.$o_lang.']')?>">
												<?php elseif ( isset($offer->offer_title[$o_lang]) ): ?>
													<input type="text" class="input-xlarge focused" name="offer_title[<?=$o_lang?>]" size="50" maxlength="100" value="<?=$offer->offer_title[$o_lang]?>">
												<?php else: ?>
													<input <?php if($if_hidden ) echo "disabled"?> type="text" class="input-xlarge focused" name="offer_title[<?=$o_lang?>]" size="50" maxlength="100" value="">
												<?php endif ?>
											</div>	
										</div> <!-- /.control-group -->
										
										<div class="control-group  <?php if(form_error('offer_body['.$o_lang.']')) echo "error";?>">
											<div class="controls">
												<?php if(set_value('offer_body['.$o_lang.']')): ?>
													<textarea class="input-xlarge" rows="5" id="offer_body[<?=$o_lang?>]" name="offer_body[<?=$o_lang?>]"><?=set_value('offer_body['.$o_lang.']')?></textarea>
												<?php elseif ( isset($offer->offer_body[$o_lang]) ): ?>
													<textarea class="input-xlarge" rows="5" id="offer_body[<?=$o_lang?>]" name="offer_body[<?=$o_lang?>]"><?=$offer->offer_body[$o_lang]?></textarea>
												<?php else: ?>
													<textarea <?php if($if_hidden ) echo "disabled"?> class="input-xlarge" rows="5" id="offer_body[<?=$o_lang?>]" name="offer_body[<?=$o_lang?>]"></textarea>
												<?php endif ?>
												<span class="help-inline">Inserisci il testo (nella lingua corretta) che verr&agrave; visualizzato nella pagina delle offerte.</span>
											</div>
										</div> <!-- /.control-group -->
										
										<?php if($o_lang != LANG_DEFAULT): ?>
											<div class="control-group warning">
												<label class="control-label" for="remove_lang">Elimina Traduzione <br/> <em>(Azione Irreversibile)</em></label>
												<div class="controls">
													<label class="checkbox">
														<input class="remove_lang <?php if( $if_hidden ) echo "new"?>" type="checkbox" name="remove_lang[]" value="<?=$o_lang?>" data-lang="<?=$o_lang?>"> Seleziona per eliminare la lingua <strong><?=$LANGUAGES[$o_lang]?></strong>.
													</label>
												</div>
											</div> <!-- /.control-group -->
										<?php endif ?>
										
									</div>
								</div>
							</div> <!-- /.accordion-group -->
					<?php endforeach ?>
					
				</div> <!-- /#title_accordion -->

				<div class="control-group">
					<label class="control-label" for="offer_special">Offerta Speciale</label>
					<div class="controls">
						<label class="checkbox">
							<input id="offer_special" type="checkbox" name="offer_special" value="1" <?if($offer->offer_special):?>checked<?endif;?>>
							Seleziona questa opzione se vuoi che l'offerta abbia l'indicazione "Offerta Speciale".
						</label>
					</div>
				</div>

				<!-- <div class="control-group">
					<label class="control-label" for="expires">Con Scadenza</label>
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" name="expires" id="expires" value="1" <?if($offer->offer_expire):?>checked<?endif;?> >
							Seleziona questa opzione se desideri che l'offerta abbia una data di scadenza dopo la quale il sistema la disattiver&agrave; in automatico.
						</label>
					</div>
				</div> -->

				<div id="expires" class="control-group success <?php if(form_error('offer_expire')) echo "error";?>">
					<label class="control-label" for="offer_expire">Scadenza<br><em>(Opzionale)</em></label>
					<div class="controls">
						<?php if(set_value('offer_expire')): ?>
							<input type="text" class="input-xlarge focused" name="offer_expire" id="offer_expire" placeholder="GG-MM-AAA"  value="<?=set_value('offer_expire')?>" >
						<?php else: ?>
							<input type="text" class="input-xlarge focused" name="offer_expire" id="offer_expire" placeholder="GG-MM-AAA"  value="<?=$offer->offer_expire?>" >
						<?php endif ?>
						<span class="help-inline">
							Compila questa casella se desideri che l'offerta abbia una data di scadenza dopo la quale il sistema la disattiver&agrave; in automatico. (Utilizza il formato "GG-MM-AA")
						</span>
					</div>
				</div>

			</fieldset>
			<fieldset>

				<?if($offer->offer_image):?>
					<div class="control-group">
						<label class="control-label" for="offer_image">Immagine Corrente</label>
						<div class="controls">
							<img src="/public/upload/<?=$offer->offer_image?>" class="offer-old-image">
						</div>
					</div>
				<? endif; ?>
				
				<div class="control-group success">
					<label class="control-label" for="offer_image">Cambia Immagine<br><em>(Opzionale)</em></label>
					<div class="controls">
						<input class="input-file" id="offer_image" name="offer_image" type="file">
						<span class="help-inline">Seleziona e carica un immagine relativa all'offerta. <em>(Massimo: <?php echo(UPLOAD_MAX_SIZE / 1024)?>MB)</em></span>
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				
				<div class="accordion" id="translate_accordion">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#translate_accordion" href="#title_lang_new">Aggiungi Traduzione</a>
						</div>
						<div id="title_lang_new" class="accordion-body collapse">
							<div class="accordion-inner">
								<div class="control-group">
									<label class="control-label" for="new_lang">Nuova Lingua:</label>
									<div class="controls">									
										<select name="new_lang" id="new_lang">
											<option disabled selected>-Seleziona-</option>
											<?php foreach ($lang_diff as $alang => $name): ?>
												<option value="<?=$alang?>"><?=$name?></option>
											<?php endforeach ?>
										</select>
										&nbsp;<a href="#" class="btn" id="add_lang">Aggiungi</a>
										<span class="help-inline">Seleziona la lingua da aggiungere, se nessuna lingua è visibile nel menu non è possibile aggiungere altre traduzioni. <em>Le lingue disponibili sono: <?=implode($LANGUAGES, ", ")?></em></span>
									</div>
								</div> <!-- .control-group -->
							</div>
						</div>
					</div> <!-- /.accordion-group -->
	
				</div> <!-- /#translate_accordion -->
				
				<button type="submit" class="btn btn-primary">Salva modifiche</button>
				<a href="/admin/offers.html" class="btn">Annulla</a>
			</div>
		</form> 
	
	
	</div>
	
	
</div><!-- /content -->

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