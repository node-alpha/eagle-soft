CUSTOMER_ACCOUNT = {
	createCustomerPath : sz_PublicHost + 'customer/index/create',
	
	init : function(){
		$('#create_button').click(function(){
			CUSTOMER_ACCOUNT.createCustomer();
		});
	},
	createCustomer: function(){
		$dataRequest = $('#customeraccountpopup').find('input, select').serializeArray(); 
		ajaxCore.ajaxGetJson(
			CUSTOMER_ACCOUNT.createCustomerPath,
			$dataRequest,
			function(response){
				if (response.sz_ErrorMessage)	
				{
					alert(response.sz_ErrorMessage);
				}
			},
			function(response){
				if (response.sz_ErrorMessage)	
				{
					alert(sz_ErrorMessage);
				}
			});
	}
}
$(function(){CUSTOMER_ACCOUNT.init();})
$(document).ready(function() { 
	// 
	$('a.poplight[href^=#]').click(function() { 
		$('#customeraccountpopup').find('input[type="text"], select').val('');
		var popID = $(this).attr('rel'); //Get Popup Name 
		var popURL = $(this).attr('href'); //Get Popup href to define size 
		var query= popURL.split('?'); 
		var dim= query[1].split('&'); 
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value 
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="'+ sz_PublicHost +'images/dialog_close.png" class="btn_close" title="Close" alt="Close" /></a>'); 
		var popMargTop = ($('#' + popID).height() + 10) / 2; 
		var popMargLeft = ($('#' + popID).width() + 80) / 2; 
		//Apply Margin to Popup 
		$('#' + popID).css({ 
		'margin-top' : -popMargTop,
		'margin-left' : -popMargLeft 
		}); 
		$('body').append('<div id="fade"></div>');
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) 
		return false; 
		}); 
		$('a.close, #fade').live('click', function() { 
		$('#fade , .popup_block').fadeOut(function() { 
		$('#fade, a.close').remove(); //fade them both out 
		}); 
		return false; 
	}); 
}); 