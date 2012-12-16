<link rel="stylesheet" href="<?=base_url("public/css/services/gallery.css")?>" type="text/css" media="screen" title="no title" charset="utf-8">
<script src="<?=base_url("public/js/jquery.ajaxfileupload.js")?>" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
/* <![CDATA[ */

$(document).ready(function(){
	$("#gallery-list td a").tooltip();
	$(".modal").modal({'show':false});
	
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
	
	
	$('#fileupload').click(function(e) {
		$('#fileupload').hide();
		$("#loader-icon").show();
		
		e.preventDefault();
		
		$.ajaxFileUpload({
			url:'/admin/gallery/file/upload', 
			secureuri:false,
			fileElementId:'image_upload',
			dataType: 'json',
			data    : {
				'website_id': <?=$website['info']->website_id?>,
				'gallery_id': 'new',
				'csrf_token_iris_login': $('input[name=<?= $this->config->item('csrf_token_name');?>]').val(),
			},
			success: function (data, status) {
				if(data.error !='') {
					alert(data.error);
				} else {
					<?php if(DEBUG) echo "console.log(data);"; ?>
		
					$("#gallery-no-images").hide();
					
					$(document.createElement('img')).attr({
						src: data.thumb_path,
						class: "upload-thumb"
					}).appendTo("#gallery-images");
					
					$(document.createElement('input')).attr({
					    type: 'hidden',
					    name: 'images[]',
						value: data.file_name
					}).appendTo('#gallery-images');
				}
				
				$("#loader-icon").hide();
				$('#fileupload').show();
			},
			error: function (data, status, e) {
				$("#loader-icon").hide();
				$('#fileupload').show();
				
				<?php if(DEBUG): ?>
					console.log(e);
					console.log(status);
					console.log(data);
				<?php endif; ?>
				
				alert("Errore generico nell\'upload!");
			}
		});
		
		
	});

	$("#gallery-add").submit(function(e){
		// Permete il submit solo se il campo del titolo è compilato!
		if($("#gallery-add-title input").val().length <= 0) {
			e.preventDefault();
			
			alert("Inserisci un Titolo alla galleria.");
			$("#gallery-add-title").addClass("error");
			$("#gallery-add-title input").focus();
		}
	});
	
	$(".gallery-name a").live('click', function(e){
		e.preventDefault();
		
		id = $(this).parents('tr').attr('gallery-id');
		
		$(this).next(".edit-title").show().focus();
		$(this).hide();
	});


	$(".edit-title button").click(function(e){
		e.preventDefault();
		
		var button = $(this);
		var id = button.parents('tr').attr('gallery-id');
		var title = button.prev('input').val();
		
		if(title.length <= 0)
		{
			alert("Inserisci un Titolo!");
			$(this).prev('input').focus();
		}
		else {
			$.ajax({
				type: "POST",
				url: '/admin/gallery/manage/'+id,
				dataType: 'json',
				data: {
					'gallery_title': title,
					'csrf_token_iris_login': $('input[name=<?= $this->config->item('csrf_token_name');?>]').val()
				},
				success: function(data, status){
					if(data.success) {
						button.parent('.edit-title').prev('a').html(title);
						button.parent('.edit-title').prev('a').show();
						button.parent('.edit-title').hide();
					}
					else {
						alert("ERRORE: Impossibile modificare il titolo!");
					}
				},
				error: function(data, status, e) {
					console.log(e);
					alert("ERRORE: Impossibile modificare il titolo!");
				}
			});
		}
	})
});
/* ]]> */

</script>

