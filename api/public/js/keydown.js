jQuery().ready(function(){
	jQuery('body').bind("keydown keypress", function(e) {
		if(e.keyCode == '27'){
			e.preventDefault();				
			var message = {keyCode: e.keyCode};
			
			console.log("Pressed: "+e.keyCode);
					
			window.parent.postMessage(JSON.stringify(message),'*');					
		}
	});
});
