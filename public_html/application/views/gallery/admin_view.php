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
				
				console.log(e);
				alert("Errore nell\'Upload!");
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
				<!-- <li id="gallery-settings-selector"><a href="#gallery-settings" data-toggle="tab">Impostazioni</a></li> -->
				<li id="gallery-descr-selector"><a href="#gallery-descr" data-toggle="tab">Maggiorni Informazioni</a></li>
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
					<?php echo form_open('admin/gallery/add', array('class' => 'form-horizontal', 'id'=>'gallery-add'), $form_hidden); ?>
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
								<label class="control-label" for="image_upload">Aggiungi Immagine</label>
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
					</form> <!-- /admin/offers/add -->
					

				</div>

				<!-- <div class="tab-pane fade" id="gallery-settings">
					<h2>Impostazioni Generali</h2>
					<p>TODO</p>
				</div>
				 -->
				
				<div class="tab-pane fade" id="gallery-descr">
					<p class="dark">Con il servizio gallery avrai la possibilità di inserire le tue gallerie fotografiche in una pagina dedicata. Potrai modificarle, eliminarle o aggiungerne di nuove.
					Potrai organizzarle per temi, modificandone i titoli. Sul tuo sito appariranno le anteprime delle immagini, che che potranno essere visualizzate in modalità fullscreen.</p>
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