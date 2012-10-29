<script src="/public/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="/public/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
<script src="/public/js/jquery.dialog2.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
	$("td *[rel=tooltip]").tooltip();
	
	$(".request-more-info").click(function(event) {
		$(".tooltip.fade.in").css("display", "none"); // Little Bugfix.
		
		var id = $(this).attr("data-id");
		
        $('<div/>').dialog2({
            title: "Dettagli Richiesta", 
            content: "/admin/reservations/ajax/"+id+".html", 
            id: "request-details",
			showCloseHandle: false,
            removeOnClose: true, 
        	closeOnOverlayClick: true,
			showCloseHandle: false,
        }).dialog2("removeButton", "Cancel");

        event.preventDefault();

		$("tr#request-"+id+" .request-seen").removeClass("new");
		// $("tr#new-request-"+id).detach().appendTo("#booking-list tbody");
		$("tr#new-request-"+id).remove();
		
		if($("#booking-list-new tbody tr").length <= 0)
		{
			$("#booking-list-new").remove();
			$("#booking-list-new-selector").remove();


			$("#booking-list").addClass(" in active");
			$("#booking-list-selector").addClass("active");
		}
		
    });


	$('.request-confirmed input').live('change', function(event){
		$(".tooltip.fade.in").css("display", "none"); // Little Bugfix.
		
		var element = $(this);
		var r_id = element.attr('request-id');
		element.parent("td").addClass('loading');
		
		if( $(this).is(":checked") ){
			$.ajax({
				type: "GET",
				url: "/admin/reservations/"+r_id+"/confirm.html"
				}).done(function( msg) {
					if(msg != 'TRUE'){
						element.attr('checked', false);
						alert("Azione non Riuscita!");
					}
					
					element.parent("td").removeClass('loading');
				});			
		}
		else {
			$.ajax({
				type: "GET",
				url: "/admin/reservations/"+r_id+"/unconfirm.html"
				}).done(function( msg) {
				 	if(msg != 'TRUE'){
						element.attr('checked', true);
						alert("Azione non Riuscita!");
					}
					
					element.parent("td").removeClass('loading');
				});
		}

	});

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
});
/* ]]> */

