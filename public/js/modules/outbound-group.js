OUTBOUND_GROUP = {
	addUrl : sz_PublicHost + 'did/outbound-group/add',
	searchUrl : sz_PublicHost + 'did/outbound-group/search',
	editUrl : sz_PublicHost + 'did/outbound-group/edit',
	saveUrl : sz_PublicHost + 'did/outbound-group/save',
	deleteUrl : sz_PublicHost + 'did/outbound-group/delete',
	init : function(){		
		$('#add_group').click(function(){
			OUTBOUND_GROUP.add();
		});
		$('#search').click(function(e){
			OUTBOUND_GROUP.search();
		});
		
		$('#clear-form').click(function(){
			$('#textfield').val('');
		});
		OUTBOUND_GROUP.search();
		$('a.close, #close-popup').live('click', function() { 
			$('#fade , .popup_block').fadeOut(function() { 
			$('#fade, a.close').remove(); //fade them both out 
			}); 
			return false; 
		}); 
	},
	add: function(){
		var $dataRequest = $('#form-add').find('input, select').serializeArray();
		ajaxCore.ajaxGetJson(
				OUTBOUND_GROUP.addUrl,
			$dataRequest,
			function(response){
				if (response.sz_Message)	
				{
					alert(response.sz_Message);
					$('#clear-form').click();
					OUTBOUND_GROUP.search();
				}
			},
			function(response){
				if (response.sz_Message)
				{
					alert(response.sz_Message);
				}
			});
	},
	
	search: function(){
		var $dataRequest = $('.filterbox').find('input, select').serializeArray(); 
		ajaxCore.ajaxGetHtml(
				OUTBOUND_GROUP.searchUrl,
			$dataRequest,
			function(response){
				$('#list-view tbody').html(response);
			},
			function(response){
				alert('have error !')
			});
	},
	edit : function(the_i_Id)
	{
		ajaxCore.ajaxGetHtml(
				OUTBOUND_GROUP.editUrl,
				{'id': the_i_Id},
				function(response){
					if (response)	
					{
						popID = 'popup_block';
						$('#' + popID + ' .popup_content').html(response);
						$('#' + popID).fadeIn().css({ 'width': Number( 510 ) }).prepend('<a href="#" class="close"><img src="'+ sz_PublicHost +'images/dialog_close.png" class="btn_close" title="Close" alt="Close" /></a>');
						var popMargTop = ($('#' + popID).height() + 10) / 2; 
						var popMargLeft = ($('#' + popID).width() + 80) / 2; 
						//Apply Margin to Popup 
						$('#' + popID).css({ 
						'margin-top' : -popMargTop,
						'margin-left' : -popMargLeft 
						});
						$('body').append('<div id="fade"></div>');
						$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
					}
				},
				function(response){
					if (response.sz_Message)	
					{
						alert(response.sz_Message);
					}
				});
		return false;
	},
	save: function(){
		var $dataRequest = $('#form-edit').find('input, select').serializeArray(); 
		ajaxCore.ajaxGetJson(
				OUTBOUND_GROUP.saveUrl,
			$dataRequest,
			function(response){
				if (response.sz_Message)	
				{					
					$('#close-popup').click();
					alert(response.sz_Message);
					OUTBOUND_GROUP.search();
				}
			},
			function(response){
				if (response.sz_Message)	
				{
					alert(response.sz_Message);
				}
			});
	},
	delete : function(i)
	{
		ajaxCore.ajaxGetJson(
				OUTBOUND_GROUP.deleteUrl,
				{id : i},
				function(response){
					if (response.sz_Message)	
					{
						alert(response.sz_Message);
						OUTBOUND_GROUP.search();
					}
				},
				function(response){
					if (response.sz_Message)	
					{
						alert(response.sz_Message);
					}
				});
		
	}
}
$(document).ready(function() { 
	OUTBOUND_GROUP.init();
}); 