<div class="content clearfix">
	<section id="tabs" class="tabs-gallery">
		<div class="span8 offset2">
			<h1>Gestisci le Gallery <br><small>Aggiungi, modifica ed elimina le tue gallery.</small></h1>
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

		<div id="gallery-tabs-wrapper" class="tabbable tabs-left span12">
			<ul id="tab" class="nav nav-tabs span2">
				<li id="gallery-list-selector" class="active"><a href="#gallery-list" data-toggle="tab">Le Gallery</a></li>
				<li id="gallery-add-selector"><a href="#gallery-add" data-toggle="tab">Nuova Gallery</a></li>
				<li id="gallery-settings-selector"><a href="#gallery-settings" data-toggle="tab">Impostazioni</a></li>
				<li id="gallery-descr-selector"><a href="#gallery-descr" data-toggle="tab">Maggiorni Informazioni</a></li>
				<li id="service-html-code-selector"><a href="#service-html-code" data-toggle="tab">Codice Servizio</a></li>
			</ul> <!-- #tab -->

			<div id="tab-content" class="span10 tab-content">
				
				<div class="tab-pane fade in active" id="gallery-list">
					<?php if ($galleries['total']): ?>
						<table class="table table-striped">
							<tr>
								<th>ID</th>
								<th>Titolo Galleria</th>
								<th>Immagini</th>
								<th colspan="3">Azioni</th>
							</tr>
							<?php foreach ($galleries['data'] as $this_gallery): ?>
								<tr gallery-id="<?=$this_gallery->id?>">
									<td><?=$this_gallery->id?></td>
									<td class="gallery-name">
										<a href="#" rel="tooltip" data-original-title="Modifica"><?=$this_gallery->name?></a>
										<div class="edit-title input-append"  style="display:none">
											<input type="text" name="gallery_title" value="<?=$this_gallery->name?>"/>
											<button class="btn" type="button">Salva!</button>
										</div>
									</td>
									<td><?=$this_gallery->num_images?></td>
									<td class="test"><a href="<?=base_url("/gallery/single/{$this_gallery->id}/0/fullscreen")?>" target="_BLANK" rel="tooltip" data-original-title="Visualizza"></a></td>
									<td class="edit"><a href="<?=base_url("/admin/gallery/manage/{$this_gallery->id}")?>" rel="tooltip" data-original-title="Gestisci Immagini"></a></td>
									<td class="delete"><a href="#modal-gallery-<?=$this_gallery->id;?>" rel="tooltip" data-original-title="Elimina" data-toggle="modal"></a></td>

									<div class="modal fade modal-delete" id="modal-gallery-<?=$this_gallery->id;?>">
										<div class="modal-header">
											<a class="close" data-dismiss="modal">×</a>
											<h3>Attenzione!</h3>
										</div>
										<div class="modal-body">
											Si sicuro di voler eliminare la galleria: "<?=$this_gallery->name?>"?<br> Nota che non sar&agrave; possibile annullare l'operazione e tutte le immagini caricate in questa galleria verranno eliminate!
										</div>
										<div class="modal-footer">
											<a data-dismiss="modal" href="#" class="btn btn-primary">Annulla</a>
											<a href="<?=base_url("/admin/gallery/delete/{$this_gallery->id}")?>" class="btn">Elimina</a>
										</div>
									</div>


								</tr>
							<?php endforeach ?>
						</table>
					<?php else: ?>
						<p>Non hai ancora creato alcuna galleria.</p>
					<?php endif ?>
				</div> <!-- /#gallery-list -->
				
				
				<div class="tab-pane fade" id="gallery-add">
					<?php echo form_open('admin/gallery/add', array('class' => 'form-horizontal', 'id'=>'gallery-add'), array('website_id' => $website['info']->website_id)); ?>
						<fieldset>
							<legend>Aggiungi una Gallery</legend>
						</fieldset>	

						<fieldset>
							<div class="control-group" id="gallery-add-title">
								<label class="control-label" for="gallery_title">Titolo della Gallery</label>
								<div class="controls">
									<input class="input-xlarge focused" name="gallery_title" size="50" maxlength="100" placeholder="Inserisci qui il titolo.">
								</div>
							</div>

						</fieldset>

						<fieldset>
							<div class="control-group" id="images-row">
								<label class="control-label">Immagini nella Galleria</label>
								<div class="controls">
									<ul id="gallery-images" class="clearfix">
										<li id="gallery-no-images">Nessuna immagine nella galleria.</li>
									</ul>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="image_upload">Aggiungi Immagine <em>(Massimo: <?php echo(UPLOAD_MAX_SIZE / 1024)?>MB)</em></label>
								<div class="controls">
									<img id="loader-icon" src="/public/img/ajax-loader.gif" width="16" height="16" alt="Ajax Loader" style="display:none">
									
									<input type="file" class="input-xlarge focused" id="image_upload" name="image_upload" />&nbsp;
									<input type="submit" name="invia" value="Invia File" id="fileupload" />
								</div>
							</div>
							
						</fieldset>

						<div class="form-actions">
							<button type="submit" class="btn btn-primary">Salva Gallery</button>
							<?=anchor('admin/main', "Annulla", array('class'=>'btn'));?>
						</div>
					</form> <!-- /admin/gallery/add -->
					

				</div>

				<div class="tab-pane fade" id="gallery-settings">
					<h2>Impostazioni Generali</h2>
					<div class="well">
						<h3>Foglio di Stile</h3>
						<?php echo form_open_multipart('admin/settings/style/'.SERVICE_ID_GALLERY,
														array('class' => 'form-inline', 'id' => 'form-add-style'),
														array('from'=>"admin/gallery")); ?>
							<input type="submit" name="submit" value="Carica nuovo File" class="btn btn-success">
							<input class="input-file" id="css_file" name="css_file" type="file"/>
							<?php if (isset($css_file) AND $css_file != STYLE_DEFAULT_FILE): ?>
									<a class="btn btn-primary" href="<?=base_url($css_file)?>" target="_BLANK">Visualizza/Scarica</a>&nbsp;
									<a class="btn btn-warning" href="settings/style/<?=SERVICE_ID_GALLERY?>/remove?from=admin/gallery">Elimina CSS</a>
							<?php endif ?>
						</form>
						
					</div>
				</div>

				
				<div class="tab-pane fade" id="gallery-descr">
					<h3>Guida all'utilizzo di <em>IrisLogin - Gallery</em></h3>
					<p class="dark">Con il servizio <strong>Gallery</strong> avrai la possibilità di inserire le tue <strong>gallerie fotografiche in una pagina dedicata</strong>. Potrai modificarle, eliminarle o aggiungerne di nuove. Potrai organizzarle per temi, modificandone i titoli. Sul tuo sito appariranno le anteprime delle immagini, che che potranno essere visualizzate in modalità <em>fullscreen</em>.<br>
						Potrai caricare fotografie di una <em>dimensione massima di <?php echo(UPLOAD_MAX_SIZE / 1024)?>MB</em>, che corrispondono all'incirca alle fotografie realizzate con macchine fotografiche da 14-16 MegaPixel. Se possiedi una macchina fotografica che realizza file di dimensioni più grandi o se semplicemente vuoi velocizzare i tempi di upload delle fotografie, ti suggeriamo l'utilizzo di uno dei software già preinstallati sul tuo sistema per ridurre sensibilmente le dimensioni della fotografia. Alternativamente puoi utilizzare il software "<strong>Image Resizer per Windows</strong>", scaricabile gratuitamente (vedi Guida).<br>
	Image Resizer per Windows è un software molto leggero e veloce, consente anche di risparmiare molto tempo. <em>Ricorda che sul web risoluzioni come 800x600 oppure 1024x768 sono più che sufficienti per una visualizzazione di qualità delle immagini</em>.</p>
					<h4>Installazione ed utilizzo di "Image Resizer per Windows"</h4>
					<p>
						<h4>Preparazione</h4>
						Scaricate il programma di installazione dal seguente indirizzo: <a href="https://imageresizer.codeplex.com/" target="_BLANK">https://imageresizer.codeplex.com/</a>.<br>
						Una volta terminato il download, dovrete lanciare il file appena scaricato per iniziare la procedura di installazione.<br>
						<em><strong>NB</strong>: Image Resizer for Windows richiede l'installazione di "Microsoft .NET Framework 4 Client", se non è già presente sul vostro PC, l'installatore lo scaricherà automaticamente, vi basterà dare la conferma quando vi verrà chiesto il consenso per l'installazione.</em><br>
						Al termine dell'installazione verrà richiesto di riavviare il computer.
						<h4>Utilizzo</h4>
						Una volta riavviato il computer, vi basterà cliccare con il tasto destro del mouse sull'immagine da ridimensionare, e dal menù contestuale selezionare la voce "Resize Pictures".<br>
						Comparirà una piccola finestra con un elenco di misure predefinite, scegliete quella che preferite (la versione "<em>small</em>" va bene nella maggior parte dei casi), dopodiché cliccate il tasto "<em>resize</em>" e il software creerà <u>una copia dell'immagine in versione ridotta</u>.<br>
						<<em>strong</em>>Attenzione!</strong> Se mette la spunta su "<em>Replace Originals</em>" la foto originale verrà <u>sostituita con quella ridotta</u>, sconsigliamo l'utilizzo di questa opzione.<br>

						Ora avrete l'immagine ridotta pronta per essere inserita sulla vostra gallery, in tempi molto inferiori alle immagini originali.<br>
						&nbsp;&nbsp;Buon divertimento con le Gallery di IRIS Login!
					</p>
				</div>
				
				<div class="tab-pane fade" id="service-html-code">
					<h2>Codice per l'utilizzo del servizio 'Gallery'</h2>
					<p>Per utilizzare il servizio Gallery fornito da IrisLogin in una qualsiasi pagina è sufficiente utilizzare il seguente codice HTML.</p>

					<pre class="irislogin-service-code prettyprint ">
&lt;!-- IrisLogin Gallery Code: Begin --&gt;
  &lt;script type="text/javascript" charset="utf-8" src="<?=base_url("/public/js/manage-gallery.js")?>"&gt;&lt;/script&gt;&lt;iframe src="<?= base_url(SERVICE_NAME_GALLERY.'/'.$website['info']->website_id) ?>" width="550px" height="700px" frameborder="0" id="irislogin-galleries"&gt;&lt;/iframe&gt;
&lt;!-- IrisLogin Gallery Code: End --&gt;</pre>

				</div>

			</div> <!-- #tab-content -->
			
		</div> <!-- #gallery-tabs-wrapper -->

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