</script>
<div class="content clearfix">
	<section id="tabs" class="tabs-booking">
		
		<div class="booking-header span8 offset2">
			<h1>Gestisci le tue Prenotazioni<br><small>Visualiza le richieste di prenotazione organizzate per categorie.</small></h1>
			<?if($error):?>
			<div class="alert alert-error"><?=$error?></div>
			<?endif;?>
			<?if($message):?>
			<div class="alert alert-info"><?=$message?></div>
			<?endif;?>
		</div>

		<div id="booking-tabs-wrapper" class="tabbable tabs-left span12">
			<ul id="tab" class="nav nav-tabs span2">
				<?php if(isset($new_requests)): ?>
					<li id="booking-list-new-selector" class="active"><a href="#booking-list-new" data-toggle="tab">Nuove Richieste</a></li>
					<li id="booking-list-selector"><a href="#booking-list" data-toggle="tab">Richieste Correnti</a></li>
				<?php else: ?>
					<li id="booking-list-selector" class="active"><a href="#booking-list" data-toggle="tab">Tutte le Richieste</a></li>
				<? endif; ?>
				<li id="booking-list-old-selector"><a href="#booking-list-old" data-toggle="tab">Richieste Passate</a></li>
				<li id="booking-settings-selector"><a href="#booking-settings" data-toggle="tab">Impostazioni</a></li>
				<li id="booking-descr-selector"><a href="#booking-descr" data-toggle="tab">Maggiorni Informazioni</a></li>
			</ul>
			
			<div id="tab-content" class="span10 tab-content">
				
				<?php if(isset($new_requests)): ?>
					<div class="tab-pane fade in active" id="booking-list-new">
						<h3>Nuove richieste non ancora lette.</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Data Richiesta</th>
									<th>Richiedente</th>
									<th>Adulti</th>
									<th>Bambini</th>
									<th>Dal Giorno</th>
									<th>Al Giorno</th>
									<th>Maggiori Info</th>
									<!-- <th>Confermata</th> -->
								</tr>
							</thead>
							<tbody>
								<?php if(count($new_requests)>0): ?>
									<?php foreach ($new_requests as $id=>$request): ?>
										<tr id="new-request-<?=$request->id?>">
											<td class="request-time"><?=$request->time?></td>
											<td class="request-name"><?=$request->name." ".$request->surname?></td>
											<td class="request-adults"><?=$request->adults?></td>
											<td class="request-babies"><?=$request->babies?></td>
											<td class="request-begin"><?=$request->from_date?></td>
											<td class="request-end"><?=$request->to_date?></td>
											<td class="info request-details"><a class="request-more-info" data-id="<?=$id?>" href="#" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
											<!-- <td class="request-confirmed"><input type="checkbox" request-id="<?=$request->id?>" <? if($request->confirmed) print 'checked' ?>></td> -->
										</tr>
									<?php endforeach ?>
								<?php else: ?>
									<tr>
										<td colspan="7">Nessuna richiesta ancora da leggere. Vedi quelle <a href="#booking-list" data-toggle="tab" >gi&agrave; lette</a>.</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>
					</div> <!-- #booking-list-new -->
				<? endif; ?>
				
				<div class="tab-pane fade <?if( ! isset($new_requests)) echo "in active"?>" id="booking-list">
					<h3>Tutte le richieste pervenute.</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Data Richiesta</th>
								<th>Richiedente</th>
								<th>Adulti</th>
								<th>Bambini</th>
								<th>Dal Giorno</th>
								<th>Al Giorno</th>
								<th>Maggiori Info</th>
								<th>OK</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($requests) AND count($requests)>0): ?>
								<?php foreach ($requests as $id=>$request): ?>
									<tr id="request-<?=$request->id?>">
										<td class="request-time"><?=$request->time?></td>
										<td class="request-name"><?=$request->name." ".$request->surname?></td>
										<td class="request-adults"><?=$request->adults?></td>
										<td class="request-babies"><?=$request->babies?></td>
										<td class="request-begin"><?=$request->from_date?></td>
										<td class="request-end"><?=$request->to_date?></td>
										<td class="info request-details"><a class="request-more-info" data-id="<?=$id?>" href="#" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
										<td class="request-confirmed"><input type="checkbox" request-id="<?=$request->id?>" rel="tooltip" data-original-title="Richiesta Confermata" <? if($request->confirmed) print 'checked' ?>></td>
										<td class="request-seen <? if( ! $request->seen) echo "new"?>"></td>
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="8">Nessuna richiesta nel sistema.</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane fade" id="booking-list-old">
					<h3>Richieste riguardanti il passato.</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Data Richiesta</th>
								<th>Richiedente</th>
								<th>Adulti</th>
								<th>Bambini</th>
								<th>Dal Giorno</th>
								<th>Al Giorno</th>
								<th>Maggiori Info</th>
								<th>OK</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($old_requests) AND count($old_requests)>0): ?>
								<?php foreach ($old_requests as $id=>$request): ?>
									<tr id="old-request-<?=$request->id?>">
										<td class="request-time"><?=$request->time?></td>
										<td class="request-name"><?=$request->name." ".$request->surname?></td>
										<td class="request-adults"><?=$request->adults?></td>
										<td class="request-babies"><?=$request->babies?></td>
										<td class="request-begin"><?=$request->from_date?></td>
										<td class="request-end"><?=$request->to_date?></td>
										<td class="info request-details"><a class="request-more-info" data-id="<?=$id?>" href="#" rel="tooltip" data-original-title="Info">&nbsp;</a></td>
										<td class="request-confirmed"><input type="checkbox" disabled <? if($request->confirmed) print 'checked' ?>></td>
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="8">Nessuna richiesta nel sistema.</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane fade" id="booking-settings">
					<h2>Impostazioni Generali</h2>
					<div class="well">
						<h3>Foglio di Stile</h3>
						<?php echo form_open_multipart('admin/settings/style/'.SERVICE_ID_BOOKING,
														array('class' => 'form-inline', 'id' => 'form-add-style'),
														array('from'=>"admin/reservations")); ?>
							<input type="submit" name="submit" value="Carica nuovo File" class="btn btn-success">
							<input class="input-file" id="css_file" name="css_file" type="file"/>
							<?php if (isset($css_file) AND $css_file): ?>
									<a class="btn btn-primary" href="<?=$css_file?>" target="_BLANK">Visualizza/Scarica</a>&nbsp;
									<a class="btn btn-warning" href="settings/style/<?=SERVICE_ID_BOOKING?>/remove?from=admin/reservations">Elimina CSS</a>
							<?php endif ?>
						</form>
						
					</div>
				</div>
				<div class="tab-pane fade" id="booking-descr">
					<p class="dark">Il servizio booking consente ai tuoi clienti di inoltrarti richieste di prenotazione direttamente dalle pagine del tuo sito.
						Il sistema ti inoltrerà automaticamente un’email alla quale potrai rispondere per confermare o rifiutare la richiesta.
						Con il servizio potrai consultare lo storico di tutte le richieste pervenute.</p>
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