$.fn.usercart = {
    options : {
        rootPath : '/gtweb'
    },
    addToCart : function(courseId,itemNum){
        $.ajax({
            url : $.fn.usercart.options.rootPath+"/user/myCart.php",
            type : 'POST',
            data : 'action=add_to_cart&courseId='+courseId+'&itemNum='+itemNum+'&t='+(new Date()).getTime(),
            success : function(result){
                window.location = $.fn.usercart.options.rootPath+"/user/myCart.php";
            },
            error:function(result){                
            }
        });
    },
    submitOrder : function(){
        window.location = $.fn.usercart.options.rootPath+"/user/myCart.php?action=submit_order";
    }
};