/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @author jack
 * @date 2013.03.07
 */

/*路径配置*/

/*var  Path={
     web_path:"/goodteacher_web",
     image_path:"http://192.168.0.33/goodteacher"
};*/

/*var  Path={
   web_path:"http://gt.higgses.com",
   image_path:"http://gtapi.higgses.com"
};*/

var  Path={
   web_path:"/gt",
   image_path:"http://www.kaopuu.com/gtapi"
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
    T0003:'<tr class="nodata ops"><td colspan="{number}">暂无相关数据</td></tr>',
    T0004:'<div class="video_player">'
        +    '<div class="close"><a href="javascript:;"></a></div>'
        +   '<div id="video"><div id="a1"></div></div>'
        + '</div>'
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
    profile:"tag10",
    logs:"tag11",
    regions:"tag12",
    feedbacks:"tag13"
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
            location.href =Path.web_path+'/profiles';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
    },
     uopdate_org_info:{
         bclick:function(){
            return agency_editpage.checkAll();
        },
        url:Path.web_path + '/Agency/AgencyEdit/doSaveAgency',
        sucrender:function(){
            var name=$("#prame_name").val();
            location.href =Path.web_path+'/agencys?name='+name+'';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
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
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
   },
   /************************************添加课程视频/编辑课程视频************************************/
   add_courese_video:{
        bclick:function(){
            return video_editpage.checkAll();
        },
        url:Path.web_path + '/Video/VideoEdit/doAddVideo',
        sucrender:function(){
            alert("添加成功");
            location.href =Path.web_path+'/videos';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
   },
   update_video_info:{
        bclick:function(){
            return video_editpage.checkAll();
        },
        url:Path.web_path + '/Video/VideoEdit/doSaveVideo',
        sucrender:function(){
            alert("操作成功");
            location.href =Path.web_path+'/videos';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
   },
   /************************************添加培训课程/编辑培训课程************************************/
   add_course:{
       bclick:function(){
            return course_editpage.checkAll();
        },
        url:Path.web_path + '/Course/CourseEdit/doAddCourse',
        sucrender:function(){
            alert("操作成功");
            location.href =Path.web_path+'/courses';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
   },
   update_course_info:{
       bclick:function(){
            return course_editpage.checkAll();
        },
        url:Path.web_path + '/Course/CourseEdit/doSaveCourse',
        sucrender:function(){
            alert("操作成功");
            location.href =Path.web_path+'/courses';
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true
   },
   /************************************修改密码************************************/
   update_pwd:{
        bclick:function(){
            return profilespage.checkAll();
        },
        url:Path.web_path + '/Profile/ProfileEdit/doUpdatePassword',
        sucrender:function(){
            alert("操作成功");
            location.reload();
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************添加/编辑系统消息************************************/
   add_sys_notice:{
        bclick:function(){
            return sys_noteditpage.checkAll();
        },
        url:Path.web_path + '/Notice/NoticeEdit/doAddSystemNotice',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/notices";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   update_sys_notice:{
       
        bclick:function(){
            return sys_noteditpage.checkAll();
        },
        url:Path.web_path + '/Notice/NoticeEdit/doUpdateSystemNotice',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/notices";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************添加/编辑推广消息************************************/
   add_promote_msg:{
        bclick:function(){
            return prom_noteditpage.checkAll();
        },
        url:Path.web_path + '/Notice/NoticeEdit/doAddPromoteNotice',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/prom_notices";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   update_promote_msg:{
       
        bclick:function(){
            return prom_noteditpage.checkAll();
        },
        url:Path.web_path + '/Notice/NoticeEdit/doUpdatePromoteNotice',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/prom_notices";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************查询范围************************************/
   set_ranges:{
        bclick:function(){
            return range_editpage.checkAll();
        },
        url:Path.web_path + '/Range/SearchRangeEdit/doSetSearchRadius',
        sucrender:function(){
            alert("操作成功");
            location.reload();
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************应用版本管理************************************/
   add_new_appedtion:{
        bclick:function(){
            return appedtion_editpage.checkAll();
        },
        url:Path.web_path + '/AppEdtion/ApplicationEdtionEdit/doAddNewAppEdtion',
        sucrender:function(){
            alert("添加成功");
            location.href=Path.web_path+"/apps";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   update_appedtion:{
        bclick:function(){
            return appedtion_editpage.checkAll();
        },
        url:Path.web_path + '/AppEdtion/ApplicationEdtionEdit/doUpdateAppEdtion',
        sucrender:function(){
            alert("修改成功");
            location.href=Path.web_path+"/apps";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************行政区域管理************************************/
   add_xz_region:{
        bclick:function(){
            return region_editpage.checkAll();
        },
        url:Path.web_path + '/Region/RegionEdit/doAddRegion',
        sucrender:function(){
            alert("添加成功");
            location.href=Path.web_path+"/regions";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   save_xz_region:{
        bclick:function(){
            return region_editpage.checkAll();
        },
        url:Path.web_path + '/Region/RegionEdit/doUpdateRegion',
        sucrender:function(){
            alert("修改成功");
            location.href=Path.web_path+"/regions";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   /************************************添加老师证书************************************/
   add_teacher_certifi:{
       bclick:function(){
            return teacher_filepage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/do_teacher_file_edit',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/teachers";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   },
   update_teacher_certifi:{
       bclick:function(){
            return teacher_filepage.checkAll();
        },
        url:Path.web_path + '/User/UserEdit/do_teacher_file_edit',
        sucrender:function(){
            alert("操作成功");
            location.href=Path.web_path+"/teachers";
        },
        failrender:function(data){
           if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
            }else{
                alert(data.data);
            }
        },
        async:true 
   }
};
var Base={
    /*
     *省市区选择
     */
    iniPlace:function(){
        var par=$("div.place");
        par.each(function(i,o){
            $(o).find("select.prov").live("change",function(){
                var city=$(o).find("select.city"),area=$(o).find("select.area"),town=$(o).find("select.town");
                city.html("<option value='-1'> - 请选择市 - </option>");
                area.html("<option value='-1'> - 请选择区 - </option>");
                town.html("<option value='-1'> - 请选择商区 - </option>");
                var id=$(this).val();
                if(id!="0"){
                    Base.getArea("市",city,id);
                }
                 $("#distan,#mark").addClass("hidden");
                  Base.removeTip("#range");
                  $("#range").val("");
                  $("#latitude").val("");
                  $("#longitude").val("");
            });
            $(o).find("select.city").live("change",function(){
                var area=$(o).find("select.area");
                area.html("<option value='-1'> - 请选择区 - </option>");
                var town=$(o).find("select.town");
                town.html("<option value='-1'> - 请选择商区 - </option>");
                var id=$(this).val();
                if(id!="-1"){
                    Base.getArea("区",area,id);
                }
               $("#distan,#mark").addClass("hidden");
                  Base.removeTip("#range");
                  $("#range").val("");
                  $("#latitude").val("");
                  $("#longitude").val("");
            });
            $(o).find("select.area").live("change",function(){
                var level=par.attr("level");
                    var town=$(o).find("select.town");
                    town.html("<option value='-1'> - 请选择商区 - </option>");
                    var id=$(this).val();
                    if(id!="-1"){
                        if(level=="4"){
                             Base.getArea("商区",town,id);
                         }
                       $("#distan,#mark").removeClass("hidden");
                    }else{
                        $("#distan,#mark").addClass("hidden");
                        $("#range").val("");
                        Base.removeTip("#range");
                         $("#latitude").val("");
                         $("#longitude").val("");
                    }
                
            });
        });
    },
    getArea:function(name,obj,id){
        var html="<option value='-1'> - 请选择"+name+" - </option>";
        var  did=$("#data_id").val();
        var s={
            url:Path.web_path+"/Place/GetPublicPlace/getRegionsList",
            params:"parentId="+id,
            sucrender:function(data){
                if(data.data!=""){
                    $.each(data.data,function(i,o){
                        if(did!=o.id){
                         html+="<option value='"+o.id+"'>"+o.name+"</option>";
                        }
                    });
                    $(obj).html(html);
                    $(obj).parents("div.place").find("select").not("select.prov").not("select.city").removeAttr("disabled").css("color","#555");
                }else{
                    if($(obj).hasClass("area")||$(obj).hasClass("town")){
                        $(obj).attr("disabled","disabled");
                        $(obj).css("color", "#aaa");
                        $(obj).next().attr("disabled","disabled");
                        $(obj).next().css("color", "#aaa");
                    }
                }
            },
            failrender:function(){       
            }
        };
        Base.AjaxRequest(s);
    },   
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
            file_size_limit : "500",	// 500KB
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
            button_text : '<span class="button" style="margin-top:1px;">选择图片（最大不超过500KB）</span>',
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
    },
    /*引入头部图标*/
    linkInfavouiteIcon:function(){
        var dom=document.createElement("link");
        dom.rel="shortcut icon";
        dom.href=Path.web_path+"/Theme/images/admin/favicon.ico";
        document.getElementsByTagName("head")[0].appendChild(dom);
    },
    /*页面列表公共搜索方法提取*/
    doSearchInteractiveAct:function(){
         $("#search_box").focus(function(){
            var cur=$(this);
            if(cur.hasClass("gray")){
                cur.val("").removeClass("gray");
            }
        }).blur(function(){
            var val=$(this).val();
            if(val.replace(new RegExp(" ","g"),"")==""){
                $(this).val("请输入搜索关键字词").addClass("gray");
            }else{
               $(this).val(val);  
            }
        }); 
        $("#search_box").keyup(function(e){
            if(e.keyCode==13){
                $("#dosearch").trigger("click");
            }
        });
    },
    /*统一验证密码格式*/
    ValidatePassword:function(id,cont){
        $(id).focus(function(){
            Base.tip(this,"请输入"+cont+"", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg=""+cont+"不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VPwd(str);
                if(bl1==false){
                    msg="密码长度应在6到20位之间";
                    b=false;
                    Base.resetButton();
                }else{
                    b=true;
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    },
    /*统一验证手机号码格式*/
    ValidateCellphone:function(id,cont){
        $(id).focus(function(){
            Base.tip(this,"请输入"+cont+"", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg=""+cont+"不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VPhone(str);
                if(bl1==false){
                    msg="手机号码格式错误";
                    b=false;
                    Base.resetButton();
                }else{
                    b=true;
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    },
    /*价格|距离等正整数验证*/
    ValidateIntNumber:function(id,cont){
        $(id).focus(function(){
            Base.tip(this,"请输入"+cont+"", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg=""+cont+"不能为空";
                b=false;
                Base.resetButton();
            }else{
                var z1 = /^[123456789]{1}\d*$/.test(str);
                var z2 = /^0\.{1}\d+$/.test(str);
                var z3 = /^[123456789]{1}\d*\.{1}\d+$/.test(str);
                if(z1==true||z2==true||z3==true){
                    b=true;
                }else{
                    msg="格式错误，请输入大于0的整数或小数";
                    b=false;
                    Base.resetButton();
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    },
    /*机构电话*/
    inputOrgNumber:function(id,cont){
        $(id).focus(function(){
            Base.tip(this,"请输入"+cont+",手机号或者座机号", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg=""+cont+"不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VPhone(str);
                var bl2=/^\d{6,12}$/.test(str);
//                var bl2=/^(((\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/.test(str);
                if(bl1==true||bl2==true){
                    b=true;
                }else{
                    b=false;
                    msg="格式错误";
                    Base.resetButton();
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    },
    /*正整数验证*/
    validateInter:function(id,cont){
         $(id).focus(function(){
            Base.tip(this,"请输入"+cont, "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg=""+cont+"不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VInt(str);
                if(bl1==true){
                    b=true;
                }else{
                    b=false;
                    msg="输入只能是正整数";
                    Base.resetButton();
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    }
    
};
/*消息提示End*/
var HValidate={
    //验证密码
    VPwd:function(str){
        return !!/^[A-Za-z0-9/!/@/#/$/%/^/&/*/_]{6,20}$/.test(str);
    },
    //验证是否为正整数
    VInt:function(str){
        return !!/^[1-9]{1}\d*?$/.test(str);
    },
    //手机号
    VPhone:function(str){
        return !!(/^(1(([358][0-9])|(47)|[8][01236789]))\d{8}$/.test(str));
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
    ba.linkInfavouiteIcon();
    ba.iniPlace();
});
