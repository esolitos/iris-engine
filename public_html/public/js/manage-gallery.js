// Check for settings, if not avaible set defaults
if( typeof galleries_iframe_id == 'undefined' ){ var galleries_iframe_id = "irislogin-galleries"; }
if( typeof gallery_iframe_id == 'undefined' ){ var gallery_iframe_id = "irislogin-single-gellery"; }
if( typeof gallery_iframe_wrapper_id == 'undefined' ){var gallery_iframe_wrapper_id = "irislogin-single-gellery-wrapper"; }

var wrapper_element = null;
var close_icon = null;

function getIE_Version()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}

function hide_gallery () {
	wrapper_element.slideUp('fast', function(){
		jQuery("#"+gallery_iframe_id).remove();
	});
}
		
function loadGallery(g_url){
	jQuery('<iframe/>').attr({
		src: g_url+"/standalone",
		style: "position:absolute; top:10%; left:10%; width:80%; height:80%; min-height:520px;min-width:400px;",
		frameborder: 'no',
		id: gallery_iframe_id,
	}).appendTo(wrapper_element);

    jQuery('#'+gallery_iframe_id).load(function(){
		wrapper_element.slideDown();
    });
};

function keyPressed(e) {
	if(e.keyCode == '27'){ //Sending event to parent
		e.preventDefault();
		hide_gallery();
	}
}

function onMessage (event) {
	galleries = document.getElementById(galleries_iframe_id);
	gallery = document.getElementById(gallery_iframe_id);
			
    if (event.source != galleries.contentWindow && (gallery != null && event.source != gallery.contentWindow)){ return; }
    var message = JSON.parse(event.data);

	if(message.url){
		loadGallery(message.url);
	}
	else if(message.keyCode && message.keyCode == 27) {
		hide_gallery();
	}
}

jQuery().ready(function(){
	// Create the wrapper
	wrapper_element = $('<div/>').attr({
		id: gallery_iframe_wrapper_id,
		style: "position: absolute;top:0;bottom:0;left:0;right:0;height:100%;width:100%;display: none;background-color:black;background-color:rgba(0,0,0,0.7);",
	}).appendTo("body");
	
	// Create closing icon
	close_icon = jQuery("<img />").attr({
		src:"http:///irislogin.it/public/img/btn-del-small.png",
		style: "position: absolute; top:9%;left:9%;z-index:1000;",
	}).appendTo(wrapper_element);
	
	close_icon.bind('click', function(){
		hide_gallery();
	});
	
	if(getIE_Version() > -1 ){
		script = jQuery("<script>").attr({
			src: "http:///irislogin.it/public/js/JSON.js",
			type: "text/javascript"
		}).appendTo("body");	
	}
	
	// Finally add the listenbers
	if (window.attachEvent)
	{
	    window.attachEvent('onmessage', onMessage);
	    window.attachEvent('keydown', keyPressed);
	}
	else if (window.addEventListener){
	    window.addEventListener('message', onMessage, false);
	    window.addEventListener('keydown', keyPressed, false);
	}
});