ajaxCore = {
	ajaxGetJson : function(url, data, funcSuccess, funcFail) {
		$.ajax( {
			url : url,
			data : data,
			dataType : 'json',
			type : 'post',
			async : false,
			success : function(response) {
				if (response.i_Code == 1) {
					funcSuccess.call(this, response);
				} else {
					funcFail.call(this, response);
				}
			},
			error : function(response) {
				funcFail.call(this, response);
			}
		});
	},
	ajaxGetHtml : function(url, data, funcSuccess, funcFail) {
		$.ajax( {
			url : url,
			data : data,
			dataType : 'html',
			type : 'post',
			async : false,
			success : function(response) {
				funcSuccess.call(this, response);
			},
			error : function(response) {
				funcFail.call(this, response);
			}
		});
	}
};