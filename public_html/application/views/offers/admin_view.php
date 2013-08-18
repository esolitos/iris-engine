<? global $LANGUAGES ?>
<!-- <script src="/public/bootstrap/js/bootstrap-tab.js" type="text/javascript" charset="utf-8"></script> -->
<!-- <script src="/public/bootstrap/js/bootstrap-transition.js" type="text/javascript" charset="utf-8"></script> -->
<!-- <script src="/public/bootstrap/js/bootstrap-tooltip.js" type="text/javascript" charset="utf-8"></script> -->
<!-- <script src="/public/bootstrap/js/bootstrap-modal.js" type="text/javascript" charset="utf-8"></script> -->

<script src="/public/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">

<script type="text/javascript">
/* <![CDATA[ */
// window.location.hash.replace("#", "")

$(document).ready(function(){
	// $( "#add_offer_expire" ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#add_offer_expire" ).datepicker({ dateFormat: 'dd-mm-yy' });
	$("#offer-list td a").tooltip();
	$(".modal").modal({'show':false});

	// Se e' stato inserito un hash nell'url coltrolliamo se esiste tra le tabs ed in caso lo selezioniamo.
	if( $(window.location.hash+"-selector").length > 0 )
	{
		// Rimuovo la selezione al elemento di default
		$("#tab-content div:first-child").removeClass(" in active");
		$("#tab li:first-child").removeClass("active");

		// Attivo la selezione all'elemento dell'hash
		$(window.location.hash).addClass(" in active");
		$(window.location.hash+"-selector").addClass("active");

		// Rimuovo il jump all'elemento.
		$('html, body').animate({scrollTop:0});	
	}
	
	// Add a new translation on request.
	$("#add_lang").click( function(clicked) {
		clicked.preventDefault();
		var nlang = $("#new_lang").val();
		if ($("#title_lang_"+nlang).length )
		{
			$("#title_lang_"+nlang+" input, #title_lang_"+nlang+" textarea").attr('disabled', false)
			$("#title_lang_"+nlang).parents(".accordion-group").removeClass("in hidden");
		
			$("#new_lang option[value="+nlang+"]").attr('disabled', true).attr('selected', false);
			$("html, body").animate({ scrollTop: $("#offer-add").offset().top-180 }, 'slow');	
		}
	} );
	
	$("a.remove_lang").click( function(clicked){
		clicked.preventDefault();
		var rlang = $(this).data("lang");
		
		$("#title_lang_"+rlang).attr('disabled', true).parents(".accordion-group").addClass("hidden");
		
		$("#new_lang option[value="+rlang+"]").attr('disabled', false);
	} );
	
});
/* ]]> */

</script>

