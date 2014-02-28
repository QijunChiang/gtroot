$.fn.teachers={
	options : {
        rootPath : '/gtweb',
        loginedRedirectUrl : 'http://localhost/gtweb'
    },
    goPrevPage : function(){
    	var currPage = $('input[name="currPage"]').val();
    	currPage--;
    	var currOrder = $('input[name="currOrder"]').val();
    	var currCounty = $('input[name="currCounty"]').val();
    	$.fn.teachers.getTeachers(currPage, currOrder, currCounty);
    },
    goNextPage : function(){
    	var currPage = $('input[name="currPage"]').val();
    	currPage++;
    	var currOrder = $('input[name="currOrder"]').val();
    	var currCounty = $('input[name="currCounty"]').val();
    	$.fn.teachers.getTeachers(currPage, currOrder, currCounty);
    },
    teachersOrder : function(){
    	var currCounty = $('input[name="currCounty"]').val();
    	$.fn.teachers.getTeachers(1, $(this).attr('order'), currCounty);
    },
    teachersCounty : function(){
    	var currOrder = $('input[name="currOrder"]').val();
    	$.fn.teachers.getTeachers(1, currOrder, $(this).attr('county'));
    },
    getTeachers : function(page, currOrder, currCounty){
     	window.location = $.fn.teachers.options.rootPath 
     	+ '/teachers.php?page='+page+'&currOrder='+currOrder+"&currCounty="+currCounty;
    }
    

}