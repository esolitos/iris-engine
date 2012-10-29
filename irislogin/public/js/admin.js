$(".toggle-example").live('click', function(ev){
	$(".example").slideToggle();
	ev.preventDefault()
})