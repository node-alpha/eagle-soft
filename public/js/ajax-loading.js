var ossAjaxEventHandle = {
	i_CountAjax : 0,
	a_ExceptUrl : [],
	/**
	 * Handle event ajax when start, send and stop
	 * 
	 */
	v_fInit: function(){
		$(document).ajaxSend( function(event, jqXHR, ajaxOptions){ 
			//this function using for disable url a_Exception
			$(ossAjaxEventHandle.a_ExceptUrl).each(function (key, sz_Url){
				if(ajaxOptions.url.toString().lastIndexOf(sz_Url.toString())>-1){
					ossAjaxEventHandle.v_fhideMaskForAjax();
				}
			});
		});
		$(document).ajaxStart(function(jqXHR, ajaxOptions){
			ossAjaxEventHandle.v_fshowMaskForAjax();
			ossAjaxEventHandle.i_CountAjax++;
		});
		$(document).ajaxStop(function(){
			ossAjaxEventHandle.i_CountAjax--;
			if(ossAjaxEventHandle.i_CountAjax <= 0){
				$(document).ready(function(){
					ossAjaxEventHandle.v_fhideMaskForAjax();
				});
			}
		});
		if($('div.loadmask').length <= 0)
		{
			$('<div class="loadmask"></div>').appendTo(document.body).hide();
			$('<div class="loadmask-msg"><div class="load-waiting"></div><div class="loading-msg">Loading...</div></div><div class="clr"></div>').appendTo(document.body).hide();
		}
	},
	v_fshowMaskForAjax : function(element, label){
		if(element == 'undefined' || !element)
			var element = $('body');
			
		var maskDiv = $('div.loadmask');
		var maskMsgDiv = $('div.loadmask-msg');
		$("div.loadmask-msg,div.loadmask").show();
		$("select.masked-hidden").show();

		//auto height fix for IE
	    maskDiv.height(element.height() + parseInt(element.css("padding-top")) + parseInt(element.css("padding-bottom")) + Math.round(maskMsgDiv.height()/2));
	    maskDiv.width(element.width() + parseInt(element.css("padding-left")) + parseInt(element.css("padding-right")));
		
		//fix for z-index bug with selects in IE6
		if(navigator.userAgent.toLowerCase().indexOf("msie 6") > -1){
			if(element.find("select.masked-hidden").length <=0){
				element.find("select").addClass("masked-hidden");
			}
			element.find("select.masked-hidden").removeClass('hide');
		}

		//calculate center position
		var windowHeight = $(window).height();
		var scrollTop = $(window).scrollTop();
		maskMsgDiv.css("top", scrollTop + Math.floor(windowHeight / 2) - Math.round(maskMsgDiv.height()/2)+"px");

		var windowWidth = $(window).width();
		var scrollLeft = $(window).scrollLeft();
		maskMsgDiv.css("left", scrollLeft + Math.floor(windowWidth / 2) - Math.round(maskMsgDiv.width()/2) +"px");

		maskMsgDiv.show();
		//change handle position on window resize
		$(window).unbind('resize').bind('resize', function(){
			ossAjaxEventHandle.v_fhideMaskForAjax();
			ossAjaxEventHandle.v_fshowMaskForAjax();
		});
		$(window).unbind('scroll').bind('scroll', function () { 
			ossAjaxEventHandle.v_fhideMaskForAjax();
			ossAjaxEventHandle.v_fshowMaskForAjax(); 
		});

	},
	v_fhideMaskForAjax: function(element){
		if(element == 'undefined' || !element)
			var element = $('body');
			
		$(window).unbind('resize');
		$(window).unbind('scroll');
		$("div.loadmask-msg,div.loadmask").hide();
		element.find("select.masked-hidden").hide();
	}
};
$(function(){ossAjaxEventHandle.v_fInit();})