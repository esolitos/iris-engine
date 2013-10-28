
		<div id="footer">
			<div id="copy">
				Powered by <a href="http://irisdesign.it/">IRISDesign.it</a><br>
				Developed by <a href="http://esolitos.com/">Esolitos&nbsp;&copy;</a><br>
			</div>
			<?php if ($user['logged_in']): ?>
				<div id="contact">
					<script type="text/javascript" charset="utf-8">
					$(document).ready(function(){
						$('#send-issue').live('click', function(event){
							event.preventDefault();
							// $("#issue-contact-form").dialog2('open');
							
							$("#issue-contact-form").dialog2({
								title: "Segnalaci il tuo problema.", 
								showCloseHandle: false,
								closeOnOverlayClick: false,
								removeOnClose: false,
								buttons: {
									Invia: { 
										primary: true, 
										click: function() {
											$("form#issue-contact-form").submit();
										}
									},
									Annulla: { 
										primary: false, 
										click: function() {
											$(this).dialog2("close");
										}
									}
								}, 
							});
						});
					});

					</script>
					<a id="send-issue" href="#">Segnalaci un problema.</a>
					
					<div style="display:none">
						<?=form_open('iris/contact', array('id'=>'issue-contact-form', 'class'=>'form-horizontal'), array('from' => uri_string(), 'username' => $user['username'])); ?>
						  <fieldset>
						    <legend>Scrivici il tuo problema &nbsp;<small>NB:Tutti i campi sono obbligatori!</small></legend>

							<div class="control-group">
								<label class="control-label">Username</label>
								<div class="controls">
									<input type="text" class="input-xlarge disabled" disabled value="<?=$user['username']?>">
								</div>
							</div>

						    <div class="control-group">
						      <label class="control-label" for="name">Nome e Cognome</label>
						      <div class="controls">
						       <input type="text" class="input-xlarge" id="name" name="name" placeholder="John Smith">
						        <p class="help-block">Inserisci il tuo nome.</p>
						      </div>
						    </div>

						    <div class="control-group">
						      <label class="control-label" for="company">Azienda</label>
						      <div class="controls">
						        <input type="text" class="input-xlarge" id="company" name="company" placeholder="Apple Inc.">
						        <p class="help-block">Inserisci il nome dell'azienda per conto della quale ci sontatti.</p>
						      </div>
						    </div>

						    <div class="control-group">
						      <label class="control-label" for="email">eMail di contatto</label>
						      <div class="controls">
						        <input type="text" class="input-xlarge" id="email" name="email" value="<?=$user['email']?>">
						        <p class="help-block">Inserisci una mail sulla quale sia possibile contattarti.</p>
						      </div>
						    </div>

						    <div class="control-group">
						      <label class="control-label" for="tel">Telefono di contatto</label>
						      <div class="controls">
						        <input type="text" class="input-xlarge" id="tel" name="tel">
						        <p class="help-block">Inserisci un numero di telefono sul quale sia possibile contattarti.</p>
						      </div>
						    </div>
						
							<div class="control-group">
								<label class="control-label" for="contact_reason">Tipologia Problema</label>
								<div class="controls">
									<select name="contact_reason" id="contact_reason">
										<option value="FALSE" disabled selected>-Scegli una voce-</option>
										<option value="tech">Problemi Tecnici</option>
										<option value="admin">Richiesta di Supporto</option>
										<option value="ask">Domande su IrisLogin</option>
										<option value="suggest">Suggerimenti</option>
										<option value="oth">Altro</option>
									</select>
									<p class="help-block">Seleziona il motivo del tuo contatto tra quelli elencati.</p>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="message">Messaggio</label>
								<div class="controls">
									<textarea class="input-xlarge" id="message" name="message" rows="5"></textarea>
									<p class="help-block">Non includere HTML nel testo altrimenti<br> la tua email verr&agrave; catalogata come spam<br> e potrebbe non essere consegnata.</p>
								</div>
							</div>

							<!-- <div class="form-actions">
								<button type="submit" class="btn btn-primary">Invia Richiesta</button>
								<button class="btn" id="issue-cancel-btn">Annulla</button>
							</div> -->
						  </fieldset>
						</form>						
					</div> <!-- .modal -->

				</div>
			<? endif; ?>
		</div><!-- /#footer -->
		
		<?php if (DEBUG OR TESTING): ?>
			<script type="text/javascript" charset="utf-8">
				$("#req_book").live('click',function(event) {
					event.preventDefault();
					wid = $('#website_num').val();
					value = $('#req_book').attr('href');

					if(wid != 0)
						$(location).attr('href', value.replace('SITE', wid));
					else
						alert('BOOKING: Insert a website id!!!');
				});
				
				$("#newsletter").live('click',function(event) {
					event.preventDefault();
					wid = $('#website_num').val();
					value = $('#newsletter').attr('href');

					if(wid != 0)
						$(location).attr('href', value.replace('SITE', wid));
					else
						alert('NEWSLETTER: Insert a website id!!!');
				});
			</script>
			<ul id="testing_links" class="clearfix">
				<li><a href="/">View Offers</a></li>
				<li><?=anchor('reservations/SITE', 'Request Booking', array('id' => 'req_book',))?></li>
				<li><?=anchor('newsletter/index/SITE', 'Subscribe', array('id' => 'newsletter',))?></li>
				<li><input type="text" id="website_num" value="0" size="2"></li>
			</ul>
		<?php endif ?>
		
	</div><!-- /#wrapper -->
</body>
</html>