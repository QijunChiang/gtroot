(function($) {
	$.fn.tab = function(options) {
		var _cfg = {
			firstIndex:0,
			index : -1,
			tabButtons : [ 'phoneTab', 'androidTab' ],// the tab buttons.
			tabPages : [ 'iphoneBtns', 'androidBtns' ],// the tab containers.
			method : 'mouseover',
			methodEnd : 'mouseout',
			isTimer : true,
			isFade : true,
			mouseOnFade:true,
			isMove : false,
			autoIndex:0,
			indexChanged:function(index,_cfg){
				if (index == 0){
					if(_cfg.isFade){
	                                    $('#androidPic').stop(true,true).animate({
	                                            left : "-=5px",
	                                            opacity: 0
	                                    }, 200);
	                                    $('#iphonePic').stop(true,true).delay(100).animate({
	                                        left : "-=5px",
	                                        opacity: 1
	                                    }, 200);
					}
					else
					{
						$('#iphonePic').show();
						$('#androidPic').css({
							opacity: 0
						});
					}
				}
				else {
					if(_cfg.isFade){
	                                    $('#iphonePic').stop(true,true).animate({
	                                            left : "+=5px",
	                                            opacity: 0
	                                    }, 200);
	                                    
	                                    $('#androidPic').stop(true,true).delay(100).animate({
	                                            left : "+=5px",
	                                            opacity: 1
	                                    }, 200);
					}
					else
					{
						$('#iphonePic').hide();
						$('#androidPic').css({
							opacity: 1
						});
					}
				}
			}
		};
		var This=this;
		if (options)
			$.extend(_cfg, options);
		
		if (jQuery.browser.msie/* &&parseFloat($.browser.version)<9.0 */) {
			_cfg.isFade = false;_cfg.mouseOnFade=false;
		}
		$('#androidPic').css({
			opacity: 0
		});
		
		var mutex = 0;
		var mouseOn = 0;
		var fn = {};
		var showTimer = null;
		fn.showIndex = function(index) {// set the tab index
			if(index<0)return;
			if(index==_cfg.index) return;
			
			//var j=index==_cfg.tabPages.length-1?0:index+1;
			for(var j=0;j<_cfg.tabPages.length;j++){
				if(j==index) continue;				
			if (_cfg.isFade) {
				This.find('#' + _cfg.tabPages[j]).stop(true,true).fadeOut("slow", "linear",
						function(i){
						});
			} else {
				This.find('#' + _cfg.tabPages[j]).hide();// hide the other containers
			}
			This.find('#' + _cfg.tabButtons[j]).removeClass('on');
			
			}
			
			if (_cfg.isFade) {
				mutex = 1;
				This.find('#' + _cfg.tabPages[index]).stop(true,true).fadeIn("slow", "linear",
						function() {
							mutex = 0;
						});
			} else {
				This.find('#' + _cfg.tabPages[index]).show();// show the selected container
			}
			This.find('#' + _cfg.tabButtons[index]).addClass('on');
			
			
			if(_cfg.indexChanged){_cfg.indexChanged(index,_cfg);}
			_cfg.index=index;
		};

		fn.fadeSwitch = function() {
			if (jQuery.browser.msie/* &&parseFloat($.browser.version)<9.0 */) {
				_cfg.isFade = false;_cfg.mouseOnFade=false;
			} else
				_cfg.isFade = true;

			if (_cfg.autoIndex == _cfg.tabPages.length - 1)
				_cfg.autoIndex = 0;
			else
				_cfg.autoIndex++;

			fn.showIndex(_cfg.autoIndex);
			_cfg.isFade = _cfg.mouseOnFade;
		};

		fn.showIndex(_cfg.firstIndex);//显示第一帧
		jQuery.each(_cfg.tabButtons, function(i, item) {
			This.find('#' + item).bind(_cfg.method, function(e) {
				mouseOn = 1;
				if (showTimer !== null) {
					clearInterval(showTimer);
					showTimer = null;
				}
				fn.showIndex(i);
			}).bind(_cfg.methodEnd, function(e) {
				mouseOn = 0;
				if (_cfg.isTimer) {
					if (showTimer === null) {
						showTimer = setInterval(fn.fadeSwitch, 6000);
					}
				}
			});
		});

		jQuery.each(_cfg.tabPages, function(i, item) {
			This.find('#' + item).bind(_cfg.method, function(e) {
				mouseOn = 1;
				if (showTimer !== null) {
					clearInterval(showTimer);
					showTimer = null;
				}
			}).bind(_cfg.methodEnd, function(e) {
				mouseOn = 0;
				if (_cfg.isTimer) {
					if (showTimer === null) {
						showTimer = setInterval(fn.fadeSwitch, 6000);
					}
				}
			});
		});
		if (_cfg.isTimer) {
			showTimer = setInterval(fn.fadeSwitch, 6000);
		}
		return this;
	};
})(jQuery);