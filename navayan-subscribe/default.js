if ( typeof jQuery != 'undefined'){
	jQuery(function($){
		
		$('#nySubscribeTabs a').each(function(){
			var a = $(this),
				aHash = '',
				blocks = 'nySubscribeBlocks';
			if ( a.hasClass('on') ){
				aHash = a.attr('href').split('#');
				$('#' + blocks + ' > div').hide();
				$('#' + blocks + ' div#' + aHash[1] ).show();
			}
			
			a.click(function(e){
				if ( !$(this).hasClass('donatelink') ){
					e.preventDefault();
					aHash = a.attr('href').split('#');
					$('#' + blocks + ' > div').hide();
					$('#nySubscribeTabs a').removeClass('on');
					$('#' + blocks + ' div#' + aHash[1] ).show();
					a.addClass('on');
				}
			});
		});
		
		function valEmail(field){
			var f = field, fval = f.val();
			with (f){
				if(fval != ''){
					apos = fval.indexOf('@');
					dotpos = fval.lastIndexOf('.');
					if (apos < 1 || dotpos-apos < 2){
						return false;
					}else{
						return true;
					}
				}
			}
		}
		
		$('form#ny_subscribe_form input#ny_subscribe_submit').click(function(){
			var myForm = $('form#ny_subscribe_form'),
                myName = $('input#ny_name', myForm),
                myEmail = $('input#ny_email', myForm),
                myCustom = $('input#ny_custom', myForm),
                appendMsg = $('<p id="nySubscribeMsg"></p>');
			
			if ( myName && myName.val() == '' ){
				formError( appendMsg, myName ); return false;
			}else if ( myEmail && myEmail.val() == '' ){
				formError( appendMsg, myEmail ); return false;
			}else if ( myEmail.val() != '' && !valEmail ( myEmail ) ){
				formError( appendMsg, myEmail ); return false;
			}else if ( myCustom && myCustom.val() == '' ){
				formError( appendMsg, myCustom ); return false;
			}else{
				$('#ny_subscribe_wrapper p#nySubscribeMsg').remove();
				appendMsg.insertBefore( myForm ).addClass( 'nys-error' ).text( 'processing...' );
				return true;
			}
		});
		
		function formError( ErrorE, toFocus ){
			$('#ny_subscribe_wrapper p#nySubscribeMsg').remove();
			ErrorE.insertBefore( $('form#ny_subscribe_form') ).addClass( 'nys-error' ).text( toFocus.attr('rel') );
			toFocus.focus();
		}
	});
}