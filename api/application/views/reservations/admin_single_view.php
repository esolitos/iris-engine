<div class="content">
	<h2>Booking Request Details</h2>
	
	<?if($error):?>
		<span class="block_alert err"><?=$error?></span>
	<?endif;?>
	<?if($message):?>
		<span class="block_alert message"><?=$message?></span>
	<?endif;?>
	
	<div id="single_request">
		<h3>Informazioni di Contatto</h3>
		<span class="detail"><strong>ID Richiesta</strong>: <?=$book->id?></span><br/>
		<span class="detail"><strong>Nome</strong>: <?=$book->name?>&nbsp;<?=$book->surname?></span><br/>
		<span class="detail"><strong>eMail</strong>: <?=mailto($book->email, $book->email)?></span><br/>
		<span class="detail"><strong>Telefono</strong>: <?=$book->tel?></span><br/>
		
		<br/><h3>Informazioni di Prenotazione</h3>
		<span class="detail"><strong>Adulti</strong>: <?=$book->adults?></span><br/>
		<span class="detail"><strong>Bambini</strong>: <?=$book->babies?></span><br/>
		<span class="detail"><strong>Dal</strong> <?=$book->from?> <strong>al</strong> <?=$book->to_date?>.</span><br/>
		
		<br/><h3>Note</h3>
		<span class="detail"><?=$book->notes?></span><br/>
		
		<hr/>
		<h4>Altre Informazioni</h4>
		<span class="detail other"><strong>Iscritto alla Newsletter</strong>: <?=$book->newsletter?></span><br/>
		<span class="detail other"><strong>Richiesta inviata</strong>: <?=$book->time?></span><br/>
	</div>
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