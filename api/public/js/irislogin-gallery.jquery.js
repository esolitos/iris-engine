// $('div').tooltip(); // calls the init method
// $('div').tooltip({  // calls the init method
//   foo : 'bar'
// });
// $('div').tooltip('hide'); // calls the hide method
// $('div').tooltip('update', 'This is the new tooltip content!'); // calls the update method

(function( $ ){
	
	var defaults = {
		debug: false,
		// These are the defaults.
		irisLoginAPI: "http://api.irislogin.it/",
		irisLogin: "http://irislogin.it/",
		galleries: [],
		websiteID: 0,
		gallerySelected: 0,
		startFrom: 0,
		thumbs_visible: 5,
		autoPlay: false,
		
		// HTML options
		wrapperID: false,
		galleryID: "irislogin-gallery-",
		galleriesClass: "irislogin-galleries-wrapper",
		
		slideshowBackdropID: "slideshow-overall-backdrop",
		slideshowWrapperID: "slideshow-wrapper",
		slideshowCaruselID: "slideshow-carusel",
		slideshowThumbnailID: "slideshow-thumb"
	}

    var methods = {
        init : function(options) {
			var settings = defaults = $.extend(defaults, options );
			
			// If we don't have a website ID set we can't get the images and build the galleries
			// so we cheack fort that otherwise we throw and error.
			if(settings.websiteID != 0) {
				$.ajax({
					// Getting the website data trough JSONP
					url: settings.irisLoginAPI +'gallery/'+ settings.websiteID +'?callback=?',
					type: 'GET',
					dataType: 'jsonp',
					success: function(data, textStatus, xhr) {
						// If everything is right the server should have returned data.result as TRUE or
						// a positive number, so we check for that and if that's right we call the build method.
						if( data.result ) {
							methods.build(data.data.galleries);
						}
						else {
							alert("Galleries not loaded. Check console for more informations.")
							
							if(defaults.debug) {
								console.log("IrisLoginGallery: ERROR malformed data received!");
								console.log(data);
							}
						}
						
					},
					error: function(xhr, textStatus, errorThrown) {
						document.console.log("IrisLoginGallery: ERROR while getting data.")
						
						if(defaults.debug) {
							console.log(xhr);
							// console.log(textStatus);
							console.log(errorThrown);
						}
					}
				});
			}
			else {
				document.console.log("IrisLoginGallery: ERROR No website ID given.");
			}
			
        },
		
		
		build : function(galleries, options) {

			setup = $.extend(defaults, options);

			if( ! setup.wrapperID ) {
				alert("Wrong Setup. Check console for more informations.");
				
				console.log("Wrapper ID Not set.");
			}
			else {
				// Loop trough galleries
				for (var gID = galleries.length - 1; gID >= 0; gID--) {
					var currGallery = galleries[gID];
					var gallID = setup.galleryID + currGallery.gid;
					
					wrapper = $("<DIV/>").attr({
						id: gallID
					}).css({
						width: '90%',
						'min-width': '350px',
						'max-width': '1050px',
						margin: '50px auto'
					});
					
					gall = $("<div/>").attr({
						class : setup.galleriesClass,
						'data-gid': currGallery.gid
					}).css({
						'text-align': "center",
						margin: "20px 0px 0px 10px"
					});
					
					for (var j = currGallery.images.length - 1; j >= 0; j--) {
						imageData = currGallery.images[j];
						
						img = $("<img/>").attr({
							class: "gallery-image",
							title: imageData.title,
							alt: imageData.descr,
							src: setup.irisLogin + imageData.thumb,
							'data-large': setup.irisLogin + imageData.full_size,
							'data-gid': currGallery.gid,
							'data-imgID': imageData.id
						}).bind('click', function(ev){
							// $me = $(this);
							methods.start(this.getAttribute('data-imgid'), this.getAttribute('data-gid'));

						}).css({
							display: 'inline-block',
							border: '1px solid #ccc',
							padding: '12px',
							margin: '0px 20px 20px 0px'
						});
						
						img.appendTo(gall);
					}
					
					gall.appendTo(wrapper);
					wrapper.appendTo("#"+setup.wrapperID);
						
				}
				
				
				
			}
		},
        
		
		start : function( imgID, gID, options ) {
			
			setup = $.extend(defaults, options);
			var gallID = setup.galleryID+gID;
			var $carus = null;
			var $thumbs = null;
			
			$backdrop = $("<div/>").attr({
				id: setup.slideshowBackdropID
			}).css({
				position: 'absolute',
				top: 0,
				bottom: 0,
				right: 0,
				left: 0,
				width: '100%',
				height: '100%',
				'background-color': '#FAFAFA',
				'background-color': '#FAFAFA',
				'z-index': 5000,
				opacity: 0
			});

			$backdrop.click(function(){
				methods.stop();
				methods.destroy();
				// this.parentNode.removeChild(this);
			});
			
			var wrapperSize = {position:{}};
			wrapperSize.width = window.innerWidth * 0.8;
			wrapperSize.height = window.innerHeight * 0.8;
			wrapperSize.position.top = (window.innerHeight / 2) - (wrapperSize.height /2);
			wrapperSize.position.right = (window.innerWidth / 2) - (wrapperSize.width /2);
			
			var $wrapper = $("<div/>").attr({
				id: setup.slideshowWrapperID
			}).css({
				position: "absolute",
				width: wrapperSize.width,
				height: wrapperSize.height,
				top: wrapperSize.position.top,
				left: wrapperSize.position.right,
				
				'background-color': "#000",
				'box-shadow': "0 20px 50px #333",
				'z-index': 5500,
				'overflow': "hidden",
				opacity: 0
			});
			
			$thumbs = $("#"+gallID +" ."+ setup.galleriesClass).clone().removeClass(setup.galleriesClass);
			$thumbs.attr({
				id: setup.slideshowThumbnailID,
				style: false
			}).css({
				height: "120px",
				overflow: "hidden",
				position: "relative",
				bottom: "50px",
				'min-width': "250px",
				'z-index': 6500
			}).find('img').each(function(){
				this.removeAttribute("data-large");
				this.removeAttribute("style");
				
				$(this).css({
					display: "block",
					float: "left",
					margin: "10px",
					width: "100px",
					height: "100px",
					'box-shadow': "0 0 10px #000"
				});
			});

			$carus = $("#"+gallID +" ."+ setup.galleriesClass).clone().removeClass(setup.galleriesClass);
			
			
			$carus.attr({
				id: setup.slideshowCaruselID,
				style: false
			}).css({
				overflow: "hidden",
				'z-index': 6000
			}).find('img').each(function(idx){
				if(this.src) {					
					this.onload = function() {
						// this.setAttribute('width', this.width);
						// this.setAttribute('height', this.height);
						// var $cache = $("#"+setup.slideshowWrapperID);
						// 
						// var ratio = this.width / this.height;
						// var h = Math.min($cache.innerHeight(), this.height);
						// var w = h * ratio;
						// 
						// if( w > $cache.innerWidth() )
						// {
						// 	ratio = this.height / this.width;
						// 	
						// 	w = $cache.innerWidth();
						// 	h = w * ratio;
						// }
						// 
						// this.setAttribute(width, w);
						// this.setAttribute(height, h);
						this.removeAttribute("style");
						this.setAttribute('width', 'auto');
						this.setAttribute('height', $("#"+setup.slideshowWrapperID).innerHeight());
						
						this.style.display = 'block';
						this.style.float = 'left';
						
						// $(this).css({
						// 	width: 'auto',
						// 	height: $("#"+setup.slideshowWrapperID).innerHeight()
						// });
					}
					
					this.src = this.getAttribute("data-large");
					this.removeAttribute("data-large");
				}
			});
			
			
			$wrapper.append( $carus );
			$wrapper.append( $thumbs );
			// $wrapper.appendTo( $backdrop );

			$('body').append($backdrop);
			$('body').append($wrapper);
						
 
			// console.log($('#'+setup.slideshowThumbnailID+' img[data-imgid='+ imgID +']').index());
 
			$carus = null;
			$thumbs = null;
			
			$carus = $("#"+setup.slideshowCaruselID);
			$thumbs = $("#"+setup.slideshowThumbnailID);
			
			setTimeout(function () {
				// $("#"+setup.slideshowCaruselID).carouFredSel({items:1},{debug:true});
			
				$carus.carouFredSel({
					width: "100%",
					height: "100%",
					items: {
						start: $('#'+setup.slideshowCaruselID+' img[data-imgid='+ imgID +']'),
						visible: 1,
						height: 'variable',
						width: 'variable'
					},
					scroll: {
						fx: 'crossfade',
						onBefore: function( data ) {
							imgID = data.items.visible.data('imgid');
							pos = Math.floor(setup.thumbs_visible / 2) - 1;
							
							$thumbs.trigger( 'slideTo', [ $('#'+setup.slideshowThumbnailID+' img[data-imgid='+ imgID +']'), - pos  ] );
						}
					},
					prev: {key: 'left'},
					next: {key: 'right'},
					onCreate: function(data) {
						$wrapper.css({
							height: data.height
						});
						
						$backdrop.animate({opacity: 1});
						$wrapper.animate({opacity: 1});
					}
				});
			 
				$thumbs.carouFredSel({
					auto: false,
					width: '100%'
				});
					 
				$thumbs.children('img').click(function() {
					imgID = this.getAttribute('data-imgid');
					selector = '#'+setup.slideshowCaruselID+' img[data-imgid='+ imgID +']';
					
					$carus.trigger( 'slideTo', [ $(selector) ] );
				}).css( 'cursor', 'pointer' );

				$wrapper.hover(
					function() {
						$carus.trigger( 'pause' );
						$thumbs.parent().animate({bottom: "120"});
					}, function() {
						$carus.trigger( 'play' );
						$thumbs.parent().animate({bottom: "30"});
					}
				);
				
				setup.thumbs_visible = $thumbs.children('img').size();
				$carus.trigger("play", [0, true]);
				
			
			}, 150);
        },
        
		
		stop : function( ) {
        	
			$("#"+setup.slideshowCaruselID).trigger('stop');
			$("#"+setup.slideshowThumbnailID).trigger('stop');
        },
        
		
		update : function( content ) {
			
			$("#"+setup.slideshowCaruselID).trigger('updateSizes');
			$("#"+setup.slideshowThumbnailID).trigger('updateSizes');
		},
		
		
		destroy: function(options) {
			setup = $.extend(defaults, options);
			var wrapper = document.getElementById(setup.slideshowWrapperID);
			var bg = document.getElementById(setup.slideshowBackdropID);
			
			$(wrapper).animate({opacity:0}, 200);
			$(bg).animate({opacity:0}, 200, function() {
				$("#"+setup.slideshowCaruselID).trigger('destroy');
				$("#"+setup.slideshowThumbnailID).trigger('destroy');
			});
			
			setTimeout(function () {
				wrapper.parentNode.removeChild(wrapper);
				bg.parentNode.removeChild(bg);
			}, 200);
		}
    };

    $.fn.irisLoginGallery = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.irisLoginGallery' );
        }    
    };


})( jQuery );