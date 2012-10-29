<div class="content clearfix">
	<h1>Informazioni sull Utente</h1>
	<form action="#" class="form-horizontal" accept-charset="utf-8">
		<fieldset>
			<legend>Dettagli</legend>

			<div class="control-group">
				<label class="control-label" for="user_id">ID Utente</label>
				<div class="controls">
					<input disabled id="user_id" class="input-xlarge" value="<?=$subscriber->id?>">
				</div>
			</div>
			

			<div class="control-group">
				<label class="control-label" for="user_email">Indirizzo eMail</label>
				<div class="controls">
					<input disabled id="user_email" class="input-xlarge" value="<?=$subscriber->email?>">
				</div>
			</div>
			

			<div class="control-group">
				<label class="control-label" for="user_ip_signup">IP Sottoscrizione</label>
				<div class="controls">
					<input disabled id="user_ip_signup" class="input-xlarge" value="<?=$subscriber->ip_signup?>">
				</div>
			</div>
			

			<div class="control-group">
				<label class="control-label" for="user_timestamp_signup">Data e Ora di Sottoscrizione</label>
				<div class="controls">
					<input disabled id="user_timestamp_signup" class="input-xlarge" value="<?=$subscriber->timestamp_signup?>">
				</div>
			</div>
			
		</fieldset>
		<fieldset>
			<legend>Dati Aggiuntivi</legend>

			<div class="control-group">
				<label class="control-label" for="user_language">Lingua Preferita</label>
				<div class="controls">
					<input disabled id="user_language" class="input-xlarge" value="<?=$subscriber->language?>">
				</div>
			</div>			

			<div class="control-group">
				<label class="control-label" for="user_email_type">Tipologia eMail</label>
				<div class="controls">
					<input disabled id="user_email_type" class="input-xlarge" value="<?=$subscriber->email_type?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="user_status">Status</label>
				<div class="controls">
					<input disabled id="user_status" class="input-xlarge" value="<?=$subscriber->status?>">
				</div>
			</div>
			
			<?php foreach ($subscriber->merges as $key => $value): ?>
				<div class="control-group">
					<label class="control-label" for="user_merges_<?=$key?>"><?=$key?></label>
					<div class="controls">
						<input disabled id="user_merges_<?=$key?>" class="input-xlarge" value="<?=$value?>">
					</div>
				</div>
			<?php endforeach; ?>
						
		</fieldset>
		<?php if (isset($subscriber->geo->latitude)): ?>
			<fieldset>
				<legend>Dati Geolocalizzazione</legend>

				<div class="control-group">
					<label class="control-label" for="user_geo_latitude">Latitudine</label>
					<div class="controls">
						<input disabled id="user_geo_latitude" class="input-xlarge" value="<?=$subscriber->geo->latitude?>">
					</div>
				</div>			

				<div class="control-group">
					<label class="control-label" for="user_geo_longitude">Longitudine</label>
					<div class="controls">
						<input disabled id="user_geo_longitude" class="input-xlarge" value="<?=$subscriber->geo->longitude?>">
					</div>
				</div>			

				<div class="control-group">
					<label class="control-label" for="user_geo_gmtoff">GMT Offset</label>
					<div class="controls">
						<input disabled id="user_geo_gmtoff" class="input-xlarge" value="<?=$subscriber->geo->gmtoff?>">
					</div>
				</div>			

				<div class="control-group">
					<label class="control-label" for="user_geo_timezone">Timezone</label>
					<div class="controls">
						<input disabled id="user_geo_timezone" class="input-xlarge" value="<?=$subscriber->geo->timezone?>">
					</div>
				</div>			

				<div class="control-group">
					<label class="control-label" for="user_geo_cc">Codice Stato/Citt&agrave;</label>
					<div class="controls">
						<input disabled id="user_geo_cc" class="input-xlarge" value="<?=$subscriber->geo->cc?> / <?=$subscriber->geo->region?>">
					</div>
				</div>			

			</fieldset>
		<?php endif ?>
		

		<div class="actions">
			<a href="#" class="btn close-dialog">Chiudi</a>
		</div>
	</form>
</div>