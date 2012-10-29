<script type="text/javascript">
/* <![CDATA[ */
// window.location.hash.replace("#", "")

$(document).ready(function(){
	$("td a").tooltip();
	$(".modal").modal({'show':false});
	$( 'textarea.editor' ).ckeditor();
	

	// Se e' stato inserito un hash nell'url coltrolliamo se esiste tra le tabs ed in caso lo selezioniamo.
	if(window.location.hash.length > 0 && $(window.location.hash+"-selector").length > 0)
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
	

	$.fn.dialog2.defaults.autoAddCancelButton = false;
	$(".modal-ajax-trigger").click(function(event) {
		event.preventDefault();
        
		var id = $(this).attr("data-id");
		var mTitle = $(this).attr("modal-title");
		var href = $(this).attr("href");

        $('<div/>').dialog2({
            title: mTitle, 
            content: href, 
            id: "modal-ajax-landing-data",
			showCloseHandle: false,
            removeOnClose: true, 
        	closeOnOverlayClick: true,
			showCloseHandle: false,
			initialLoadText: "Caricamento in corso...",
        }).dialog2("removeButton", "Cancel");
    });
	
});
/* ]]> */

</script>

<div class="content clearfix">
	<section id="tabs" class="tabs-newsletter">
		<div class="newsletter-header span8 offset2">
			<h1>Gestisci la newsletter<br><small>Crea, Salva ed Invia news ai tuoi clienti.</small></h1>
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

		<div id="newsletter-tabs-wrapper" class="tabbable tabs-left span12">
			<ul id="tab" class="nav nav-tabs span2">
				<?php if(isset($draft_news)): ?>
					<li id="newsletter-draft-selector" class="active"><a href="#newsletter-draft" data-toggle="tab">News non inviate</a></li>
					<li id="newsletter-list-selector"><a href="#newsletter-list" data-toggle="tab">News Inviate</a></li>
				<?php else: ?>
					<li id="newsletter-list-selector" class="active"><a href="#newsletter-list" data-toggle="tab">News Inviate</a></li>
				<? endif; ?>
				<li id="newsletter-add-selector"><a href="#newsletter-add" data-toggle="tab">Crea News</a></li>
				<li id="subscribers-list-selector"><a href="#subscribers-list" data-toggle="tab">Utenti Iscritti</a></li>
				<li id="subscribers-add-selector"><a href="#subscribers-add" data-toggle="tab">Aggiungi Utente</a></li>
				<!-- <li id="newsletter-settings-selector"><a href="#newsletter-settings" data-toggle="tab">Impostazioni</a></li> -->
				<li id="newsletter-descr-selector"><a href="#newsletter-descr" data-toggle="tab">Maggiorni Informazioni</a></li>
			</ul>


			<div id="tab-content" class="span10 tab-content">
				<?php if (isset($draft_news)): ?>
					<div class="tab-pane fade in active" id="newsletter-draft">
						<h3>News non inviate.</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="id">ID News</th>
									<th class="insert">Inserita</th>
									<th class="subject">Oggetto eMail</th>
									<th colspan="4"></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($draft_news->data as $news): ?>
									<tr id="news-<?=$news->id;?>">
										<td><?=$news->id;?></td>
										<td><?=$news->create_time?></td>
										<td class="subject"><?=$news->subject?></td>

										<td class="info"><a class="modal-ajax-trigger" modal-title="Visualizza News" href="<?=base_url("/admin/newsletter/{$news->id}/show")?>" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
										<td class="test"><a class="modal-ajax-trigger" modal-title="Invia Test" href="<?=base_url("/admin/newsletter/{$news->id}/test")?>" rel="tooltip" data-original-title="Invia Test">&nbsp;</a></td>
										<td class="edit"><a class="modal-ajax-trigger" modal-title="Modifica News" href="<?=base_url("/admin/newsletter/{$news->id}/edit")?>" rel="tooltip" data-original-title="Modifica">&nbsp;</a></td>

										<td class="delete"><a data-toggle="modal" href="#modal-news-<?=$news->id;?>" rel="tooltip" data-original-title="Elimina">&nbsp;</a></td>


										<div class="modal fade modal-delete" id="modal-news-<?=$news->id;?>">
											<div class="modal-header">
												<a class="close" data-dismiss="modal">×</a>
												<h3>Attenzione!</h3>
											</div>
											<div class="modal-body">
												Si sicuro di voler eliminare la news: "<?=$news->title?>"?<br> Nota che non sar&agrave; possibile annullare l'operazione!
											</div>
											<div class="modal-footer">
												<a data-dismiss="modal" href="#" class="btn btn-primary">Annulla</a>
												<a href="<?=base_url("/admin/newsletter/{$news->id}/delete")?>" class="btn">Elimina</a>
											</div>
										</div>

									</tr>
								<? endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif ?>
				
		
				<div class="tab-pane fade <?php if( ! isset($draft_news)) echo "in active" ?>" id="newsletter-list">
					<h3>News inviate ai tuoi clienti.</h3>
					<?php if ($sent_news->total > 0): ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="id">ID News</th>
									<th class="subject">Oggetto eMail</th>
									<th class="sent">Inviata</th>
									<th class="sent-messages">Utenti</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($sent_news->data as $news): ?>
									<tr id="news-<?=$news->id;?>">
										<td><?=$news->id;?></td>
										<td class="subject"><?=$news->subject?></td>
										<td><?=$news->send_time?></td>
										<td><?=$news->emails_sent?></td>
										<td class="info"><a class="modal-ajax-trigger" modal-title="Visualizza News" href="<?=base_url("/admin/newsletter/{$news->id}/show")?>" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
									</tr>
								<? endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<span class="no_offers">Non hai ancora inviato nessuna news!</span>
					<?php endif ?>
				</div>
				
				
				<div class="tab-pane fade" id="subscribers-list">
					<h3>Utenti iscritti alla tua newsleter.</h3>
					<?php if (count($subscribers)>0): ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="id">#</th>
									<th class="email">eMail</th>
									<th class="since">Iscrizione</th>
									<th colspan="2"></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($subscribers as $key=>$sub_user): ?>
									<tr>
										<td><?=$key;?></td>
										<td class="email"><?=$sub_user->email?></td>
										<td class="since"><?=$sub_user->timestamp?></td>
										<td class="info"><a class="modal-ajax-trigger" modal-title="Visualizza News" href="<?=base_url("/admin/newsletter/user/{$sub_user->email}/show")?>" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
										<!-- <td class="edit"><a href="<?=base_url("/admin/newsletter/user/{$sub_user->email}/edit")?>" rel="tooltip" data-original-title="Modifica">&nbsp;</a></td> -->
										<td class="delete"><a data-toggle="modal" href="#modal-user-remove-<?=$key;?>" rel="tooltip" data-original-title="Elimina">&nbsp;</a></td>


										<div class="modal fade modal-delete" id="modal-user-remove-<?=$key;?>">
											<div class="modal-header">
												<a class="close" data-dismiss="modal">×</a>
												<h3>Attenzione!</h3>
											</div>
											<div class="modal-body">
												Si sicuro di voler eliminare l'utente: "<?=$sub_user->email?>"?<br> Nota che non sar&agrave; possibile annullare l'operazione!
											</div>
											<div class="modal-footer">
												<a data-dismiss="modal" href="#" class="btn btn-primary">Annulla</a>
												<a href="<?=base_url("/admin/newsletter/user/{$sub_user->email}/delete")?>" class="btn">Elimina</a>
											</div>
										</div>

									</tr>
								<? endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<span class="no_offers">Nessun utente si è ancora iscritto alla tua newsletter!</span>
					<?php endif ?>
				</div>
				
				<div class="tab-pane fade" id="newsletter-add">
					<?=form_open_multipart("admin/newsletter/create", array('class' => "form-horizontal"), $form_hidden);?>
						<fieldset>
							<legend>Crea una news</legend>

							<?php if (validation_errors()): ?>
								<div class="alert alert-error">
									<h4 class="alert-heading">Attenzione!</h4>
									<?=validation_errors()?>
								</div>
							<?php endif ?>
						</fieldset>	

						<fieldset>
							<div class="control-group <? if(form_error('subject')) echo "error"?>">
								<label class="control-label" for="subject">Oggetto</label>
								<div class="controls">
									<input class="input-xlarge focused" id="subject" name="subject" size="50" maxlength="100" value="<?=set_value('subject')?>" placeholder="Oggetto visualizzato nella eMail">
								</div>
							</div>

							<div class="control-group <? if(form_error('from_email')) echo "error"?>">
								<label class="control-label" for="from_email">eMail Mittente</label>
								<div class="controls">
									<input class="input-xlarge focused" type="email" id="from_email" name="from_email" size="50" maxlength="100" value="<?=set_value('from_email', $user['email'])?>" placeholder="Indirizzo eMail valido">
									<span class="help-inline">Questo indirizzo mail sar&agrave; visualizzato come mittente.</span>
								</div>
							</div>


							<div id="expires" class="control-group <? if(form_error('from_name')) echo "error"?>">
								<label class="control-label" for="from_name">Nome Mittente</label>
								<div class="controls">
									<input class="input-xlarge focused" name="from_name" id="from_name" placeholder="Nome Visualizzato" value="<?=set_value('from_name')?>">
									<span class="help-inline">La news inviata avr&agrave; questo nome come mittente.</span>
								</div>
							</div>


							<div id="expires" class="control-group <? if(form_error('to_name')) echo "error"?>">
								<label class="control-label" for="to_name">Nome Destinatario</label>
								<div class="controls">
									<input class="input-xlarge focused" name="to_name" id="to_name" placeholder="Nome Visualizzato" value="<?=set_value('to_name')?>">
									<span class="help-inline">Il lettore della mail vedr&agrave; questo nome come destinatario.</span>
								</div>
							</div>

						</fieldset>
						<fieldset>

							<div class="control-group  <? if(form_error('news_body')) echo "error"?>">
								<label class="control-label" for="news_body">Contenuto della News</label>
								<div class="controls">
									<textarea class="input-xlarge editor" rows="5" id="news_body-<?=md5(rand())?>" name="news_body"><?=set_value('news_body')?></textarea>
									<span class="help-inline">Inserisci il contenuto della tua news.</span>
								</div>
							</div>
						</fieldset>

						<div class="form-actions">
							<input type="submit" class="btn btn-primary" name="action" value="Salva Bozza"/>
							<input type="submit" class="btn btn-success" name="action" value="Invia Subito"/>
							<button type="reset" class="btn">Cancella Form</button>
							<?=anchor('admin/main', "Annulla", array('class'=>'btn'));?>
						</div>
					</form>
				</div> <!-- /#newsletter-add -->
				
				
				<div class="tab-pane fade" id="subscribers-add">
					<?=form_open_multipart("admin/newsletter/user/new/add", array('class' => "form-horizontal"), $form_hidden);?>
						<fieldset>
							<legend>Aggiungi un Utente</legend>
							<p>Nota che l'utente dovrà accettare manualmente la sottoscrizione cliccando sul bottone  prima che esso possa ricevere le tue newsletter.</p>

							<?php if (validation_errors()): ?>
								<div class="alert alert-error">
									<h4 class="alert-heading">Attenzione!</h4>
									<?=validation_errors()?>
								</div>
							<?php endif ?>
						</fieldset>	

						<fieldset>
							<div class="control-group <? if(form_error('user_email')) echo "error"?>">
								<label class="control-label" for="user_email">Indirizzo eMail</label>
								<div class="controls">
									<input class="input-xlarge focused" type="email" id="user_email" name="user_email" size="50" value="<?=set_value('user_email')?>" placeholder="Indirizzo eMail dell'Utente">
								</div>
							</div>

							<div class="control-group <? if(form_error('user_name')) echo "error"?>">
								<label class="control-label" for="user_name">Nome</label>
								<div class="controls">
									<input class="input-xlarge focused" type="text" id="user_name" name="user_name" size="50" value="<?=set_value('user_name')?>" placeholder="Nome dell'Utente">
								</div>
							</div>


							<div id="expires" class="control-group <? if(form_error('user_surname')) echo "error"?>">
								<label class="control-label" for="user_surname">Cognome</label>
								<div class="controls">
									<input class="input-xlarge focused" type="text" name="user_surname" id="user_surname" value="<?=set_value('user_surname')?>" placeholder="Cognome dell'Utente">
								</div>
							</div>

						</fieldset>

						<div class="form-actions">
							<input type="submit" class="btn btn-success" name="action" value="Aggiungi"/>
							<button type="reset" class="btn">Cancella Form</button>
							<?=anchor('admin/main', "Annulla", array('class'=>'btn'));?>
						</div>
					</form>
				</div>				
				
				
				<!-- <div class="tab-pane fade" id="newsletter-settings"><em>@TODO: Impostare immagine per "speciale", altro?</em></div> -->
				<div class="tab-pane fade" id="newsletter-descr">
					<p class="dark">Il servizio Newsletter è il modo più semplice e veloce per comunicare via e-mail a tutti i tuoi clienti iscritti le novità riguardanti la tua attività.
						Scopri com'è semplice ed intuitivo con l'editor di testo integrato con il quale potrai personalizzare i tuoi messaggi!</p>
				</div>
				
				
			</div>

		</div>

	</section>
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