<div class="content clearfix">
	<section id="tabs" class="tabs-offer">
		<div class="offer-header span8 offset2">
			<h1>Gestisci le Offerte <br><small>Aggiungi, modifica ed elimina le tue offerte.</small></h1>
			<?if($error):?>
				<div class="alert alert-error">
					<span class="close" data-dismiss="alert">×</span>
					<h4 class="alert-heading">Attenzione!</h4>
					<?=$error?>
				</div>
			<?endif;?>
			<?if($message):?>
				<div class="alert alert-info">
					<span class="close" data-dismiss="alert">×</span>
					<h4 class="alert-heading">Informazione:</h4>
					<?=$message?>
				</div>
			<?endif;?>
		</div>

		<div id="offer-tabs-wrapper" class="tabbable tabs-left span12">
			<ul id="tab" class="nav nav-tabs span2">
				<?php if (isset($offers) AND count($offers)>0): ?>
					<li id="offer-list-selector" class="<?php if( empty($active_tab) OR $active_tab == "offer-list") echo "active" ?>"><a href="#offer-list" data-toggle="tab">Offerte Inserite</a></li>
					<li id="offer-add-selector" class="<?php if( $active_tab == "offer-add") echo "active" ?>"><a href="#offer-add" data-toggle="tab">Nuova Offerta</a></li>
				<?php else: ?>
					<li id="offer-add-selector" class="<?php if( $active_tab == "offer-add") echo "active" ?>"><a href="#offer-add" data-toggle="tab">Nuova Offerta</a></li>
				<?php endif; ?>

				<li id="offer-settings-selector" class="<?php if( $active_tab == "offer-settings") echo "active" ?>"><a href="#offer-settings" data-toggle="tab">Impostazioni</a></li>
				<li id="offer-descr-selector" class="<?php if( $active_tab == "offer-descr") echo "active" ?>"><a href="#offer-descr" data-toggle="tab">Maggiorni Informazioni</a></li>
				<li id="service-html-code-selector" class="<?php if( $active_tab == "service-html-code") echo "active" ?>"><a href="#service-html-code" data-toggle="tab">Codice Servizio</a></li>
			</ul> <!-- #tab -->

			<div id="tab-content" class="span10 tab-content">
				<?php if (isset($offers) AND count($offers)>0): ?>
					<div class="tab-pane fade <?php if( empty($active_tab) OR $active_tab == "offer-list") echo "in active" ?>" id="offer-list">
						<h3>Offerte gi&agrave; inserite e presenti nel sistema.</h3>
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="id">#</th>
										<th class="special">Speciale</th>
										<th class="title">Titolo</th>
										<th  class="insert">Inserita</th>
										<!-- <th class="start">Inizio</th> -->
										<th class="end">Fine</th>
										<th class="actions" colspan="2"></th>
									</tr>
								</thead>
								<tbody>
									<? foreach($offers as $o_id=>$offer): ?>
										<tr id="offer-<?=$o_id;?>">
											<td><?=$o_id;?></td>
											<td class="<? if($offer->offer_special) echo "offer-special-yes"?>"></td>
											<td class="title"><?=$offer->offer_title?></td>
											<td><?=$offer->offer_creation?></td>
											<!-- <td><?//$offer->offer_start ?></td> -->
											<td><?=$offer->offer_expire?></td>
											<td class="edit"><a href="/admin/offers/edit/<?=$offer->id?>" rel="tooltip" data-original-title="Modifica">&nbsp;</a></td>
											<td class="delete"><a data-toggle="modal" href="#modal-offer-<?=$o_id;?>" rel="tooltip" data-original-title="Elimina">&nbsp;</a></td>


											<div class="modal fade modal-delete" id="modal-offer-<?=$o_id;?>">
												<div class="modal-header">
													<a class="close" data-dismiss="modal">×</a>
													<h3>Attenzione!</h3>
												</div>
												<div class="modal-body">
													Si sicuro di voler eliminare l'offerta: "<?=$offer->offer_title?>"?<br> Nota che non sar&agrave; possibile annullare l'operazione!
												</div>
												<div class="modal-footer">
													<a data-dismiss="modal" href="#" class="btn btn-primary">Annulla</a>
													<a href="/admin/offers/delete/<?=$offer->id?>" class="btn">Elimina</a>
												</div>
											</div>

										</tr>
									<? endforeach; ?>
								</tbody>
							</table>
					</div> <!-- #offer-list -->
				<?php endif; //Is set Offers AND count($offers)>0 ?>


				<div class="tab-pane fade <?php if( $active_tab == "offer-add" OR empty($offers)) echo "in active" ?>" id="offer-add">
					<?php echo form_open_multipart('admin/offers/add', array('class' => 'form-horizontal', 'id' => 'form-add-offer'), $form_add_hidden); ?>
						<fieldset>
							<legend>Aggiungi un Offerta</legend>

							<?php if (validation_errors()): ?>
								<div class="alert alert-error">
									<h4 class="alert-heading">Attenzione!</h4>
									<?=validation_errors()?>
								</div>
							<?php endif ?>
						</fieldset>	

						<fieldset>
							<!-- Old Single-Language Title -->
							<!-- <div class="control-group <? if(form_error('offer_title')) echo "error"?>">
								<label class="control-label" for="offer_title">Titolo</label>
								<div class="controls">
									<input class="input-xlarge focused" id="offer_title" name="offer_title" size="50" maxlength="100" value="<?=set_value('offer_title')?>" placeholder="">
								</div>
							</div> -->
							<!-- // Old Single-Language Title -->
							
								<div class="accordion" id="title_accordion">
									<?php foreach ($LANGUAGES as $o_lang=>$o_name): ?>
										<?php $if_hidden = ( in_array($o_lang, $used_langs) OR $o_lang == LANG_DEFAULT ) ? FALSE : 'hidden' ;  ?>
										
											<div class="accordion-group <?=$if_hidden?>">
												<div class="accordion-heading">
													<?php if($o_lang != LANG_DEFAULT): ?>
														<a class="remove_lang btn btn-mini btn-warning" href="#" data-lang="<?=$o_lang?>"> Elimina</a>
													<?php endif ?>
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#title_accordion" href="#title_lang_<?=$o_lang?>"  data-lang="<?=$o_lang?>"><span class="lang-name"><?=$o_name?></span></a>
												</div>
												<div id="title_lang_<?=$o_lang?>" class="accordion-body collapse <?if($o_lang == LANG_DEFAULT) echo "in"?>">
													<div class="accordion-inner">

														<div class="control-group  <?php if(form_error('offer_title['.$o_lang.']')) echo "error";?>">
															<label class="control-label" for="offer_title">Titolo Offerta</label>
															<div class="controls">
																	<input <?if($if_hidden) echo "disabled"?> type="text" class="input-xlarge focused" name="offer_title[<?=$o_lang?>]" size="50" maxlength="100" value="<?=set_value("offer_title[{$o_lang}]")?>">
																<span class="help-inline">Inserisci il titolo (In <?=$o_name?>) che verr&agrave; visualizzato nella pagina delle offerte.</span>
															</div>	
														</div> <!-- /.control-group -->
														
														
														<div class="control-group  <?php if(form_error('offer_body['.$o_lang.']')) echo "error";?>">
															<label class="control-label" for="offer_title">Testo Offerta</label>
															<div class="controls">
																	<textarea <?if($if_hidden) echo "disabled"?> class="input-xlarge" rows="5" id="offer_body[<?=$o_lang?>]" name="offer_body[<?=$o_lang?>]"><?=set_value('offer_body['.$o_lang.']')?></textarea>
																<span class="help-inline">Inserisci il testo (In <?=$o_name?>) che verr&agrave; visualizzato nell'offerta.</span>
															</div>
														</div> <!-- /.control-group -->
														
													</div>
												</div>
											</div> <!-- /.accordion-group -->
									<?php endforeach ?>
					
								</div> <!-- /#title_accordion -->
							

							<div class="control-group <? if(form_error('offer_special')) echo "error"?>">
								<label class="control-label" for="offer_special">Offerta Speciale</label>
								<div class="controls">
									<label class="checkbox">
										<input id="offer_special" type="checkbox" name="offer_special" <?if(set_value('offer_special')) echo "checked";?> value="1">
										Seleziona questa opzione se vuoi che l'offerta abbia l'indicazione "Offerta Speciale".
									</label>
								</div>
							</div>

							<!-- <div id="offer_start" class="control-group">
							<label class="control-label" for="offer_start">Inizio</label>
							<div class="controls">
							<input class="input-xlarge focused" name="offer_start" id="add_offer_start" placeholder="YYYY-MM-GG" value="<?=set_value('offer_start')?>">
							<span class="help-inline">Seleziona la data di inizio. Lascia in bianco per inizio immediato. (Formato "YYYY-MM-GG") </span>
							</div>
							</div> -->

							<!-- <div class="control-group <? if(form_error('expires')) echo "error"?>">
								<label class="control-label" for="add_expires">Con Scadenza</label>
								<div class="controls">
									<label class="checkbox">
										<input type="checkbox" id="add_expires" name="expires" <?if(set_value('expires')) echo "checked";?>  value="1">
										Seleziona questa opzione se desideri che l'offerta abbia una data di scadenza dopo la quale il sistema la disattiver&agrave; in automatico.
									</label>
								</div>
							</div> -->

							<div id="expires" class="control-group <? if(form_error('offer_expire')) echo "error"?>">
								<label class="control-label" for="offer_expire">Scadenza</label>
								<div class="controls">
									<input class="input-xlarge focused" name="offer_expire" id="add_offer_expire" placeholder="GG-MM-AAAA" value="<?=set_value('offer_expire')?>">
									<span class="help-inline">Seleziona la data di scadenza. (Utilizza il formato "GG-MM-AAAA") </span>
								</div>
							</div>

						</fieldset>
						<fieldset>

							<!-- Old Single-Language Body -->
							<!-- <div class="control-group  <? if(form_error('offer_body')) echo "error"?>">
								<label class="control-label" for="textarea">Testo</label>
								<div class="controls">
									<textarea class="input-xlarge" rows="5" id="offer_body" name="offer_body"><?=set_value('offer_body')?></textarea>
									<span class="help-inline">Inserisci il testo che verr&agrave; visualizzato nella pagina delle offerte.</span>
								</div>
							</div> -->

							<div class="control-group">
								<label class="control-label" for="offer_image">Immagine Offerta</label>
								<div class="controls">
									<input class="input-file" id="offer_image" name="offer_image" type="file">
									<span class="help-inline"><em>(Opzionale)</em> Seleziona e carica un immagine relativa all'offerta. <em>(Massimo: <?php echo(UPLOAD_MAX_SIZE / 1024)?>MB)</em></span>
								</div>
							</div>
							
							<div class="control-group <? if(form_error('send_message')) echo "error"?>">
								<label class="control-label" for="send_message">Avvisa Clienti</label>
								<div class="controls">
									<label class="checkbox">
										<input type="checkbox" name="send_message" value="SEND">
										<em>(Opzionale)</em> Avvisa tramite mail i clienti di quest'offerta.
									</label>
									<span class="help-inline"><i class="icon-info-sign"></i> Solo se hai sottoscritto il servizio di Newsletter.</span>
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
							
							<button type="submit" class="btn btn-primary">Salva Offerta</button>
							<button type="reset" class="btn">Cancella Dati</button>
							<?=anchor('admin/main', "Annulla", array('class'=>'btn'));?>
						</div>
					</form> <!-- /admin/offers/add -->

				</div>

				<div class="tab-pane fade <?php if( $active_tab == "offer-settings") echo "in active" ?>" id="offer-settings">
					<h2>Impostazioni Generali</h2>
					<div class="well">
						<h3>Colori</h3>
						<p>In questa sezione puoi impostare i colori di alcuni elementi del servizio.</p>
						<?=
							form_open(
								'admin/settings/color/'.SERVICE_ID_OFFERS,
								array('class' => 'form-inline', 'id' => 'form-edit-styles'),
								array('from'=>"admin/offers")
								);
						?>
						<div style="display:none">
							<script src="<?=base_url("/public/js/jquery.colorpicker.js")?>" type="text/javascript" charset="utf-8"></script>
							<link rel="stylesheet" href="<?=base_url("/public/css/jquery.colorpicker.css")?>" type="text/css" media="screen" charset="utf-8">
							<script type="text/javascript" charset="utf-8">
							$().ready(function(){
								$('input.color-preview').ColorPicker({
									position: 'top',
									onBeforeShow: function () {
										$(this).ColorPickerSetColor(this.value);
									},
									onShow: function (colpkr) {
										$(colpkr).fadeIn(500);
										return false;
									},
									onHide: function (colpkr, el) {
										$(colpkr).fadeOut(500);
										return false;
									},
									onChange: function (hsb, hex, rgb, el) {										
										target = $(el).attr('data-target');
										prop = $(el).attr('data-prop');
										$("."+target).css(prop, "#"+hex);
										$(el).val(hex);
										
										// $("#debug-value").html( target +" "+ prop );
									},
									onSubmit: function(hsb, hex, rgb, el) {
										$(el).val(hex);
										$(el).ColorPickerHide();
									},
								});
							});
							</script>
						</div>
						<div class="row-fluid">
							<div class="control-group span4">
								<label class="control-label" for="text_color">Testo</label>
								<div class="controls">
									<input type="text" class="input-small color-preview" data-target="offer" data-prop="color" id="text_color" name="text_color" value="<?=$custom_style['text_color']?>">
								</div>
							</div>
							<div class="control-group span4">
								<label class="control-label" for="title_color">Titolo</label>
								<div class="controls">
									<input type="text" class="input-small color-preview" data-target="offer_title" data-prop="color" id="title_color" name="title_color" value="<?=$custom_style['title_color']?>">
								</div>
							</div>
							<div class="control-group span4">
								<label class="control-label" for="bg_color">Sfondo</label>
								<div class="controls">
									<input type="text" class="input-small color-preview" data-target="offer" data-prop="background-color" id="bg_color" name="bg_color" value="<?=$custom_style['bg_color']?>">
								</div>
							</div>

						</div>
						<div class="form-actions">
							<a href="#" class="toggle-example btn">Mostra/Nascondi Esempio</a>
							<input type="reset" value="Cancella" class="btn btn-warning">&nbsp;
							<input type="submit" name="submit" value="Salva i Colori" class="btn btn-primary">
						</div>
						</form>
						<div id="offer-example-wrapper">							
							<style type="text/css" media="screen">
								.offer.example {
									color:#<?=$custom_style['text_color']?>;
									background-color:#<?=$custom_style['bg_color']?>;;
								}
								.offer.example .offer_title {
									color:#<?=$custom_style['title_color']?>;
								}
								
							</style>
							
							<div class="offer example">
								<h2 class="offer_title">Titolo Offerta</h2>
								<span class="offer_creation"><em>12-03-2012</em></span>
								<p class="offer_body clearfix">
									<img src="http://placehold.it/310x160.png/bd93bd/ffffff&amp;text=placeholder" style="display:block;float:right;margin-left:10px;width:50%">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
									Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br>
									Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
									Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
								</p>
								Scadenza: <span class="offer_expire"><em>23-09-2012</em></span>
							</div>
						</div>
					</div>
					<div class="well">
						<h3>Foglio di Stile</h3>
						<p>
							IRIS Login ti fornisce uno stile predefinito che si adatta a quello del tuo sito. In questo pannello puoi modificarne i colori a tuo piacimento.<br>
							<strong>Attenzione</strong>: ti consigliamo di verificare sempre l'effetto che le modifiche che apporterai avranno sul tuo sito. <br>
							Le modifiche <em>si applicheranno a tutte le inserzioni</em> che hai inserito attraverso il modulo offerte, ma non ad altre parti sito.<br>
						</p>
						<?php echo form_open_multipart('admin/settings/style/'.SERVICE_ID_OFFERS,
														array('class' => 'form-inline', 'id' => 'form-add-style'),
														array('from'=>"admin/offers")); ?>
							<input type="submit" name="submit" value="Carica nuovo File" class="btn btn-success">
							<input class="input-file" id="css_file" name="css_file" type="file"/>
							<?php if (isset($css_file) AND $css_file != STYLE_DEFAULT_FILE): ?>
									<a class="btn btn-primary" href="<?=base_url($css_file)?>" target="_BLANK">Visualizza/Scarica</a>&nbsp;
									<a class="btn btn-warning" href="settings/style/<?=SERVICE_ID_OFFERS?>/remove?from=admin/offers">Elimina CSS</a>
							<?php endif ?>
						</form>
					</div>
				</div>
				
				
				<div class="tab-pane fade <?php if( $active_tab == "offer-descr") echo "in active" ?>" id="offer-descr">
					<p class="dark">Con il servizio offerte potrai inserire nella pagina dedicata del tuo sito offerte e promozioni riguardanti i tuoi servizi e prodotti.
					Potrai deciderne la scadenza e se hai sottoscritto anche il servizio <em>Newsletter</em> avvisare in automatico i tuoi clienti che si sono registrati sul tuo sito dei nuovi inserimenti.</p>
				</div>
				
				<div class="tab-pane fade <?php if( $active_tab == "service-html-code") echo "in active" ?>" id="service-html-code">
					<h2>Codice per l'utilizzo del servizio 'Gallery'</h2>
					<p>Per utilizzare il servizio Gallery fornito da IrisLogin in una qualsiasi pagina è sufficiente utilizzare il seguente codice HTML.</p>

					<pre class="irislogin-service-code prettyprint ">
&lt;!-- IrisLogin Offers Code: Begin --&gt;
  &lt;iframe src="<?= base_url(SERVICE_NAME_OFFERS.'/'.$website['info']->website_id) ?>" width="550px" height=650px" frameborder="0" id="irislogin-offers"&gt;&lt;/iframe&gt;
&lt;!-- IrisLogin Offers Code: End --&gt;</pre>

				</div>

			</div> <!-- #tab-content -->
			
		</div> <!-- #offer-tabs-wrapper -->

	</section>
</div><!-- content -->


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