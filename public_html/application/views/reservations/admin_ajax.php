<script type="text/javascript" charset="utf-8">
	$().ready(function(){
		$('.req-confirm').click(function(event){
			event.preventDefault();

			var r_id = $("#reqest-id").html();

			$.ajax({
				type: "GET",
				url: "/admin/reservations/"+r_id+"/confirm.html"
				}).done(function(msg) {
					if(msg == "TRUE") {
						$('.req-confirm').remove();
						$('.request-confirmed input[request-id='+r_id+']').attr('checked', "checked");

				 		// alert("Richiesta Confermata");
					}
					else {
				 		alert("ERRORE:"+msg);						
					}
				});
		});		
	});
</script>

<form class="form-horizontal">
	<span style="display:none" id="reqest-id"><?=$req->id?></span>
	<fieldset>
    	<legend>Informazioni di Contatto</legend>
	    <div class="control-group">
	      <label class="control-label">Nome</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->name?>&nbsp;<?=$req->surname?></span>
	      </div>
	    </div>

	    <div class="control-group">
	      <label class="control-label">eMail</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->email?></span>
	      </div>
	    </div>

	    <div class="control-group">
	      <label class="control-label">Telefono</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->tel?></span>
	      </div>
	    </div>

	<fieldset id="info-prenotazione">
		<legend>Informazioni di Prenotazione</legend>

	    <div class="control-group">
	      <label class="control-label">Data Inizio Soggiorno</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->from_date?></span>
	      </div>
	    </div>

	    <div class="control-group">
	      <label class="control-label">Data Fine Soggiorno</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->to_date?></span>
	      </div>
	    </div>

	    <div class="control-group">
	      <label class="control-label">Adulti</label>
	      <div class="controls">
	        <span class="input-large uneditable-input"><?=$req->adults?></span>
	      </div>
	    </div>

		<?php if ($req->babies): ?>
			<div class="control-group">
				<label class="control-label">Bambini</label>
				<div class="controls">
					<span class="input-large uneditable-input"><?=$req->babies?></span>
				</div>
			</div>	
		<?php endif ?>

		<?php if ($req->notes): ?>
			<div class="control-group">
				<label class="control-label">Note</label>
				<div class="controls well">
					<?=$req->notes?>
				</div>
			</div>
		<?php endif ?>

	</fieldset>
    
<!-- 
	<div class="control-group">
      <label class="control-label"></label>
      <div class="controls">
        <span class="input-large uneditable-input"></span>
      </div>
    </div>

 -->

</form>

<div class="form-actions">
	<? if( ! $req->confirmed): ?>
	    <a class="btn close-dialog req-confirm" href="<?=$req->id?>">Indica come "Confermata"</a>
	<? endif; ?>
    <a class="btn btn-primary close-dialog" href="#">Chiudi</a>
</div>