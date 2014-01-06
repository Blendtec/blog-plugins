;(function($){
	"use strict";

	var jpibfi_debug = false;

	$(document).ready( function() {

		var settings = {
			pageUrl        		: document.URL,
			pageTitle      		: document.title,
			pageDescription		: $('meta[name="description"]').attr('content') || "",
			siteTitle					: jpibfi_options.site_title,
			imageSelector			: jpibfi_options.image_selector,
			disabledClasses		: jpibfi_options.disabled_classes,
			enabledClasses		: jpibfi_options.enabled_classes,
			descriptionOption	: jpibfi_options.description_option,
			usePostUrl				: jpibfi_options.use_post_url == "1",
			minImageHeight		: jpibfi_options.min_image_height,
			minImageWidth			: jpibfi_options.min_image_width,
			mode							: jpibfi_options.mode,
			buttonPosition		: jpibfi_options.button_position
		}

		var pinButtonDimensions = {
			height: parseInt( jpibfi_options.pin_image_height ),
			width: parseInt( jpibfi_options.pin_image_width )
		}

		var pinButtonMargins = {
			top: parseInt( jpibfi_options.button_margin_top ),
			right: parseInt( jpibfi_options.button_margin_right ),
			bottom: parseInt( jpibfi_options.button_margin_bottom ),
			left: parseInt( jpibfi_options.button_margin_left )
		}

		jpibfi_debug = '1' == jpibfi_options.debug;

		jpibfiLog( settings );
		jpibfiLog( pinButtonDimensions );
		jpibfiLog( pinButtonMargins );

		var $containers = $('.jpibfi').closest('div').addClass('jpibfi_container');

		jpibfiLog( 'Number of containers added: ' + $containers.length );

		var notSelector = createSelectorFromList( settings.disabledClasses );
		//in case of no enabled classes, we need a selector that takes all elements
		var filterSelector = createSelectorFromList( settings.enabledClasses ) || "*";

		jpibfiLog( 'Image selector: ' + settings.imageSelector );
		jpibfiLog( 'Filter selector: ' + filterSelector );
		jpibfiLog( 'Not selector: ' + notSelector );

		var imageCount = 0;
		$( settings.imageSelector )
				.not( notSelector )
				.filter( filterSelector )
				.each( function (i) {	$( this ).attr('data-jpibfi-indexer', i); imageCount++; } );
		jpibfiLog( 'Images caught by selectors: ' + imageCount );

		//EVENT HANDLING

		if ( 'static' == settings.mode) {

			jpibfiLog( 'Adding static mode delegates');

			$( document).delegate( 'div.pinit-overlay', 'hover', function( event ) {
				var hover = event.type === 'mouseenter';
				var indexer = $(this).attr("data-jpibfi-indexer");
				$('.pinit-button[data-jpibfi-indexer="' + indexer + '"]').toggle( hover );
				$('img[data-jpibfi-indexer="' + indexer + '"]').toggleClass( 'pinit-hover', hover );
			});

		} else if ( 'dynamic' == settings.mode ) {

			jpibfiLog( 'Adding dynamic mode delegates');

			$( document ).delegate( 'a.pinit-button', 'mouseenter', function() {
				var $button = $( this );
				clearTimeout( $button.data('jpibfi-timeoutId') );
			});

			$( document ).delegate( 'a.pinit-button', 'mouseleave', function() {
				var $button = $( this );
				var timeoutId = setTimeout( function(){
					$button.remove();
					$('img[data-jpibfi-indexer="' + $button.attr( 'data-jpibfi-indexer' ) + '"]').removeClass( 'pinit-hover' );
				}, 100 );
				$button.data('jpibfi-timeoutId', timeoutId);
			});

			$( document ).delegate( 'img[data-jpibfi-indexer]', 'mouseenter', function() {
				var $image = $( this );
				var indexer = $image.attr( 'data-jpibfi-indexer' );

				var $button = $('a.pinit-button[data-jpibfi-indexer="' + indexer + '"]');

				if ( $button.length == 0 ) {
					//button doesn't exist so we need to create it
					var $button = jpibfiCreatePinitButton( indexer );
					var position = $image.offset();
					var imageDimensions = {
						width: $image.get(0).clientWidth,
						height: $image.get(0).clientHeight
					}

					switch( settings.buttonPosition ){
						case '0': //top-left
							position.left += pinButtonMargins.left;
							position.top += pinButtonMargins.top;
							break;
						case '1': //top-right
							position.top += pinButtonMargins.top;
							position.left = position.left + imageDimensions.width - pinButtonMargins.right - pinButtonDimensions.width;
							break;
						case '2': //bottom-left;
							position.left += pinButtonMargins.left;
							position.top = position.top + imageDimensions.height - pinButtonMargins.bottom - pinButtonDimensions.height;
							break;
						case '3': //bottom-right
							position.left = position.left + imageDimensions.width - pinButtonMargins.right - pinButtonDimensions.width;
							position.top = position.top + imageDimensions.height - pinButtonMargins.bottom - pinButtonDimensions.height;
							break;
						case '4': //middle
							position.left = Math.round( position.left + imageDimensions.width / 2 - pinButtonDimensions.width / 2 );
							position.top = Math.round( position.top + imageDimensions.height / 2 - pinButtonDimensions.height / 2 );
							break;
					}

					$image.after( $button );
					$button
							.show()
							.offset({ left: position.left, top: position.top });
				} else {
					//button exists, we need to clear the timeout that has to remove it
					clearTimeout( $button.data('jpibfi-timeoutId') );
				}

				$( 'img[data-jpibfi-indexer="' + $button.attr( 'data-jpibfi-indexer' ) + '"]' ).addClass( 'pinit-hover' );
			});

			$( document).delegate( 'img[data-jpibfi-indexer]', 'mouseleave', function() {
				var indexer = $(this).attr("data-jpibfi-indexer");
				var $button = $('a.pinit-button[data-jpibfi-indexer="' + indexer + '"]');

				var timeoutId = setTimeout(function(){
					$button.remove();
					$('img[data-jpibfi-indexer="' + $button.attr( 'data-jpibfi-indexer' ) + '"]').removeClass( 'pinit-hover' );
				}, 100 );
				$button.data('jpibfi-timeoutId', timeoutId);
			});

		}

		function jpibfiAddElements() {
			jpibfiLog( 'Add Elements called' );

			var imageCount = 0;
			$("img[data-jpibfi-indexer]").each(function () {
				var $image = $(this);

				if ( this.clientWidth < settings.minImageWidth || this.clientHeight < settings.minImageHeight ) {
					$image.removeAttr( 'data-jpibfi-indexer' );
					return;
				}
				if ( settings.mode == 'static' )
					jpibfiCreateAndShowOverlayDiv( $image, settings.buttonPosition );

				imageCount++;
			});
			jpibfiLog( 'Images caught after filtering: ' + imageCount );
		}

		function jpibfiRemoveElements() {
			jpibfiLog( 'Remove Elements called' );
			$( 'div.pinit-overlay' ).remove();
		}

		$(window).load( jpibfiAddElements );

		$(window).resize ( function() {
			jpibfiRemoveElements();
			jpibfiAddElements();
		});

		//UTILITY FUNCTIONS

		function jpibfiCreateAndShowOverlayDiv( $image, buttonPosition ) {
			var position = $image.offset();

			var $overlay = jpibfiCreateOverlayDiv( $image, buttonPosition );

			$image.after( $overlay );

			$overlay
					.css({
						height: $image.get(0).clientHeight + 'px',
						width: $image.get(0).clientWidth + 'px'
					})
					.show()
					.offset({ left: position.left, top: position.top });

			return $overlay;
		}

		//function creates an overlay div that covers the image
		function jpibfiCreateOverlayDiv( $image, buttonPosition ) {

			var indexer = $image.attr("data-jpibfi-indexer");

			return jQuery('<div/>', {
				"class": 'pinit-overlay',
				"data-jpibfi-indexer": indexer,
				title: $image.attr( 'title' )  || '',
				html: jpibfiCreatePinitButton( indexer).addClass( jpibfiButtonPositionToClass( buttonPosition ))
			})
		}

		function jpibfiCreatePinitButton( indexer ){

			var $anchor = jQuery('<a/>', {
				href: '#',
				"class": 'pinit-button',
				"data-jpibfi-indexer": indexer,
				text: "Pin It"
			});

			$anchor.click( function(e) {
				jpibfiLog( 'Pin In button clicked' );
				var index = $(this).attr("data-jpibfi-indexer");
				var $image = $('img[data-jpibfi-indexer="' + index+ '"]');

				//Bookmark description is created on click because sometimes it's lazy loaded
				var bookmarkDescription = "", descriptionForUrl = "", bookmarkUrl = "";

				//if usePostUrl feature is active, we need to get the data
				if ( settings.usePostUrl ) {
					var $inputWithData = $image.closest("div.jpibfi_container").find("input.jpibfi").first();

					if ( $inputWithData.length ) {
						descriptionForUrl = $inputWithData.attr("data-jpibfi-description")
						bookmarkUrl = $inputWithData.attr("data-jpibfi-url");
					}
				}
				bookmarkUrl = bookmarkUrl || settings.pageUrl;

				if ( settings.descriptionOption == 3 )
					bookmarkDescription = $image.attr('title') || $image.attr('alt');
				else if ( settings.descriptionOption == 2 )
					bookmarkDescription = descriptionForUrl || settings.pageDescription;
				else if ( settings.descriptionOption == 4 )
					bookmarkDescription = settings.siteTitle;
				else if ( settings.descriptionOption == 5 )
					bookmarkDescription = $image.attr( 'data-jpibfi-description' );

				bookmarkDescription = bookmarkDescription || ( descriptionForUrl || settings.pageTitle );

				var imageUrl = 'http://pinterest.com/pin/create/bookmarklet/?is_video=' + encodeURIComponent('false') + "&url=" + encodeURIComponent( bookmarkUrl ) + "&media=" + encodeURIComponent( $image.data('media') || $image[0].src )
						+ '&description=' + encodeURIComponent( bookmarkDescription );

				window.open(imageUrl, 'Pinterest', 'width=632,height=253,status=0,toolbar=0,menubar=0,location=1,scrollbars=1');
				return false;
			});

			return $anchor;
		}

	});

	//UTILITY FUNCTIONS

	//functions logs a message or object data if plugin runs in debug mode
	function jpibfiLog( o ) {
		if ( jpibfi_debug && console && console.log ) {
			if ( 'string' == typeof o || o instanceof String ) {
				console.log( 'jpibfi debug: ' + o );
			} else if ( 'object' == typeof o  && typeof JSON !== 'undefined' && typeof JSON.stringify === 'function' ) {
				console.log( 'jpibfi debug: ' + JSON.stringify( o, null, 4 ) );
			} else if ( 'object' == typeof o ) {
				var out = '';
				for (var p in o)
					out += p + ': ' + o[p] + '\n';
				console.log( 'jpibfi debug: ' + out );
			}
		}
	};

	//returns class name based on given button position
	function jpibfiButtonPositionToClass( buttonPosition ) {
		switch( buttonPosition ){
			case '0': return 'pinit-top-left';
			case '1': return 'pinit-top-right';
			case '2': return 'pinit-bottom-left';
			case '3': return 'pinit-bottom-right';
			case '4': return 'pinit-middle';
			default: return '';
		}
	}

	//function creates a selector from a list of semicolon separated classes
	function createSelectorFromList(classes) {
		var arrayOfClasses = classes.split( ';' );

		var selector = "";

		for (var i = 0; i < arrayOfClasses.length; i++) {
			if ( arrayOfClasses[i] )
				selector += '.' + arrayOfClasses[i] + ',';
		}

		if (selector)
			selector = selector.substr(0, selector.length - 1);

		return selector;
	}
})(jQuery);