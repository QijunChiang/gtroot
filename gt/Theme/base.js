/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @author jack
 * @date 2013.03.07
 */
/*路径配置*/
/*var  Path={
    web_path:"/goodteacher_web",
    image_path:"http://192.168.0.10/goodteacher"
};*/
var  Path={
    web_path:"http://gt.goodteacher.com",
    image_path:"http://gtapi.goodteacher.com"
};
/*当前页*/
var  curPage="";
var  cur_url="";
var pagesize = 20;
/*模板*/
var COMMONTEMP={
    /*输入框提示模板*/
    T0001:'<div class="tip"><span class="tri"></span><div class="msg"></div></div>',
    T0002:'<tr class="ops"><td colspan="{number}"><div class="loading_bar clearfix">'
    +   '<img src="'+Path.web_path+'/Theme/images/public/loading.gif" alt="" class="lf">'
    +  '<span class="lf">正在努力加载数据···</span>'
    + '</div></td></tr>',
    T0003:'<tr class="nodata ops"><td colspan="{number}">暂无相关数据</td></tr>'
}
/*语言包配置*/
var  lang={
    L0001:"请输入{msg}",
    L0002:"{msg}不能为空"
};
var pageTag={
    users:"tag0",
    agencys:"tag1",
    videos:"tag2",
    courses:"tag3",
    messages:"tag4",
    comments:"tag5",
    categorys:"tag6",
    notices:"tag7",
    apps:"tag8",
    ranges:"tag9",
    profile:"tag10"
};
/*按钮提交配置*/
var subConfig={
    /************************************登录 OK************************************/
    login:{
        bclick:function(){
            return loginpage.CheckAll();
        },
        url:Path.web_path + '/Auth/Login/dologin',
        sucrender:function(data){
            location.href =Path.web_path+'/categorys';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
    /************************************添加父分类************************************/
    add_parent_cate:{
         bclick:function(){
            return category_editpage.CheckAll();
        },
        url:Path.web_path + '/Category/CategoryEdit/doAddCategory',
        sucrender:function(){
            location.href =Path.web_path+'/categorys';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
    save_parent_cate:{
         bclick:function(){
            return category_editpage.CheckAll();
        },
        url:Path.web_path + '/Category/CategoryEdit/doSaveCategory',
        sucrender:function(){
            location.href =Path.web_path+'/categorys';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
    /************************************添加子分类************************************/
    add_child_cate:{
        bclick:function(){
            return subcate_editpage.CheckAll();
        },
        url:Path.web_path + '/Category/CategoryEdit/doAddSubCategory',
        sucrender:function(){
            var parent_id=$("#parent_id").val();
            var parent_name=$("#parent_name").val();
            location.href =Path.web_path+'/sub_categorys?id='+parent_id+'&name='+parent_name+'';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
    /************************************编辑子分类************************************/
    save_child_cate:{
        bclick:function(){
            return subcate_editpage.CheckAll();
        },
        url:Path.web_path + '/Category/CategoryEdit/doSaveSubCategory',
        sucrender:function(){
            var parent_id=$("#parent_id").val();
            var parent_name=$("#parent_name").val();
            location.href =Path.web_path+'/sub_categorys?id='+parent_id+'&parent_name='+parent_name+'';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
    /************************************添加机构/编辑机构************************************/
    add_org:{
         bclick:function(){
            return agency_editpage.checkAll();
        },
        url:Path.web_path + '/Agency/AgencyEdit/doAddAgency',
        sucrender:function(){
            location.href =Path.web_path+'/agencys';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
     uopdate_org_info:{
         bclick:function(){
            return agency_editpage.checkAll();
        },
        url:Path.web_path + '/Agency/AgencyEdit/doSaveAgency',
        sucrender:function(){
            location.href =Path.web_path+'/agencys';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
    },
   /************************************添加学生/编辑学生************************************/
   add_user_student:{
       bclick:function(){
            return add_studentpage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/doAddStudent',
        sucrender:function(){
            location.href =Path.web_path+'/students';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
   },
   update_stuInfo:{
        bclick:function(){
            return student_editpage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/doSaveStudent',
        sucrender:function(){
            location.href =Path.web_path+'/students';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
   },
   /************************************添加老师/编辑老师************************************/
   add_user_teacher:{
        bclick:function(){
            return add_teacherpage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/doAddTeacher',
        sucrender:function(){
            location.href =Path.web_path+'/teachers';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
   },
   update_teacher_info:{
        bclick:function(){
            return teacher_editpage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/doSaveTeacher',
        sucrender:function(){
            location.href =Path.web_path+'/teachers';
        },
        failrender:function(data){
            alert(data.data);
        },
        async:true
   }

};
var Base={
    /* 功能：异步后台方法访问
     * 参数：
     * settings：object 异步配置项参数
     * 格式：var settings={
     *          url:"",         //必需配置项，后台方法访问路径，不能为空
     *          params:"",      //必需配置项，后台方法参数，可为 ""
     *          sucrender:null, //异步成功后页面渲染方法对象，可为null
     *          failrender:null //异步失败后页面渲染方法对象，可为null
     *      };
     * 无
     */
    getKeyValue:function(par){
        par=$(par);
        var data="";
        var valinput=par.find("select[name^='fm-'],input[name^='fm-'][type!='radio'][type!='checkbox']"),
        radios=par.find("input[name^='fm-'][type='radio']"),
        checkbox=par.find("input[name^='fm-'][type='checkbox']"),
        textarea=par.find("textarea[name^='fm-']");
        var cur;
        $.each(valinput,function(i,o){
            cur=$(o);
            data+=cur.attr("name").split("fm-")[1]+"="+cur.val()+"&";
        });
        var temp={};
        $.each(radios,function(i,o){
            cur=$(o);
            var name=cur.attr("name").split("fm-")[1];
            if(!!!temp[name]){
                temp[name]=true;
                data+=name+"="+cur.parents(".formdata").find("input[type='radio'][name='fm-"+name+"']:checked").val()+"&";
            }
        });
        temp={};
        $.each(checkbox,function(i,o){
            cur=$(o);
            var name=cur.attr("name").split("fm-")[1];
            if(!!!temp[name]){
                temp[name]=true;
                var items = cur.parents(".formdata").find("input[type='checkbox'][name='fm-"+name+"']:checked");
                var options = "";
                $.each(items,function(m,obj){
                    options+=$(obj).val()+",";
                });
                options=options.substring(0,options.length-1);
                data+=name+"="+options+"&";
            }
        });
        $.each(textarea,function(i,o){
            cur=$(o);
            data+=cur.attr("name").split("fm-")[1]+"="+cur.val()+"&";
        });
        data=data.substring(0,data.length-1);
        //        alert(data);
        return data;
    },
    iniSubButton:function(){
        $(".submit").click(function(event){
            if(!$(this).hasClass("subbtnclicked")){
                var loading = "<img src='"+Path.web_path+"/Theme/images/public/loading.gif' class='loading'/><span class='waiting'>正在提交服务器，请等待...</span>";
                $(this).after(loading);
                $(this).addClass("subbtnclicked");
                var conf=subConfig[$(this).attr("id")];
                var hg=Base;
                var bl=true;
                if(!!conf&&!!conf.bclick){
                    bl=conf.bclick();
                    if(bl!=false){
                        bl=true;
                    }
                }else if(!!!conf || !!!conf.bclick){
                    bl=false;
                }
                if(bl){
                    hg.submit(this);
                }
                return true;
            }else{
                return false;
            }
        });
    },
    submit:function(obj){
        var hg=Base;
        var conf=subConfig[$(obj).attr("id")];
        var par=$(obj).parents(".formdata");
        conf.params=hg.getKeyValue(par);
        Base.AjaxRequest(conf);
    },
    resetButton:function(obj){
        if(!!!obj){
            $(".subbtnclicked").next().next().remove();
            $(".subbtnclicked").next().remove();
            $(".subbtnclicked").removeClass("subbtnclicked");
        }else{
            $(obj).removeClass("subbtnclicked");
            $(obj).after("");
        }
    },
    /*
     * 功能：在firefox下初始化文本框
     * 参数：
     * 无
     */
    IniInput:function(){
        if($.browser.mozilla){
            var inp=$("input[type='text'],textarea");
            $.each(inp,function(i,o){
                var a=$(o);
                a.val(a[0].defaultValue);
            });
        }
    },
    /*统一异步请求处理*/
    AjaxRequest:function(settings){
        var res=null;
        if(settings.async!=false){
            settings.async=true;
        }
        $.ajax({
            async: settings.async,
            type: "POST",
            url: settings.url+"",
            data: settings.params+"",
            dataType: "json",
            //            jsonp: "jsoncallback",
            success: function (json) {
                Base.resetButton();
                if(!!json){
                    var r=json;
                    if(typeof(r.result)=="undefined"){
                        r.ret=typeof(r.error)!="undefined"?false:true;
                    }
                    if(r.result==true){
                        var sr=settings.sucrender;
                        if(typeof(sr)!="undefined"&&sr!=null){
                            sr(r);
                        }
                    }
                    else{
                        var fr=settings.failrender;
                        if(typeof(fr)!="undefined"&&fr!=null){
                            fr(r);
                        }
                    }
                    if(!!!settings.async){
                        res=r;
                    }
                }
            },
            error:function(jqXHR, textStatus, errorThrown){
            //alert(textStatus+","+jqXHR.responseText);
            }
        });
        if(settings.async==false){
            return res;
        }
        return false;
    },
    /*
     * 功能：模板生成
     * 参数：
     * id：string 模板的父容器id
     * varr：Array 所有变量的名称的数组
     * arr：Array 模板中变量对应项的数据
     * tmp：string 模板编号
     */
    GenTemp:function(id,varr,arr,tmp){
        var dom = $("#"+id);
        if(dom.hasClass("hgstemp")){
            var temp=tmp.toString().replace(/[\n\r]/gm,'');
            var t='';
            var html='';
            $.each(arr,function(i,item){
                if(!!item){
                    t=temp;
                    $.each(varr,function(i,o){
                        t=t.replace(new RegExp("{"+o+"}","g"),item[o]);
                    });
                    html=html+t;
                }
            });
            if(dom[0].localName=="table"||dom[0].nodeName=="TABLE"){
                dom.find("td").parents("tr").remove();
                dom.append(html);
            }else{
                dom.html(html);
            }
        }
    },
    /*初始化当前页*/
    iniCurPage:function(){
        var cur=$("#pagename");
        var cpage;
        var page;
        if(cur&&!!cur.val()){
            page=cur.val();
        }else{
            page=location.href;
        }
        var start=page.lastIndexOf("/");
        cpage=page.substring(start+1).replace(/#/g,"").split("?")[0];
        curpage=cpage;
        var curTag = $("#pagetag").val();
        if(!!curTag){
            $("#left_menu").find("ul li[class='"+pageTag[curTag]+"'] a").addClass("cur");
        }
    },
    /*
     * 功能：初始化翻页插件
     * 参数：
     * id：分页插件的父容器id
     * t：总共的条数
     * id:翻译插件绑定id
     * func:翻页回调函数
     */
    iniPagination:function(t,id,func,size){
        if(!!!func){
            func=function(){};
        }
        if(!!!size){
            size=pagesize;
        }
        $(id).pagination(t, {
            callback: func,
            prev_text: "上一页",
            next_text: "下一页",
            items_per_page:size,
            num_display_entries:9,
            current_page:0,
            num_edge_entries:1,
            link_to:"javascript:;"
        });
    },
    /*退出系统*/
    exit:function(){
        $("#log_out").click(function(){
            var s={
                url:Path.web_path +"/Auth/Login/logout",
                params:"",
                sucrender:function(){
                    location.href=Path.web_path+"/login";
                },
                failrender:function(data){
                    alert(data.data);
                }
            };
            Base.AjaxRequest(s);
        });
    },
    /*初始化页面高度*/
    iniNavHeight:function(){
        var wh= document.body.scrollHeight;
        var wh1=screen.height;
        if(wh>wh1){
            $("#left_menu").height(wh);
        }else{
            $("#left_menu").height(wh1);
        }
    },
    /*
     * 功能：验证提示
     * 参数：
     * obj:提示对象
     * a：提示信息
     * b：提示的样式
     * c：提示距文本框的距离
     */
    tip:function(obj,a,b,c){
        $(obj).removeClass("green_border").removeClass("red_border");
        var p=$(obj).parent();
        if(!!!c){
            c=5;
        }
        if($(".tip").length!=0){
            p.find(".tip").remove();
        }
        if(b=="error"){
            $(obj).addClass("red_border");
        }else if(b=="right"){
            $(obj).addClass("green_border");
        }
        if($.browser.mozilla){
            p.css("display","block");
        }
        p.css("position","relative");
        var cur=null;
        p.append(COMMONTEMP.T0001);
        cur=p.find("div.tip");
        cur.find("div.msg").html(a);
        var pos = $.extend({}, $(obj).offset(), {
            width: $(obj)[0].offsetWidth,
            height: $(obj)[0].offsetHeight
        });
        var pos1=$.extend({}, p.offset(), {
            width: p[0].offsetWidth,
            height: p[0].offsetHeight
        });
        var actualWidth = cur[0].offsetWidth;
        if(actualWidth>260){
            actualWidth=260;
        }
        cur.css("width",actualWidth+"px");
        var actualHeight = cur[0].offsetHeight;
        cur.css({
            "left": (pos.width+(pos.left-pos1.left)+c)+"px"
        });
        actualHeight = cur[0].offsetHeight;
        cur.css({
            "top":((pos.top-pos1.top)+pos.height/2-actualHeight/2)+"px"
        });
        cur.addClass(b);
        var sp=cur.find("span.tri");
        sp.css("margin-top",actualHeight/2-12+"px");
        cur.css("visibility","visible");
    },
    /*
     * 功能：移除验证提示
     * 参数：
     * obj:提示对象
     */
    removeTip:function(obj){
        $(obj).parent().find("div.tip").remove();
        $(obj).removeClass("green_border").removeClass("red_border");
    },
    /*验证图片格式*/
    checkIspic:function(file){
        if(/^.*?\.(gif|png|jpg|jpeg|bmp)$/i.test(file))
            return true;
        else
            return false
    },
    /* 功能：存储cookie
     * 参数：
     * c_name：cookie的名称
     * value：cookie的值
     * expiredays：cookie存储时间
     */
    SetCookie:function(c_name,value,expiredays){
        var exdate=new Date()
        exdate.setDate(exdate.getDate()+expiredays)
        document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+";path=/";
    },
    /* 功能：读取cookie
     * 参数：
     * c_name：cookie的名称
     */
    GetCookie:function(c_name){
        if(document.cookie.length>0){
            var c_start=document.cookie.indexOf(c_name + "=");
            var c_end='';
            if(c_start!=-1){
                c_start=c_start + c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if (c_end==-1) c_end=document.cookie.length;
                return unescape(document.cookie.substring(c_start,c_end));
            }
        }
        return "";
    //     var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    //     if(arr != null) return unescape(arr[2]); return "";
    },
    /* 功能：删除cookie
     * 参数：
     * c_name：cookie的名称
     */
    DelCookie:function(c_name){
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval=HGS.Base.GetCookie(c_name);
        if(cval!=null) document.cookie= c_name + "="+cval+";expires="+exp.toGMTString()+";path=/";
    },
    moduleTip:function(){
        $("#main_menu li a[href='javascript:;']").click(function(){
            alert("模块暂未开放");
        });
    },

    /*输入验证*/
    inputValidate:function(id,rcont){
         $(id).focus(function(){
            Base.tip(this,lang.L0001.replace("{msg}",rcont), "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var org=cur.attr("org").replace(new RegExp(" ","g"),"");
            var b=true;
            if(str==""){
                if(org==""||typeof(org)=="undefined"){
                    msg=lang.L0002.replace("{msg}",rcont);
                    b=false;
                }else{
                    cur.val(org);
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        });
    },
    /*加载百度地图apijs文件*/
    loadMapScript:function(func){
        var inifunc=func;
        var script = document.createElement("script");
        script.src = "http://api.map.baidu.com/api?v=1.3&callback="+inifunc;
        document.body.appendChild(script);
    },
    /*初始化图片上传*/
    iniUpload:function(progress_id){
        var swfu;
        var param=$("#post_params").val();
        swfu = new SWFUpload({
            // Backend Settings
            upload_url: ""+Path.web_path+"/upload",
	    post_params: {"PHPSESSID" : param},
            file_size_limit : "2048",	// 2MB
            file_types : "*.jpg;*.gif;*.png",
            file_types_description : "JPG Images",
            file_upload_limit : "0",

            // Event Handler Settings - these functions as defined in Handlers.js
            //  The handlers are not part of SWFUpload but are part of my website and control how
            //  my website reacts to the SWFUpload events.
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,

            // Button Settings
            button_image_url : ""+Path.web_path+"/Theme/lib/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
            button_placeholder_id : "spanButtonPlaceholder",
            button_width: 180,
            button_height: 18,
            button_text : '<span class="button" style="margin-top:1px;">选择图片（最大不超过2MB）</span>',
            button_text_top_padding: 0,
            button_text_left_padding: 18,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            // Flash Settings
            flash_url : ""+Path.web_path+"/Theme/lib/swfupload/swfupload.swf",

            custom_settings : {
                upload_target : "divFileProgressContainer",
                progress_id:progress_id
            },

            // Debug Settings
            debug: false
        });
    }
};
$().ready(function(){
    var ba=Base;
    ba.iniCurPage();
    ba.iniSubButton();
    ba.IniInput();
    ba.exit();
//    ba.iniNavHeight();
    ba.moduleTip();
});