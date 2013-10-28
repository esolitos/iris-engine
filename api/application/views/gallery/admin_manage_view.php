<div class="content clearfix">
	
	<link rel="stylesheet" href="<?=base_url("public/css/services/gallery.css")?>" type="text/css" media="screen" title="no title" charset="utf-8">
	<script src="<?=base_url("public/js/jquery.ajaxfileupload.js")?>" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript">
	/* <![CDATA[ */

	$(document).ready(function(){

		$('#fileupload').click(function(e) {
			$('#fileupload').val("...uploading...").addClass("disabled").attr('disabled', 'disabled');
			$("#loader-icon").show();

			e.preventDefault();

			$.ajaxFileUpload({
				url:'/admin/gallery/file/upload', 
				secureuri:false,
				fileElementId:'image_upload',
				dataType: 'json',
				data    : {
					'website_id': <?=$website['info']->website_id?>,
					'gallery_id': <?=$gallery_data->id?>,
					'csrf_token_iris_login': $('input[name=<?= $this->config->item('csrf_token_name');?>]').val(),
				},
				success: function (data, status) {
					if(data.error !='') {
						alert(data.error);
					} else {
						<?php if(DEBUG) echo "console.log(data);"; ?>

						$("#gallery-no-images").hide();

						new_elem = $(document.createElement('li')).attr({
							id: 'fileupload-' + data.img_id,
							'data-id': data.img_id
						}).appendTo("#gallery-images");

						$(document.createElement('img')).attr({
							src: data.thumb_path,
							class: "upload-thumb"
						}).appendTo(new_elem);

						$(document.createElement('a')).attr({
							href: "#",
							class: "delete-img"
						}).appendTo(new_elem);
					}

					$("#loader-icon").hide();
					$('#fileupload').val("Invia File").removeClass("disabled").attr('disabled', false);
				},
				error: function (data, status, e) {
					$("#loader-icon").hide();
					$('#fileupload').val("Invia File").removeClass("disabled").attr('disabled', false);

					console.log(e);
					console.log(data);

					alert("Errore Generale nell\'Upload!");
				}
			});


		});

		$(".delete-img").live('click', function(e){
			e.preventDefault();
			
			img_id = $(this).parent('li').attr('data-id');
			// alert("IMG ID: "+img_id);
			
			$.ajax({
				type: "POST",
				url: '/admin/gallery/file/delete',
				dataType: 'json',
				data: {
					'website_id': <?=$website['info']->website_id?>,
					'gallery_id': <?=$gallery_data->id?>,
					'image_id': img_id,
					'csrf_token_iris_login': $('input[name=<?= $this->config->item('csrf_token_name');?>]').val()
				},
				success: function(data, status){
					if(data.error != '') {
						alert(data.error);
					} else {
						<?php if(DEBUG) echo "console.log(data);"; ?>
						
						$('li[data-id='+img_id+']').remove();
						
						if($("#gallery-images li").length <= 1) {
							$("#gallery-no-images").show();
						}
						
					}
				},
				error: function(data, status, e) {
					console.log(e);
					alert("ERRORE: Impossibile eliminare l'imamgine!");
				}
			});
			
		});

		$("#manage-gallery").submit(function(e){
				e.preventDefault();
		});
	});
	/* ]]> */

	</script>
	
	<h2> Gestisci Immagini: <small>"<?=$gallery_data->name?>"<small></h2>

	<?if($error):?>
		<span class="alert alert-error"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="alert alert-info"><?=$message?></span>
	<?endif;?>
	
	<div id="gallery-manage">
		<?= form_open_multipart('admin/gallery/manage/'.$gallery_data->id, array('class' => 'form-horizontal clearfix', 'id' => 'manage-gallery')); ?>
			<fieldset>
				<div class="control-group" id="images-row">
					<label class="control-label">Immagini nella Galleria</label>
					<div class="controls">
						<ul id="gallery-images" class="clearfix">
						<?php if (count($gallery_data->images)): ?>
							<li id="gallery-no-images" style="display:none">Nessuna immagine nella galleria.</li>
							<?php foreach ($gallery_data->images as $img_id => $img_data): ?>
								<li id="image-<?=$img_id?>" data-id="<?=$img_id?>">
									<img src="<?=base_url($img_data->thumb)?>" class="upload-thumb"/>
									<a href="#" class="delete-img"></a>
								</li>
							<?php endforeach ?>
						<?php else: ?>
							<li id="gallery-no-images">Nessuna immagine nella galleria.</li>
						<?php endif ?>
						</ul>
						
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="image_upload">Aggiungi Immagine  <em>(Massimo: <?php echo(UPLOAD_MAX_SIZE / 1024)?>MB)</em></label>
					<div class="controls">
						<img id="loader-icon" src="/public/img/ajax-loader.gif" width="16" height="16" alt="Ajax Loader" style="display:none">
						<input type="file" class="input-xlarge focused" id="image_upload" name="image_upload" />&nbsp;
					</div>
				</div>
				
			</fieldset>
			
			<div class="form-actions">
				<input type="submit" name="invia" value="Invia File" id="fileupload" class="btn btn-primary"/>
				<?=anchor('admin/gallery', "Fine Operazioni", array('class'=>'btn'));?>
			</div>
		</form>
	</div> <!-- /#gallery-manage -->
	
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