ajaxCore = {
	ajaxGetJson : function(url, data, funcSuccess, funcFail){
		$.ajax({
			url: url,
			data: data,
			dataType: 'json',
			type: 'post',
			async: false,
			success: funcSuccess,
			error: funcFail
		});
	},
	
	ajaxGetHtml : function(url, data, funcSuccess, funcFail)
	{
		$.ajax({
			url: url,
			data: data,
			dataType: 'html',
			type: 'post',
			async: false,
			success: funcSuccess,
			error: funcFail
		});
	}
		
};