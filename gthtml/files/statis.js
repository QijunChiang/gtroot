if(null!=$){
	$(function(){
		$('a').bind('click',function(e){
			if($(this).attr('href')!=''){
				var id=$(this).attr('id');
				if(null==id) id=$(this).html();
				_hmt.push(['_trackEvent', 'common_share', 'link_click_id:'+id, 'link_click _class:'+$(this).attr('class')+'|html:'+$(this).html()]);
			}
		});
	});
}