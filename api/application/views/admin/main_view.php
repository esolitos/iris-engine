<div class="content clearfix">
	<script type="text/javascript" charset="utf-8">
	$().ready(function(){
		var options = {
			placement: 'bottom',
			trigger: 'hover',
			delay: { show: 300, hide: 1000 },
			title: 'Rinnovo Automatico',
			content: "I servizi prevedono un rinnovo automatico, per terminare la sottoscrizione contatta Iris Design entro 60 giorni dalla scadenza tramite indirizzo info@irisdesign.it"
		}
		$("a[rel=popover]").popover(options);	
	});
	$(".auto-renew").live('click', function(e){
		e.preventDefault();
	});
	</script>
	<!-- <a href="#" class="btn btn-danger" rel="popover" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title">hover for popover</a> -->
	
	<p class="description">Ciao <?=$user['username']?>, benvenuto su  "<em>IRIS Login</em>"! <br>
		 Attraverso questa pagina il sistema ti permette di gestire in autonomia tutti i servizi attivati sul tuo sito web con pochi click.</p>
	
	<?if($error OR $message):?>
		<div>
			<?if($error):?>
				<span class="alert alert-error"><?=$error?></span>
			<?endif;?>
			<?if($message):?>
				<span class="alert alert-info"><?=$message?></span>
			<?endif;?>
		</div>
	<?endif;?>


	<?php if( ! isset($website['services_list'][SERVICE_NAME_OFFERS]) OR $website['services_list'][SERVICE_NAME_OFFERS] == 0): ?>
		<!-- Service is expired! -->
		<div class="service clearfix expired">
	<?php elseif($website['services_list'][SERVICE_NAME_OFFERS] <= IS_EXPIRING_STATUS_DAYS): ?>
		<!-- Service will expire in less than <? echo IS_EXPIRING_STATUS_DAYS?>gg -->
		<div class="service clearfix going">
	<?php else: ?>
		<div class="service clearfix">		
	<?php endif; ?>
	
		<div id="<?=SERVICE_NAME_OFFERS?>-bar" class="service-head clearfix">
			<span class="service-name">Offerte</span>
			<span class="service-status">

				<?php if ( ! isset($website['services_list'][SERVICE_NAME_OFFERS])): ?>
					STATUS: Servizio non attivo.<br>
					<a href="mailto:info@irislogin.it?subject=Attivazione Servizo Offerte"><strong>Contatta Iris Design</strong> ed <strong>Attiva</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_OFFERS] <= 0): ?>
					STATUS: <strong>Scaduto il <?=outputMyDate($website['services'][SERVICE_NAME_OFFERS]->service_expire)?></strong><br>
					<a href="mailto:info@irislogin.it?subject=Rinnovo Servizo Offerte"><strong>Contatta Iris Design</strong> e <strong>Rinnova</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_OFFERS] < IS_EXPIRING_STATUS_DAYS): ?>
					STATUS: Rimangono solo <strong><?=$website['services_list'][SERVICE_NAME_OFFERS]?> giorni.</strong><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php else: ?>
					STATUS: Attivo <em>(Scade: <?=outputMyDate($website['services'][SERVICE_NAME_OFFERS]->service_expire)?>)</em><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php endif; ?>

			</span>
		</div>
		<div id="<?=SERVICE_NAME_OFFERS?>-body" class="service-body">
			<div class="service-actions">
				<div class="add">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_OFFERS]) AND $website['services_list'][SERVICE_NAME_OFFERS] > 0)
							$link = base_url("admin/offers.html#offer-add");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Crea Offerta</strong>
							<em>Clicca qui per creare una nuova offerta</em>
						</span>
					</a>
				</div>
				<div class="edit">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_OFFERS]) AND $website['services_list'][SERVICE_NAME_OFFERS] > 0)
							$link = base_url("admin/offers.html#offer-list");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Gestisci Offerte</strong>
							<em>Clicca qui per cancellare o modificare un offerta<br>&nbsp;gi&agrave; presente nel sistema.</em>
						</span>
					</a>
				</div>
			</div>
			<div class="service-description">
				<img src="/public/img/info.png" width="25" height="56" alt="Info">
				<p>Con il servizio <em>Offerte</em> potrai inserire nella pagina dedicata del tuo sito offerte e promozioni riguardanti i tuoi servizi e prodotti.
				Potrai deciderne la scadenza e se hai sottoscritto anche il servizio <em>Newsletter</em> avvisare in automatico i tuoi clienti che si sono registrati sul tuo sito dei nuovi inserimenti.</p>
			</div>
		</div>
	</div>
	
	<?php if( ! isset($website['services_list'][SERVICE_NAME_NEWSLETTER]) OR $website['services_list'][SERVICE_NAME_NEWSLETTER] == 0): ?>
		<!-- Service is expired! -->
		<div class="service clearfix expired">
	<?php elseif($website['services_list'][SERVICE_NAME_NEWSLETTER] > 0 AND $website['services_list'][SERVICE_NAME_NEWSLETTER] < IS_EXPIRING_STATUS_DAYS): ?>
		<!-- Service will expire in less than <? echo IS_EXPIRING_STATUS_DAYS?>gg -->
		<div class="service clearfix going">
	<?php else: ?>
		<div class="service clearfix">		
	<?php endif; ?>
		<div id="<?=SERVICE_NAME_NEWSLETTER?>-bar" class="service-head clearfix">
			<span class="service-name">Newsletter</span>
			<span class="service-status">

				<?php if ( ! isset($website['services_list'][SERVICE_NAME_NEWSLETTER])): ?>
					STATUS: Servizio non attivo.<br>
					<a href="mailto:info@irislogin.it?subject=Attivazione Servizo Newsletter"><strong>Contatta Iris Design</strong> ed <strong>Attiva</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_NEWSLETTER] <= 0): ?>
					STATUS: <strong>Scaduto il <?=outputMyDate($website['services'][SERVICE_NAME_NEWSLETTER]->service_expire)?></strong><br>
					<a href="mailto:info@irislogin.it?subject=Rinnovo Servizo Newsletter"><strong>Contatta Iris Design</strong> e <strong>Rinnova</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_NEWSLETTER] < IS_EXPIRING_STATUS_DAYS): ?>
					STATUS: Rimangono solo <strong><?=$website['services_list'][SERVICE_NAME_NEWSLETTER]?> giorni.</strong><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php else: ?>
					STATUS: Attivo <em>(Scade: <?=outputMyDate($website['services'][SERVICE_NAME_NEWSLETTER]->service_expire)?>)</em><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php endif; ?>

			</span>
		</div>
		<div id="<?=SERVICE_NAME_NEWSLETTER?>-body" class="service-body">
			<div class="service-actions">
				<div class="add">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_NEWSLETTER]) AND $website['services_list'][SERVICE_NAME_NEWSLETTER] > 0)
							$link = base_url("/admin/newsletter.html#newsletter-add");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Crea Newsletter</strong>
							<em>Clicca qui per creare una nuova newsletter</em>
						</span>
					</a>
				</div>
				<div class="add-user">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_NEWSLETTER]) AND $website['services_list'][SERVICE_NAME_NEWSLETTER] > 0)
							$link = base_url("/admin/newsletter.html#subscribers-add");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Aggiungi iscrizione</strong>
							<em>Clicca qui per invitare un tuo contatto ad iscriversi alla tua newsletter. </em>
						</span>
					</a>
				</div>
			</div>
			<div class="service-description">
				<img src="/public/img/info.png" width="25" height="56" alt="Info">
				<p>Il servizio <em>Newsletter</em> è il modo più semplice e veloce per comunicare via e-mail a tutti i tuoi clienti iscritti le novità riguardanti la tua attività.
					Scopri com'è semplice ed intuitivo con l'editor di testo integrato con il quale potrai personalizzare i tuoi messaggi!</p>
			</div>
		</div>
	</div>


	<?php if( ! isset($website['services_list'][SERVICE_NAME_BOOKING]) OR $website['services_list'][SERVICE_NAME_BOOKING] == 0): ?>
		<!-- Service is expired! -->
		<div class="service clearfix expired">
	<?php elseif($website['services_list'][SERVICE_NAME_BOOKING] > 0 AND $website['services_list'][SERVICE_NAME_BOOKING] < IS_EXPIRING_STATUS_DAYS): ?>
		<!-- Service will expire in less than <? echo IS_EXPIRING_STATUS_DAYS?>gg -->
		<div class="service clearfix going">
	<?php else: ?>
		<div class="service clearfix">		
	<?php endif; ?>
		<div id="<?=SERVICE_NAME_BOOKING?>-bar" class="service-head clearfix">
			<span class="service-name">Booking</span>
			<span class="service-status">

				<?php if ( ! isset($website['services_list'][SERVICE_NAME_BOOKING])): ?>
					STATUS: Servizio non attivo.<br>
					<a href="mailto:info@irislogin.it?subject=Attivazione Servizo Booking"><strong>Contatta Iris Design</strong> ed <strong>Attiva</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_BOOKING] <= 0): ?>
					STATUS: <strong>Scaduto il <?=outputMyDate($website['services'][SERVICE_NAME_BOOKING]->service_expire)?></strong><br>
					<a href="mailto:info@irislogin.it?subject=Rinnovo Servizo Booking"><strong>Contatta Iris Design</strong> e <strong>Rinnova</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_BOOKING] < IS_EXPIRING_STATUS_DAYS): ?>
					STATUS: Rimangono solo <strong><?=$website['services_list'][SERVICE_NAME_BOOKING]?> giorni.</strong><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php else: ?>
					STATUS: Attivo <em>(Scade: <?=outputMyDate($website['services'][SERVICE_NAME_BOOKING]->service_expire)?>)</em><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php endif; ?>

			</span>
		</div>
		<div id="<?=SERVICE_NAME_BOOKING?>-body" class="service-body">
			<div class="service-actions">
				<div class="view">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_BOOKING]) AND $website['services_list'][SERVICE_NAME_BOOKING] > 0)
							$link = base_url("/admin/reservations.html#view-requests");
						else
							$link = "#";
					?>
						<a href="<?=$link?>">
							<span>
								<strong>Visualizza Richieste</strong>
								<em>Clicca qui per visualizzare le richieste pervenute sino ad oggi</em>
							</span>
						</a>
				</div>
				<div class="edit">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_BOOKING]) AND $website['services_list'][SERVICE_NAME_BOOKING] > 0)
							$link = base_url("/admin/reservations.html#booking-settings");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Modifica Opzioni</strong>
							<em>Clicca qui per modificare le opzioni del servizio</em>
						</span>
					</a>
				</div>
			</div>
			<div class="service-description">
				<img src="/public/img/info.png" width="25" height="56" alt="Info">
				<p>Il servizio <em>Booking</em> consente ai tuoi clienti di inoltrarti richieste di prenotazione direttamente dalle pagine del tuo sito.
					Il sistema ti inoltrerà automaticamente un’email alla quale potrai rispondere per confermare o rifiutare la richiesta.
					Con il servizio potrai consultare lo storico di tutte le richieste pervenute.</p>
			</div>
		</div>
	</div>	
	
	
	<?php if( ! isset($website['services_list'][SERVICE_NAME_GALLERY]) OR $website['services_list'][SERVICE_NAME_GALLERY] == 0): ?>
		<!-- Service is expired! -->
		<div class="service clearfix expired">
	<?php elseif($website['services_list'][SERVICE_NAME_GALLERY] > 0 AND $website['services_list'][SERVICE_NAME_GALLERY] < IS_EXPIRING_STATUS_DAYS): ?>
		<!-- Service will expire in less than <? echo IS_EXPIRING_STATUS_DAYS?>gg -->
		<div class="service clearfix going">
	<?php else: ?>
		<div class="service clearfix">		
	<?php endif; ?>
		<div id="<?=SERVICE_NAME_GALLERY?>-bar" class="service-head clearfix">
			<span class="service-name">Gallery</span>
			<span class="service-status">

				<?php if ( ! isset($website['services_list'][SERVICE_NAME_GALLERY])): ?>
					STATUS: Servizio non attivo.<br>
					<a href="mailto:info@irislogin.it?subject=Attivazione Servizo Gallery"><strong>Contatta Iris Design</strong> ed <strong>Attiva</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_GALLERY] <= 0): ?>
					STATUS: <strong>Scaduto il <?=outputMyDate($website['services'][SERVICE_NAME_GALLERY]->service_expire)?></strong><br>
					<a href="mailto:info@irislogin.it?subject=Rinnovo Servizo Gallery"><strong>Contatta Iris Design</strong> e <strong>Rinnova</strong> subito il servizio!</a>

				<?php elseif($website['services_list'][SERVICE_NAME_GALLERY] < IS_EXPIRING_STATUS_DAYS): ?>
					STATUS: Rimangono solo <strong><?=$website['services_list'][SERVICE_NAME_GALLERY]?> giorni.</strong><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php else: ?>
					STATUS: Attivo <em>(Scade: <?=outputMyDate($website['services'][SERVICE_NAME_GALLERY]->service_expire)?>)</em><br>
					<a class="auto-renew" rel="popover" href="#">Rinnovo Automatico Attivo (Più Informazioni)</a>

				<?php endif; ?>

			</span>
		</div>
		<div id="<?=SERVICE_NAME_GALLERY?>-body" class="service-body">
			<div class="service-actions">
				<div class="add">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_GALLERY]) AND $website['services_list'][SERVICE_NAME_GALLERY] > 0)
							$link = base_url("/admin/gallery.html#gallery-add");
						else
							$link = "#";
					?>
					<a href="<?=$link?>">
						<span>
							<strong>Crea Gallery</strong>
							<em>Clicca qui per creare una nuova galleria di immagini.</em>
						</span>
					</a>
				</div>
				<div class="edit">
					<?php 
						if(isset($website['services_list'][SERVICE_NAME_GALLERY]) AND $website['services_list'][SERVICE_NAME_GALLERY] > 0)
							$link = base_url("/admin/gallery.html#gallery-list");
						else
							$link = "#";
					?>
						<a href="<?=$link?>">
							<span>
								<strong>Gestisci le Gallery</strong>
								<em>Clicca qui per visualizzare e gestire le gallerie precedentemente create.</em>
							</span>
						</a>
				</div>
			</div>
			<div class="service-description">
				<img src="/public/img/info.png" width="25" height="56" alt="Info">
				<p>Con il servizio <em>Gallery</em> avrai la possibilità di inserire le tue gallerie fotografiche in una pagina dedicata. 
					Potrai modificarle, eliminarle o aggiungerne di nuove. Potrai organizzarle per temi, modificandone i titoli. 
					Sul tuo sito appariranno le anteprime delle immagini, che che potranno essere visualizzate in modalità full-screen.</p>
			</div>
		</div>
	</div>
	
	
</div>
<script src="/public/js/admin.js" type="text/javascript" charset="utf-8"></script>

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