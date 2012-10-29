<script type="text/javascript">
/* <![CDATA[ */
// window.location.hash.replace("#", "")

$(document).ready(function(){
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
	
	
	$("td a").tooltip();
	
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
	<section id="tabs" class="tabs-booking">
		
		<div class="booking-header span8 offset2">
			<h1>Gestione Siti Web e Sottoscrizioni<br><small>Visualiza e gestisci i siti web e le sottoscrizioni di IrisLogin.</small></h1>
			<?if($error):?>
				<div class="alert alert-error"><?=$error?></div>
			<?endif;?>
			<?if($message):?>
				<div class="alert alert-info"><?=$message?></div>
			<?endif;?>
			<?php if (validation_errors()): ?>
				<div class="clearfix">
					<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>
				</div>
			<?php endif ?>
		</div>

		<div id="booking-tabs-wrapper" class="tabbable tabs-left span12">
			<ul id="tab" class="nav nav-tabs span2">
				<li id="website-list-selector" class="active"><a href="#website-list" data-toggle="tab">Siti Web</a></li>
				<li id="website-add-selector"><a href="#website-add" data-toggle="tab">Aggiungi Sito</a></li>
				<li id="website-user-add-selector"><a href="#website-user-add" data-toggle="tab">Aggiungi Utenti</a></li>
				<li id="subscr-list-selector"><a href="#subscr-list" data-toggle="tab">Sottoscrizioni</a></li>
				<li id="subscr-add-selector"><a href="#subscr-add" data-toggle="tab">Aggiungi Sottoscriz.</a></li>
			</ul>
			
			<div id="tab-content" class="span10 tab-content">
				
				<div class="tab-pane fade in active" id="website-list">
					<h3>Siti Web con Sottoscrizioni attive.</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nome</th>
								<th>eMail</th>
								<th>Utenti</th>
								<th>Logo</th>
								<th>Servizi Sottoscriti</th>
								<th colspan="2"></th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($websites) AND $websites['total'] > 0): ?>
								<?php foreach ($websites['data'] as $id=>$site): ?>
									<tr id="site-<?=$id?>">
										<td class="site-id"><?=$id?></td>
										<td class="site-name"><a href="<?=$site['info']->website_url?>" target="_BLANK"><?=$site['info']->website_name?></a></td>
										<td class="site-email"><?=$site['info']->website_email?></td>
										<td class="site-users"><?=count($site['users'])?></td>
										<td class="site-logo">
											<? if ($site['info']->website_logo) :?>
												<img src="<?=$site['info']->website_logo?>"/>
											<?else: ?>
												<em>Nessun Logo</em>
											<? endif; ?>
										</td>
										<td class="site-services"><?=implode(", ", array_keys($site['services'])) ?></td>
										<td class="edit"><a class="modal-ajax-trigger" modal-title="Gestisci Sito" href="<?=base_url("/admin/websites/{$id}/edit")?>" rel="tooltip" data-original-title="Gestisci">&nbsp;</a></td>
										<td class="delete"><a data-toggle="modal" href="#modal-offer-<?=$id;?>" rel="tooltip" data-original-title="Elimina">&nbsp;</a></td>


										<div class="modal fade modal-delete" id="modal-offer-<?=$id;?>">
											<div class="modal-header">
												<a class="close" data-dismiss="modal">×</a>
												<h3>Attenzione!</h3>
											</div>
											<div class="modal-body">
												Si sicuro di voler eliminare il sito: "<?=$site['info']->website_name?>"?<br> Nota che non sar&agrave; possibile annullare l'operazione!
											</div>
											<div class="modal-footer">
												<a data-dismiss="modal" href="#" class="btn btn-primary">Annulla</a>
												<a href="<?=base_url("/admin/websites/{$id}/remove")?>" class="btn">Elimina</a>
											</div>
										</div>
										
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="5">Nessun sito nel sistema.</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane fade" id="website-add">
					<h3>Aggiungi un Sito Web al sistema</h3>

					<?=form_open_multipart("admin/websites/add", array('class' => 'form-horizontal'))?>
						<fieldset>
							<div class="control-group <?php if(form_error('website_name')) echo "error";?>">
								<label class="control-label" for="website_id">Nome del Sito:</label>
								<div class="controls">
									<input class="input-xlarge" type="text" name="website_name" value="<?=set_value('website_name')?>" placeholder="Nome Visualizzato" >
								</div>
							</div>	

							<div class="control-group <?php if(form_error('website_url')) echo "error";?>">
								<label class="control-label" for="website_url">URL Sito:</label>
								<div class="controls">
									<input class="input-xlarge" type="text" name="website_url" value="<?=set_value('website_url')?>" placeholder="URL Homepage" >
								</div>
							</div>	

						<div class="control-group <?php if(form_error('website_logo')) echo "error";?>">
							<label class="control-label" for="website_logo">Logo:</label>
							<div class="controls">
								<input class="input-xlarge" type="file" name="website_logo" />
							</div>
						</div>
						</fieldset>
						<fieldset>
							<div class="form-actions">
								<input type="submit" name="submit" class="btn btn-primary" value="Aggiungi &rarr;"/>
							</div>
						</fieldset>
					</form>
				</div>
				
				<div class="tab-pane fade" id="website-user-add">
					<h3>Aggiungi un utente ad un Sito</h3>

					<?=form_open("admin/users/add", array('class' => 'form-horizontal'))?>
						<fieldset>
							<div class="control-group <?php if(form_error('user_website')) echo "error";?>">
								<label class="control-label" for="user_website">Sito:</label>
								<div class="controls">
									<select name="user_website">
										<option selected disabled>- Seleziona un Sito -</option>
										<?php foreach ($websites['data'] as $id => $value): ?>
											<option value="<?=$id?>">(<?=$id?>) <?=$value['info']->website_name?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>

							<div class="control-group <?php if(form_error('username')) echo "error";?>">
								<label class="control-label" for="username">Username:</label>
								<div class="controls">
									<input type="text" name="username" value="<?=set_value('username')?>" placeholder="Nome Utente">
								</div>
							</div>

							<div class="control-group <?php if(form_error('email')) echo "error";?>">
								<label class="control-label" for="email">Indirizzo eMail:</label>
								<div class="controls">
									<input type="text" name="email" value="<?=set_value('email')?>" placeholder="Indirizzo eMail">
								</div>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-actions">
								<input type="submit" name="submit" class="btn btn-primary" value="Aggiungi &rarr;"/>
							</div>
						</fieldset>
					</form>
				</div>

				<div class="tab-pane fade" id="subscr-list">
					<h3>Sottoscrizioni Correnti.</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>(ID) Sito</th>
								<th>(ID) Servizio</th>
								<th>Scadenza</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($websites) AND $websites['total'] > 0): ?>
								<?php foreach ($websites['data'] as $w_id=>$site): ?>
									<?php foreach ($site['services'] as $serv_name => $serv_data): ?>
										<tr id="subscr-<?=$w_id?>-<?=$serv_data->service_id?>" class="<?if($serv_data->expired) echo "expired"?>">
											<td class="subscr-website">(<?=$w_id?>) <?=$site['info']->website_name?></td>
											<td class="subscr-service">(<?=$serv_data->service_id?>) <?=$serv_name?></td>
											<td class="subscr-expire"><?=$serv_data->service_expire?></td>
											<td class="edit"><a class="modal-ajax-trigger" modal-title="Modifica Sottoscrizione" href="<?=base_url("admin/subscription/extend/{$w_id}/$serv_name")?>" rel="tooltip" data-original-title="Modifica">&nbsp;</a></td>
											
										</tr>
									<?php endforeach ?>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="8">Nessuna sottoscrizione attualmente attiva.</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>

				<div class="tab-pane fade" id="subscr-add">
					<h3>Aggiungi una sottoscrizione</h3>
					<?=form_open("admin/subscription/add", array('class' => 'form-horizontal'))?>
						<fieldset>
							<div class="control-group <?php if(form_error('website_id')) echo "error";?>">
								<label class="control-label" for="website_id">Sito:</label>
								<div class="controls">
									<select name="website_id">
										<option selected disabled>- Seleziona un Sito -</option>
										<?php foreach ($websites['data'] as $id => $value): ?>
											<option value="<?=$id?>">(<?=$id?>) <?=$value['info']->website_name?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>

							<div class="control-group <?php if(form_error('service_id')) echo "error";?>">
								<label class="control-label" for="service_id">Sito:</label>
								<div class="controls">
									<select name="service_id">
										<option selected disabled>- Seleziona un Servizio -</option>
										<?php foreach ($services as $service): ?>
											<option value="<?=$service->service_id?>">(<?=$service->service_id?>) <?=$service->service_name?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>

							<div class="control-group <?php if(form_error('subscr_expire')) echo "error";?>">
								<label class="control-label" for="subscr_expire">Scadenza:</label>
								<div class="controls">
									<input type="text" name="subscr_expire" value="<?=set_value('subscr_expire')?>" placeholder="YYYY-MM-DD">
								</div>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-actions">
								<input type="submit" name="submit" class="btn btn-primary" value="Aggiungi &rarr;"/>
							</div>
						</fieldset>
					</form>
				</div>


			</div> <!-- #tab-content -->
			
		</div> <!-- #booking-tabs-wrapper -->
			
	</section>
</div> <!-- content -->

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