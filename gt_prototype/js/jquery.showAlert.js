/**
 * 官网弹出提示框插件
 *
 * jQuery showAlert plugin
 *
 * @name jquery.showAlert.js
 * @version 1.0
 * @author jamieTsnag
 * @date 2013-08-15
 * @Email:331252914@qq.com
 * @QQ:331252914
 *
 *
 **/
$(function(){
	$.fn.showAlert=function(settings){
		settings=jQuery.extend({
			opacity:.5,//透明度
			alertWidth:420,//弹出框宽度
			alertHeight:null,//弹出框高度
			alertObject:$("#alertBox")
		},settings);
		
		var body=$('body');
		var btnObject = this;
		var alertObjectSelector=settings.alertObject.selector;
		var clientWidth,clientHeight,alertLeft,alertTop;
		var ieOpacity=settings.opacity*100;
		var version=$.browser.version;
		var alertHeight=settings.alertHeight==null?settings.alertObject.outerHeight():settings.alertHeight;
		
		function objectSize(){
			clientWidth=$(window).width();
			clientHeight=$(window).height();
			maxHeight=clientHeight*0.9;		
			if($.browser.version=="6.0"){
				alertTop=$(document).scrollTop()+clientHeight/2-alertHeight/2;
			}else{
				alertTop=alertHeight<maxHeight?clientHeight/2-alertHeight/2:clientHeight/2-maxHeight/2;
			}
			alertLeft=clientWidth/2-settings.alertWidth/2;
			//console.log(alertHeight);
		}
		
		$(window).resize(function(){
			objectSize();
			$('#overlay').css('height',clientHeight);
		});
		
		function initial(){
			body.css('overflwo','hidden');
			objectSize();
			var overlayHTML='<div id="overlay" class="alertClose" style="z-index:9999999; width:100%; position: fixed; top: 0px; left: 0px; opacity:'+settings.opacity+'; height: '+clientHeight+'px; background-position: initial initial; background-repeat: initial initial;display:none;filter:alpha(opacity='+ieOpacity+');background:#000;cursor:pointer;';
			if(version=="6.0"){
				overlayHTML+=' _background:none;"></div>';
			}else{
				overlayHTML+='"></div>';
			}
			
			body.append(overlayHTML);
			settings.alertObject.css({
				'width':settings.alertWidth,
				'max-height':maxHeight,
				'left':alertLeft,
				'top':alertTop,
				'*top':'expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-'+(alertTop+200)+'))'
			});
			$('#overlay,'+alertObjectSelector).css('display','block');
			$('.alertClose,#close').click(function(){
				$('#overlay,'+alertObjectSelector).fadeOut(300,function(){$('#overlay').remove()});
			});
			$(window).resize(function(){
				objectSize();
				$('#overlay').width(clientWidth);
				settings.alertObject.css({'left':alertLeft,'top':alertTop});
			});
		};
		
		return initial();
	}

});