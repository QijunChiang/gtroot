/*======================================公共==============================*/
var Public={
    refreshList:function(id,that){
        var curpage = parseInt($("#pagination span.current").not("#pagination span.prev,#pagination span.next").text())-1;
        if($("#"+id+" tr").length == 1){
            if(curpage>0){
                that.genList(curpage-1);
            }else{
                that.genList(0);
            }
        }else{
            that.genList(curpage);
        }
    }
};
/*======================================登录==============================*/
var loginpage={
    iniInputCheck:function(){
        Base.inputValidate("#uname", "登录帐号");
        Base.ValidatePassword("#upwd", "登录密码");
    },
    CheckAll:function(){
        $("#uname,#upwd").trigger("blur");
        var bl=true;
        var len=$("div.mlogin").find("div.tip.error").length;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    keyLogin:function(){
        $(document).keyup(function(event){
            var aid=document.activeElement.id;
            if(event.keyCode==13&&(aid=="uname"||aid=="upwd")){
                $("#uname,#upwd").blur();
                $("#login").trigger("click");
            }
        });
    },
    inipage:function(){
        this.keyLogin();
        this.iniInputCheck();
    }
};
/*======================================分类管理==============================*/
/*父分类列表获取*/
var categoryspage={
    genCategorysList:function(i){
        var cate=categoryspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#categors");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","4");
        dom.append(tmp);
        var s={
            url:Path.web_path +"/Category/CategoryList/getCategoryList",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data=data.data.CategoryList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", cate.genCategorysList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var is_show=o.isDelete;
                    var bar="";
                    if(is_show == 1){
                        bar='<a href="javascript:;" status="0" rel="'+o.id+'" class="mhide" >取消隐藏</a>';
                    }
                    if(o.isUse==false){
                        if(is_show==0){
                            bar='<a href="javascript:;" status="1" rel="'+o.id+'" class="mhide" >隐藏分类</a>';
                        }else{
                            bar='<a href="javascript:;" status="0" rel="'+o.id+'" class="mhide" >取消隐藏</a>';
                        } 
                    }
                    o.show_bar=bar;
                });
                var varr=["ops","id","name","parentId","icon","status","txt","order","show_bar"];
                var tmp='<tr class="{ops}">'
                +'<td><a href="'+Path.web_path+'/sub_categorys?id={id}&&name={name}">{name}</a></td>'
                +'<td>'
                +   '<div class="icon_cate">'
                +        '<img src="'+Path.image_path+'/{icon}" alt="" />'
                +    '</div>'
                +'</td>'
                +'<td>'
                +   '<input type="text" class="order_set" rel="{id}" org="{order}" value="{order}"/>'
                +'</td>'
                +'<td>'
                +    '<a href="'+Path.web_path+'/category_edit?categoryId={id}" class="medit">编辑</a>'
                +    '<a href="javascript:;" class="mdelete mr20"  rel="{id}">删除</a>'
                +   '<a href="'+Path.web_path+'/sub_categorys?id={id}&&name={name}" class="mcheck">查看子分类</a>'
                +   '{show_bar}'
                +'</td>'
                +'</tr>';
                Base.GenTemp("categors", varr, data, tmp);
                cate.deleteCateItem();
                cate.resetOrderNumber();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#categors");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","4");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    resetOrderNumber:function(){
        $("#categors input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var orgId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/Category/CategoryEdit/doSaveCategory",
                    params:"categoryId="+orgId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        if(v1=="-1"){
                            v1="";
                        }
                        cur.attr("org",v1);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    deleteCateItem:function(){
        $("#categors tr td a.mdelete").live("click",function(){
            if(confirm("删除该分类将会导致该分类下的所有子分类被删除,确认删除？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Category/CategoryEdit/doDeleteCategory",
                    params:"categoryId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    hideTargetparentCategorys:function(){
        $("#categors tr td a.mhide").live("click",function(){
            var msg="";
            var cur=$(this);
            var bl=true;
            if(cur.attr("status")=="1"){
                msg="隐藏该分类将同时使该分类下所有子分类不可见。确认隐藏？";
                bl=confirm(msg);
            }
            if(bl==true){
                var cid=cur.attr("rel");
                var status=cur.attr("status");
                var s={
                    url:Path.web_path+"/Category/CategoryList/changeCategoryStatus",
                    params:"categoryIds="+cid+"&isDelete="+status,
                    sucrender:function(){
                        if(status==0){
                            cur.text("隐藏分类").attr("status","1");
                        }else{
                            cur.text("取消隐藏").attr("status","0");
                        }
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.genCategorysList();
        this.hideTargetparentCategorys();
    }
};
/*热门分类列表获取*/
var hcategoryspage={
    genList:function(i){
        var cate=hcategoryspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#hot_clist");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","4");
        dom.append(tmp);
        var s={
            url:Path.web_path +"/Category/CategoryList/do_hot_cates_list",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data=data.data.List;
                if(i==1){
                    Base.iniPagination(len, "#pagination", cate.genList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                });
                var varr=["ops","id","name","icon","order"];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>'
                +   '<div class="icon_cate">'
                +        '<img src="'+Path.image_path+'/{icon}" alt="" />'
                +    '</div>'
                +'</td>'
                +'<td>'
                +   '<input type="text" class="order_set" rel="{id}" org="{order}" value="{order}"/>'
                +'</td>'
                +'<td>'
                +   '<a href="javascript:;" class="mhot" cid="{id}">取消热门</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("hot_clist", varr, data, tmp);
                cate.resetOrderNumber();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#hot_clist");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","4");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    resetOrderNumber:function(){
        $("#hot_clist input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var orgId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/Category/CategoryList/do_update_hot_cates",
                    params:"categoryId="+orgId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        if(v1=="-1"){
                            v1="";
                        }
                        cur.attr("org",v1);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    cancleSetHot:function(){
         $("#hot_clist tr td a.mhot").live("click",function(){
            if(confirm("确定取消热门设置？")){
                var cur=$(this);
                var cid=cur.attr("cid");
                var s={
                    url:Path.web_path+"/Category/CategoryList/do_delete_hot_cates",
                    params:"categoryId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                        Public.refreshList("hot_clist",hcategoryspage);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.genList();
        this.cancleSetHot();
    }  
};
/*父分类详细*/
var category_editpage={
    iniInputCheck:function(){
        Base.inputValidate("#cate_name", "分类名称");
    },
    CheckAll:function(){
        $("#cate_name").trigger("blur");
        var bl=true;
        var len=$("div.category_edit").find("div.tip.error").length;
        var ion_val=$("#icon_img").val();
        var sid=$("input[name='fm-categoryId']").val();
        if(len==0){
            if(ion_val=="" && sid==""){
                Base.resetButton();
                alert("请上传分类图标");
                bl=false;
            }else{
                bl=true;
            }
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        Base.iniUpload("progressID");
        this.iniInputCheck();
    }
};
/*子分类列表*/
var  sub_categoryspage={
    getSubcategorysList:function(i){
        var scate=sub_categoryspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#sub_categors");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var pid=$("#par_id").val();
        var pname=$("#par_name").val();
        var s={
            url:Path.web_path +"/Category/CategoryList/getCategoryList",
            params:"page="+i+"&count="+pagesize+"&parentId="+pid,
            sucrender:function(data){
                var len=data.data.AllCount;
                data=data.data.CategoryList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", scate.getSubcategorysList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var is_show=o.isDelete;
                    var is_hot=o.isHot;
                    var bar="";
                    var  hot_bar="";
                    if(is_show == 1){
                        bar='<a href="javascript:;" status="0" rel="'+o.id+'" class="mhide ml20" >取消隐藏</a>';
                    }
                    if(o.isUse==false){
                        if(is_show==0){
                            bar='<a href="javascript:;" status="1" rel="'+o.id+'" class="mhide ml20" >隐藏分类</a>';
                        }else{
                            bar='<a href="javascript:;" status="0" rel="'+o.id+'" class="mhide ml20" >取消隐藏</a>';
                        } 
                    }
                    if(is_hot==false){
                         hot_bar='<a href="javascript:;" status="1" rel="'+o.id+'" class="mhot_gray ml20" >设为热门</a>';
                     }else{
                         hot_bar='<a href="javascript:;" status="0" rel="'+o.id+'" class="mhot ml20" >取消热门</a>';
                     } 
                    o.show_bar=bar;
                    o.hot_status=hot_bar;
                });
                var varr=["ops","id","name","parentId",'icon','status','txt',"order","show_bar","hot_status"];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>'
                +   '<div class="icon_cate">'
                +        '<img src="'+Path.image_path+'/{icon}" alt="" />'
                +    '</div>'
                +'</td>'
                +'<td>'
                +   '<input type="text" class="order_set" rel="{id}" org="{order}" value="{order}"/>'
                +'</td>'
                +'<td>'+pname+'</td>'
                +'<td>'
                +    '<a href="'+Path.web_path+'/subcate_edit?categoryId={id}&&parent_name='+pname+'&&parent_id={parentId}" class="medit">编辑</a>'
                +    '<a href="javascript:;" class="mdelete"  rel="{id}">删除</a>'
                +   '{show_bar}'
                +   '{hot_status}'
                +'</td>'
                +'</tr>';
                Base.GenTemp("sub_categors", varr, data, tmp);
                scate.deleteSubCateItem();
                scate.resetOrderNumber();
                scate.setHotCategorySet();
                scate.cancleHotCate();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#sub_categors");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleteSubCateItem:function(){
        $("#sub_categors tr td a.mdelete").live("click",function(){
            if(confirm("确定删除该分类？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Category/CategoryEdit/doDeleteCategory",
                    params:"categoryId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    hideTargetCategorys:function(){
        $("#sub_categors tr td a.mhide").live("click",function(){
            var msg="";
            var cur=$(this);
            var bl=true;
            if(cur.attr("status")=="1"){
                msg="确认隐藏？";
                bl=confirm(msg);
            }
            if(bl==true){
                var cid=cur.attr("rel");
                var status=cur.attr("status");
                var s={
                    url:Path.web_path+"/Category/CategoryList/changeCategoryStatus",
                    params:"categoryIds="+cid+"&isDelete="+status,
                    sucrender:function(){
                        if(status==0){
                            cur.text("隐藏分类").attr("status","1");
                        }else{
                            cur.text("取消隐藏").attr("status","0");
                        }
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    setHotCategorySet:function(){
         $("#sub_categors tr td a.mhot_gray").unbind("click").bind("click",function(){
            var cur=$(this);
            var cid=cur.attr("rel");
            var purl=Path.web_path+"/Category/CategoryList/do_add_hot_cates";
            var s={
                url:purl,
                params:"categoryId="+cid,
                sucrender:function(){
                   cur.text("取消热门").removeClass("mhot_gray").addClass("mhot");
                   sub_categoryspage.cancleHotCate();
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        alert(data.data);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    cancleHotCate:function(){
         $("#sub_categors tr td a.mhot").unbind("click").bind("click",function(){
            var cur=$(this);
            var cid=cur.attr("rel");
            var purl=Path.web_path+"/Category/CategoryList/do_delete_hot_cates";
            var s={
                url:purl,
                params:"categoryId="+cid,
                sucrender:function(){
                    cur.text("设为热门").removeClass("mhot").addClass("mhot_gray");
                    sub_categoryspage.setHotCategorySet();
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        alert(data.data);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    resetOrderNumber:function(){
        $("#sub_categors input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var orgId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/Category/CategoryEdit/doSaveSubCategory",
                    params:"categoryId="+orgId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        if(v1=="-1"){
                            v1="";
                        }
                        cur.attr("org",v1);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.getSubcategorysList();
        this.hideTargetCategorys();
        this.hotCategorySet();
    }  
};
/*子分类详细*/
var subcate_editpage={
    iniInputCheck:function(){
        Base.inputValidate("#cate_name", "分类名称");
    },
    CheckAll:function(){
        $("#cate_name").trigger("blur");
        var bl=true;
        var len=$("div.category_edit").find("div.tip.error").length;
        var ion_val=$("#icon_img").val();
        var sid=$("input[name='fm-categoryId']").val();
        if(len==0){
            if(ion_val=="" && sid==""){
                Base.resetButton();
                alert("请上传分类图标");
                bl=false;
            }else{
                bl=true;
            }
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        Base.iniUpload("progressID");
        this.iniInputCheck();
    }  
};
/*======================================机构管理==============================*/
var agencyspage={
    genList:function(i){
        var age=agencyspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#agencys_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var param_a=studentspage.getAreaParam();
        var s={
            url:Path.web_path +"/Agency/AgencyList/getAgencyList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&cityId="+param_a,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.OrgList;
                $("#all_count").text(len);
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.genList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.lat=o.location.x;
                    o.lng=o.location.y;
                    o.address=o.location.info;
                    var cates=o.categoryList;
                    var ctmp="";
                    $.each(cates,function(item,oitem){
                        var tmp="";
                        tmp='<span class="mr10">'+oitem.name+'</span>';
                        ctmp+=tmp;
                    });
                    o.cates=ctmp;
                    var is_show=o.isShow;
                    var bar="";
                    if(is_show == 1){
                        bar='<a href="javascript:;" status="0" rel="'+o.orgId+'" class="mhide ml20" >隐藏机构</a>';
                    }else{
                        bar='<a href="javascript:;" status="1" rel="'+o.orgId+'" class="mhide ml20" >取消隐藏</a>';
                    }
                    o.show_bar=bar;
                });
                var varr=["ops",'orgId','name','phone','lat','lng','address','cates','order','show_bar'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{cates}</td>'
                +'<td>{phone}</td>'
                +'<td>'
                +    '<p>纬 {lat} 经 {lng}</p>'
                +    '<p>{address}</p>'
                +'</td>'
                +'<td>'
                +   '<input type="text" class="order_set" rel="{orgId}" org="{order}" value="{order}"/>'
                +'</td>'
                +'<td>'
                +       '<a href="'+Path.web_path+'/agency_detail?orgId={orgId}" class="mcheck"">查看</a>'
                +       '<a href="'+Path.web_path+'/agency_edit?orgId={orgId}&&name='+name+'" class="medit">编辑</a>'
                +       '<a href="javascript:;" class="mdelete" rel="{orgId}">删除</a>'
                +       '{show_bar}'
                +'</td>'
                +'</tr>';
                Base.GenTemp("agencys_list", varr, data, tmp);
                age.deleteAgencyItem();
                age.resetOrderNumber();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    $("#all_count").text(0);
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#agencys_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    hideTargetAgency:function(){
        $("#agencys_list tr td a.mhide").live("click",function(){
            var msg="";
            var cur=$(this);
            var bl=true;
            if(cur.attr("status")=="0"){
                msg="确认隐藏？";
                bl=confirm(msg);
            }
            if(bl==true){
                var cid=cur.attr("rel");
                var status=cur.attr("status");
                var s={
                    url:Path.web_path+"/Agency/AgencyList/changeAgencyStatus",
                    params:"orgId="+cid+"&isShow="+status,
                    sucrender:function(){
                        if(status==0){
                            cur.text("取消隐藏").attr("status","1");
                        }else{
                            cur.text("隐藏机构").attr("status","0");
                        }
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    deleteAgencyItem:function(){
        $("#agencys_list a.mdelete").click(function(){
            if(confirm("确定删除该机构？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Agency/AgencyList/dodeleteAgency",
                    params:"orgId="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    resetOrderNumber:function(){
        $("#agencys_list input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var orgId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/Agency/AgencyEdit/doSaveAgency",
                    params:"orgId="+orgId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        Public.refreshList("agencys_list", agencyspage);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            agencyspage.genList(0);
        });
    },
    showImportUi:function(){
        $("#import").unbind("click").bind("click",function(){
            $("#bulkimport").fadeIn(); 
        });
        $("#bulkimport .close a").unbind("click").bind("click",function(){
            $("#bulkimport").fadeOut(200); 
            $("#oExl").val("");
            $("#divFileProgressContainer").html("");
        });
    },
    doimportAct:function(){
        $("#impro").unbind("click").bind("click",function(){
            var vl=$("#oExl").val();
            if(vl==""){
                alert("请先选择要导入的文件");
            }else{
                var s={
                    url:Path.web_path + '/Agency/AgencyList/doImprotAgency',
                    params:"orgExcel="+vl,
                    sucrender:function(data){
                        var dt=data.data;
                        alert("成功导入"+dt.successNum+"条数据，失败"+dt.failureNum+"条数据,失败行数：第"+dt.failureLine+"行");
                        $("#bulkimport .close a").trigger("click");
                        window.location.reload();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    iniFileUpload:function(){
        var swfu;
        var param=$("#post_params").val();
        swfu= new SWFUpload({
            // Backend Settings
            upload_url: ""+Path.web_path+"/upload",
            post_params: {
                "PHPSESSID" : param,
                "extensionName":"xls"
            },
            file_size_limit : "5120",	// 5M
            file_types : "*.xls",
            file_types_description : "Excel工作表",
            file_upload_limit : "0",

            // Event Handler Settings - these functions as defined in Handlers.js
            //  The handlers are not part of SWFUpload but are part of my website and control how
            //  my website reacts to the SWFUpload events.
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadExcelFileSuccess,
            upload_complete_handler : uploadComplete,

            // Button Settings
            button_image_url : ""+Path.web_path+"/Theme/lib/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
            button_placeholder_id :"spanButtonPlaceholder",
            button_width: 400,
            button_height: 18,
            button_text : '<span class="button" style="margin-top:1px;">选择文件（只支持.xls格式）</span>',
            button_text_top_padding: 0,
            button_text_left_padding: 18,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            // Flash Settings
            flash_url : ""+Path.web_path+"/Theme/lib/swfupload/swfupload.swf",

            custom_settings : {
                upload_target : "divFileProgressContainer",
                progress_id:"progress_id"
            },
            debug: false
        });    
    },
    inipage:function(){
        this.genList();
        this.doSearchAct();
        this.iniFileUpload();
        this.showImportUi();
        this.doimportAct();
        this.hideTargetAgency();
    }
};
/*机构编辑添加*/
var agency_editpage={
    initialize:function(){
        var point=$("#mapdata").val().split(",");
        point[0]=parseFloat(point[0]);
        point[1]=parseFloat(point[1]);
        var point1={
            lat:point[1],
            lng:point[0]
        };
        agency_editpage.addOverLayToMap("map",point1,15);
    },
    addOverLayToMap:function(id,point1,size){
        if(!!!size){
            size=12;
        }
        var map = new BMap.Map(id); 
        var myGeo = new BMap.Geocoder();
        map.enableScrollWheelZoom(); 
        map.enableContinuousZoom(); 
        map.addControl(new BMap.NavigationControl({
            anchor: BMAP_ANCHOR_TOP_LEFT, 
            type: BMAP_NAVIGATION_CONTROL_SMALL
        }));  //右上角，仅包含平移和缩放按钮
        var bl=!!point1&&!!point1.lat&&!!point1.lng;
        var point = '';
        if(!bl){
            var point3=$("#location").val().split(",");
            point3[0]=parseFloat(point3[0]);
            point3[1]=parseFloat(point3[1]);
            var point4={
                lat:point3[1],
                lng:point3[0]
            };
            point= new BMap.Point(point4.lng,point4.lat);
            $("#longitude").val(point4.lng);
            $("#latitude").val(point4.lat);  
        }else{
            point = new BMap.Point(point1.lng,point1.lat);
            $("#longitude").val(point1.lng);
            $("#latitude").val(point1.lat);  
        }
        map.centerAndZoom(point, size);
        map.clearOverlays();
        var marker = new BMap.Marker(point);        // 创建标注  
        marker.enableDragging();
        map.addOverlay(marker);
        marker.addEventListener("dragend", function(e){    
            $("#longitude").val(e.point.lng);
            $("#latitude").val(e.point.lat);  
            var pt=new BMap.Point(e.point.lng,e.point.lat);
            myGeo.getLocation(pt, function(rs){
                var addComp = rs.addressComponents;
                var string=addComp.province+ addComp.city + addComp.district + addComp.street + addComp.streetNumber;
                $("#address").val(string);
            });
        });
    },
    loadMapScript:function () {
        Base.loadMapScript("agency_editpage.initialize");
    },
    iniInputCheck:function(){
        Base.inputValidate("#agency_name", "机构名称");
        Base.inputValidate("#short_name", "机构简称");
        Base.inputOrgNumber("#agency_phone", "机构电话");
        Base.ValidateIntNumber("#agency_money", "课程价格");
        Base.inputValidate("#address", "地址信息");
    },
    checkAll:function(){
        var age=agency_editpage;
        var sep=student_editpage;
        age.getAllcategoryIds();
        sep.getSelfImages();
        $("#agency_name,#short_name,#agency_phone,#agency_money,#address").trigger("blur");
        var bl=true;
        var len=$("div.agency_edit").find("div.tip.error").length;
        var ba=Base;
        //        var parentId="-1";
        //        var pcode=$("#prov").val();
        //        var ccode=$("#city").val();
        //        var qcode=$("#area").val();
        //        var tcode=$("#town").val();
        //        if(pcode=="0"){
        //            parentId="0";
        //        }else if(ccode=="-1"){
        //            parentId=pcode;
        //        }else if(qcode=="-1"){
        //            parentId=ccode;
        //        }else if(tcode=="-1"){
        //            parentId=qcode;
        //        }else{
        //            parentId=tcode;
        //        }
        //        var data_id=$("#is_editpage").val();
        //        if(data_id!=""){
        //            if(parentId!="0"){
        //                $("#cityIds").val(parentId);
        //            }
        //        }else{
        //            $("#cityIds").val(parentId);
        //        }
        if(len==0){
            var ids=$("#ids").val();
            if(ids==""){
                bl=false;
                ba.resetButton();
                alert("请选择机构专长");
            }else{
                bl=true;
                age.getAllcategoryIds();
            }
        }else{
            bl=false;
            var ht=$("div.agency_edit").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;
    },
    iniChosecates:function(obj){
        var pg=agency_editpage;
        $(obj).unbind("click").bind("click",function(){
            var cur=$(this);
            cur.text("取消选择").addClass("cancle").removeClass("chose");
            cur.next().show();
            pg.inCancleact(cur);
        });
    },
    inCancleact:function(obj){
        var pg=agency_editpage;
        $(obj).unbind("click").bind("click",function(){
            var cur=$(this);
            cur.text("选择").addClass("chose").removeClass("chose");
            cur.next().find(".cur").removeClass("cur");
            cur.next().next().html("");
            cur.next().hide();
            pg.iniChosecates(cur);
        });
    },
    /*异步获取子分类*/
    getSubcates:function(curl,is_muilt){
        $("div.agency_cates a").click(function(){
            var cur=$(this);
            cur.parent().find(".cur").removeClass("cur");
            cur.addClass("cur");
            var parent_id=cur.attr("rel");
            var s={
                url:Path.web_path+curl,
                params:"parentId="+parent_id,
                sucrender:function(data){
                    var dt=data.data;
                    var len=dt.length;
                    var html="";
                    $.each(dt,function(i,o){
                        var tmp="";
                        if(len==1){
                            tmp='<a href="javascript:;" rel="'+o.id+'">'+o.name+'</a>';
                            html=tmp;
                        }else{
                            tmp='<a href="javascript:;" rel="'+o.id+'">'+o.name+'</a>';
                            html+=tmp;
                        }
                    });
                    $("#scates").html(html);
                    agency_editpage.iniChoseSubcates(is_muilt);
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        var temp='<span class="red">该分类下还没有数据</span>';
                        $("#scates").html(temp);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    /*选择分类初始化*/
    iniChoseSubcates:function(bl){
        var age=agency_editpage;
        $("#scates a").click(function(){
            var cur=$(this);
            if(bl==true){
                if(cur.hasClass("cur")){
                    cur.removeClass("cur");
                }else{
                    cur.addClass("cur");
                }
            }else{
                cur.parent().find(".cur").removeClass("cur");
                cur.addClass("cur");
            }
            age.genChoseneditem(cur,bl);
        });
    },
    /*生成已选择的分类*/
    genChoseneditem:function(obj,is_bl){
        var temp="";
        var cid="";
        var age=agency_editpage;
        var  bl=true;
        var  ele=$("#good_at span");
        if($(obj).hasClass("cur")){
            var cname=$(obj).text();
            cid=$(obj).attr("rel");
            $.each(ele,function(i,o){
                var that=$(o).attr("data_id");
                if(cid==that){
                    alert("该专长已存在");
                    bl=false;
                    $(obj).removeClass("cur");
                }
            });
            if(bl==true){
                temp='<span data_id="'+cid+'"><em class="cate">'+cname+'</em><a class="remove" href="javascript:;" title="删除">X</a></span>';
                if(is_bl==false){
                    $("#good_at").html(temp);
                }else{
                    $("#good_at").append(temp);
                }
                $("#good_at").parent().show();
                age.removeItem();
            }
        }else{
            cid=$(obj).attr("rel");
            var par=$("#good_at span");
            $.each(par,function(i,o){
                var that=$(o).attr("data_id");
                if(cid==that){
                    $(o).remove();
                    age.afterRemoveItem();
                }
            });
        }
        age.getAllcategoryIds();
    },
    /*移除所选*/
    removeItem:function(){
        var age=agency_editpage;
        var  par=$("#scates a");
        $("#good_at span a.remove").click(function(){
            var cid=$(this).parent().attr("data_id");
            $(this).parent().remove(); 
            $.each(par,function(i,o){
                var that=$(o).attr("rel");
                if(cid==that){
                    $(o).removeClass("cur");
                }
            });
            age.afterRemoveItem();  
            age.getAllcategoryIds();
        });
    },
    afterRemoveItem:function(){
        var len=$("#good_at span").length;
        if(len==0){
            $("div.operation").hide();   
        }
    },
    /*存储ID*/
    getAllcategoryIds:function(){
        var par=$("#good_at span");
        var cm="";
        $.each(par,function(i,o){
            if(i==0){
                cm=$(o).attr("data_id");
            }else{
                cm+=","+$(o).attr("data_id");
            }
        });
        $("#ids").val(cm);
    },
    inipage:function(){
        this.iniInputCheck();
        this.loadMapScript();
        this.iniChosecates();
        this.iniChosecates("a.chose");
        this.getSubcates("/Agency/AgencyEdit/getSubcatesList",true);
        this.removeItem();
        var sep=student_editpage;
        sep.iniFun();
        sep.iniVideoUpload();
    //        var regp=region_editpage;
    //        regp.iniPlaceEdit();
    }
};
/*机构详细*/
var agency_detailpage={
    iniRatActing:function(){
        var score=$("#score").val();
        $("#star").raty({ 
            half: true ,
            starHalf: Path.web_path+'/Theme/images/admin/half_star@2x.png',
            starOff:  Path.web_path+'/Theme/images/admin/hollow_star.png',
            starOn:  Path.web_path+'/Theme/images/admin/solid_star.png',
            scoreName: "star",
            readOnly: true,
            score:score
        });
    },
    inipage:function(){
        this.iniRatActing();
    }
};
/*======================================课程视频管理==============================*/
var videospage={
    getCourseVideos:function(i){
        var age=videospage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#videos_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Video/VideoList/getVideoList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.videoList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getCourseVideos);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    if(o.video!=""){
                        o.has_video=' <a href="javascript:;" class="mplay player" data_video="'+Path.image_path+'/'+o.video+'">播放</a>';
                    }else{
                        o.has_video="";
                    }
                });
                var varr=["ops",'videoId','name','video','userId','categoryName','teachName','has_video'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>'+Path.image_path+'/{video}</td>'
                +'<td>{categoryName}</td>'
                +'<td>{teachName}</td>'
                +'<td>'
                +  '{has_video}'
                //                +    '<a href="'+Path.web_path+'/video_edit?videoId={videOId}" class="mcheck">查看评论</a>'
                +   '<a href="'+Path.web_path+'/video_edit?videoId={videoId}" class="medit" >编辑</a>'
                +    '<a href="javascript:;" class="mdelete" rel="{videoId}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("videos_list", varr, data, tmp);
                age.deleVideoitem();
                age.doPlayVideoAction();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#videos_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    /*删除指定视频*/
    deleVideoitem:function(){
        $("#videos_list tr td a.mdelete").live("click",function(){
            if(confirm("确定要删除该视频吗？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Video/VideoList/doDeleteVideo",
                    params:"videoId="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });  
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct(); 
        $("#dosearch").click(function(){
            videospage.getCourseVideos(0);
        });
    },
    /*播放指定视频*/
    doPlayVideoAction:function(){
        $("#videos_list a.mplay").unbind("click").bind("click",function(){
            $("#bulkimport").append(COMMONTEMP.T0004);
            var furl=$(this).attr("data_video");
            var flashvars={
                f:furl,
                s:'0',
                c:'0',
                i:"",
                e:'2'
            };
            var params={
                bgcolor:'#000',
                allowFullScreen:true,
                allowScriptAccess:'always'
            };
            var attributes={
                id:'ckplayer_a1',
                name:'ckplayer_a1'
            };
            swfobject.embedSWF(''+Path.web_path+'/Theme/lib/ckplayer/ckplayer.swf', 'a1', '600', '400', '10.0.0',''+Path.web_path+'/Theme/lib/ckplayer/expressInstall.swf', flashvars, params, attributes); 
            //            var temp='var mm={"'+furl+'":"video/mp4"};';
            //            eval(temp);
            //            var support=['iPad','iPhone','ios','android+false','msie10+false'];
            //            CKobject.embedHTML5('video','ckplayer_a1',600,400,mm,flashvars,support);
            $("#bulkimport").show();
        });
    },
    /*移除遮罩*/
    RemoveCoverVideo:function(){
        $("#bulkimport .close a").live("click",function(){
            $("#bulkimport").find("div.video_player").remove(); 
            $("#bulkimport").hide(); 
        });  
    },
    inipage:function(){
        this.getCourseVideos();
        this.doSearchAct();
        this.RemoveCoverVideo();
    }
};
/*视频详细*/
var video_editpage={
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#video_name","视频名称");
        $("#vtime").focus(function(){
            Base.tip(this,"请输入视频时长，格式如02：22", "right");
        }).blur(function(){
            var str=$(this).val();
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str.replace(new RegExp(" ","g"),"")==""){
                msg="视频时长不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bb= /^[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/.test(str);
                if(bb==true){
                    b=true;
                }else{
                    msg="格式错误，请按要求填写";
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
        $("#cid").focus(function(){
            var cur=$(this);
            if(cur.hasClass("gray")){
                cur.val("").removeClass("gray");
            }
        }).blur(function(){
            var val=$(this).val().replace(new RegExp(" ","g"),"");
            if(val==""){
                $(this).val("请输入搜索关键词").addClass("gray");
            }
        }); 
    },
    /*初始封面视频上传*/
    iniSingleImageUpload:function(){
        var sed=student_editpage;
        sed.iniMultiUpload("spanButtonPlaceholder6","divFileProgressContainer6","progress_id6");  
    },
    checkAll:function(){
        agency_editpage.getAllcategoryIds();
        $("#video_name,#vtime").trigger("blur");
        var bl=true;
        var len=$("div.video_edit").find("div.tip.error").length;
        var rid=$("#rname").val();
        var vc=$("#v_cover").val();
        var vdo=$("#video_path").val();
        var ba=Base;
        if(len==0){
            var ids=$("#ids").val();
            if(ids==""){
                bl=false;
                ba.resetButton();
                alert("请选择视频分类");
            }else if(rid==""){
                bl=false;
                ba.resetButton();
                alert("请选择关联者"); 
            }else if(vc==""){
                bl=false;
                ba.resetButton();
                alert("请上传视频封面"); 
            }else if(vdo==""){
                bl=false;
                ba.resetButton();
                alert("请上传视频文件"); 
            }else{
                bl=true;
            }
        }else{
            bl=false;
            var ht=$("div.video_edit").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;  
    },
    /*显示关联者列表*/
    showChoseUsersOps:function(){
        $("#chose_conter").unbind("click").bind("click",function(){
            var cur=$(this);
            if(cur.hasClass("fold")){
                $("#userselect").show();
                cur.removeClass("fold").addClass("unfold");
                cur.text("取消");
            }else{
                $("#userselect").hide(); 
                cur.removeClass("unfold").addClass("fold");
                cur.text("选择");
            }
        });
    },
    /*搜索关联者*/
    doSearchUsers:function(tp){
        $("#searchUser").unbind("click").bind("click",function(){
            var type=$("#stype").val();
            var name=$("#cid").val();
            if($("#cid").hasClass("gray")){
                name="";
            }
            var dom=$("#userlist");
            var s={
                url:Path.web_path +"/Video/VideoEdit/getUserList",
                params:"type="+type+"&name="+name,
                sucrender:function(data){
                    var dt= data.data;
                    $.each(dt,function(i,o){
                        o.address=o.location.info;
                        o.loc=o.location.y+","+o.location.x;
                    });
                    var varr=['userId','name','address','loc'];
                    var tmp='<li class="choose">'
                    +   '<a href="javascript:;" rel="{userId}" address="{address}" loc="{loc}" title="{name}">{name}</a>'
                    +'</li>';
                    Base.GenTemp("userlist", varr, dt, tmp);
                    video_editpage.setValToRelative(tp);
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        var tmp='<li class="nodata">没有找到数据!</li>';
                        dom.find("li").remove();
                        dom.append(tmp);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    /*关联者赋值*/
    setValToRelative:function(type){
        $("#userlist li.choose").unbind("click").bind("click",function(){
            var cur=$(this);
            cur.parent().find(".cur").removeClass("cur");
            cur.addClass("cur");
            var rname=cur.find("a").text();
            var rid=cur.find("a").attr("rel");
            $("#rid").val(rid);
            $("#rname").val(rname);
            if(type=="course"){
                course_editpage.doMarkeSelectedPosition(cur);
            }
        });
    },
    inipage:function(){
        var ag=agency_editpage;
        ag.iniChosecates();
        ag.iniChosecates("a.chose");
        ag.getSubcates("/Video/VideoEdit/getSubcatesList",false);
        ag.removeItem();
        this.iniInputCheck();
        this.iniSingleImageUpload();
        this.showChoseUsersOps();
        this.doSearchUsers("video");
        student_editpage.iniVideoUpload();
    }
};
/*======================================用户管理---获取学生列表==============================*/
var studentspage={
    getAreaParam:function(){
        var param="";
        var pcode=$("#prov").val();
        var ccode=$("#city").val();
        var qcode=$("#area").val();
        var tcode=$("#town").val();
        if(pcode=="0"){
            param="";
        }else if(ccode=="-1"){
            param=pcode;
        }else if(qcode=="-1"){
            param=ccode;
        }else if(tcode=="-1"){
            param=qcode;
        }else{
            param=tcode;
        }
      return param;
    },
    getStudentsList:function(i){
        var age=studentspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#students_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var param_a=studentspage.getAreaParam();
        var s={
            url:Path.web_path +"/User/UserList/getStudentLists",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&cityId="+param_a,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.StuList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getStudentsList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var gender=o.sex;
                    if(gender==0){
                        o.sex="女";
                    }else{
                        o.sex="男";
                    }
                    if(o.age<=0){
                        o.age="0";
                    }else{
                        o.age=o.age;
                    }
                });
                var varr=["ops",'userId','name','phone','sex','college','age'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{phone}</td>'
                +'<td>{sex}</td>'
                +'<td>{age} 岁</td>'
                +'<td>{college}</td>'
                +'<td>'
                +       '<a href="'+Path.web_path+'/student_detail?stuId={userId}" class="mcheck"">查看</a>'
                +       '<a href="'+Path.web_path+'/student_edit?stuId={userId}" class="medit">编辑</a>'
                +       '<a href="javascript:;" class="mdelete" rel="{userId}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("students_list", varr, data, tmp);
                age.deleteStudentItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#students_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleteStudentItem:function(){
        $("#students_list a.mdelete").click(function(){
            if(confirm("确定删除该学生？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Agency/AgencyList/dodeleteAgency",
                    params:"orgId="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            studentspage.getStudentsList(0);
        });
    },
    inipage:function(){
        this.doSearchAct();
        this.getStudentsList();
    }
};
/*======================================用户管理---添加学生==============================*/
var add_studentpage={
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#tname","姓名");
        ba.inputValidate("#birth","出生日期");
        ba.ValidateCellphone("#phone","手机号");
        ba.inputValidate("#email","邮箱");
        ba.ValidatePassword("#pwd","密码");
    },
    checkAll:function(){
        $("#tname,#birth,#phone,#email,#pwd").trigger("blur");
        var len=$("#add_stu_page").find("div.tip.error").length;
        var bl=true;
        if(len!=0){
            Base.resetButton();
            bl=false;
        }
        return bl;
    },
    inipage:function(){
        this.iniInputCheck();
    }
};
/*======================================用户管理---学生编辑页==============================*/
var student_editpage={
    iniVideoUpload:function(){
        var swfu;
        var param=$("#post_params").val();
        swfu= new SWFUpload({
            // Backend Settings
            upload_url: ""+Path.web_path+"/upload",
            post_params: {
                "PHPSESSID" : param,
                "extensionName":"mp4"
            },
            file_size_limit : "51200",	// 50M
            file_types : "*.mp4;*.3gp",
            file_types_description : "视频文件",
            file_upload_limit : "0",

            // Event Handler Settings - these functions as defined in Handlers.js
            //  The handlers are not part of SWFUpload but are part of my website and control how
            //  my website reacts to the SWFUpload events.
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadVideoSuccess,
            upload_complete_handler : uploadComplete,

            // Button Settings
            button_image_url : ""+Path.web_path+"/Theme/lib/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
            button_placeholder_id :"spanButtonPlaceholder7",
            button_width: 400,
            button_height: 18,
            button_text : '<span class="button" style="margin-top:1px;">选择视频文件（只支持.mp4、.3gp格式的视频文件，最大不超过50MB）</span>',
            button_text_top_padding: 0,
            button_text_left_padding: 18,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            // Flash Settings
            flash_url : ""+Path.web_path+"/Theme/lib/swfupload/swfupload.swf",

            custom_settings : {
                upload_target : "divFileProgressContainer7",
                progress_id:"progress_id7"
            },

            // Debug Settings
            debug: false
        });  
    },
    iniFun:function(){
        var sed=student_editpage;
        sed.iniMultiUpload("spanButtonPlaceholder","divFileProgressContainer","progress_id");
        sed.iniMultiUpload("spanButtonPlaceholder1","divFileProgressContainer1","progress_id1");
        sed.iniMultiUpload("spanButtonPlaceholder2","divFileProgressContainer2","progress_id2");
        sed.iniMultiUpload("spanButtonPlaceholder3","divFileProgressContainer3","progress_id3");
        sed.iniMultiUpload("spanButtonPlaceholder4","divFileProgressContainer4","progress_id4");
        sed.iniMultiUpload("spanButtonPlaceholder5","divFileProgressContainer5","progress_id5");
        sed.iniMultiUpload("spanButtonPlaceholder6","divFileProgressContainer6","progress_id6");
        /*删除自我介绍图片*/
        $("ul.screen_shot li .progressCancel").click(function(){
            var par=$(this).parents(".sel");
            var obj1=$(par).find(".thumbnails");
            obj1.next().val("delete");
            obj1.next().attr("ref","delete");
            obj1.find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
            $(this).parent().remove();
        });
    },
    /*图片上传初始化*/
    iniMultiUpload:function(upload_id,container_id,progress_id){
        var swfu;
        var param=$("#post_params").val();
        swfu= new SWFUpload({
            // Backend Settings
            upload_url: ""+Path.web_path+"/upload",
            post_params: {
                "PHPSESSID" : param
            },
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
            button_placeholder_id :upload_id,
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
                upload_target : container_id,
                progress_id:progress_id
            },

            // Debug Settings
            debug: false
        });
    },
    /*初始验证*/
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#sname", "姓名");
        ba.inputValidate("#s_age", "出生年月");
        ba.inputValidate("#s_college", "所在学校");
    },
    getSelfImages:function(){
        var  par1=$("div.stu_edit  ul.screen_shot li input.ifile");
        var  par2=$("div.stu_edit  ul.screen_shot li input.img_id");
        var cm="";
        var ck="";
        $.each(par1,function(i,o){
            if(i==0){
                cm=$(o).val();
            }else{
                cm+=","+$(o).val();
            }
        });
        $.each(par2,function(i,o){
            if(i==0){
                ck=$(o).val();
            }else{
                ck+=","+$(o).val();
            }
        });
        $("#imageIds").val(ck);
        $("#images").val(cm);
    },
    checkAll:function(){
        student_editpage.getSelfImages();
        $("#sname,#s_phone,#s_age,#s_college").trigger("blur"); 
        var  len=$("div.stu_edit").find("div.tip.error").length;
        var bl=true;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            var ht=$("div.stu_edit").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniFun();
        this.iniVideoUpload();
        this.iniInputCheck();
    }
};
/*======================================用户管理---学生详细==============================*/
var student_detailpage ={
    inipage:function(){} 
};
/*======================================用户管理---老师列表==============================*/
var teacherspage={
    genList:function(i){
        var age=teacherspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#teachers_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","8");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var param_a=studentspage.getAreaParam();
        var s={
            url:Path.web_path +"/User/UserList/getTeachertLists",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&cityId="+param_a,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.TeacherList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.genList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var gender=o.sex;
                    if(gender==0){
                        o.sex="女";
                    }else{
                        o.sex="男";
                    }
                    var cates=o.categoryList;
                    var ctmp="";
                    $.each(cates,function(item,oitem){
                        var tmp="";
                        tmp='<span class="mr10">'+oitem.name+'</span>';
                        ctmp+=tmp;
                    });
                    o.cates=ctmp;
                    if(o.age<=0){
                        o.age="0";
                    }else{
                        o.age=o.age;
                    }
                });
                var varr=["ops",'userId','name','phone','sex','college','age','cates','order'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{sex}</td>'
                +'<td>{age} 岁</td>'
                +'<td>{cates}</td>'
                +'<td>{phone}</td>'
                +'<td>{college}</td>'
                +'<td>'
                +   '<input type="text" class="order_set" rel="{userId}" org="{order}" value="{order}"/>'
                +'</td>'
                +'<td>'
                +       '<a href="'+Path.web_path+'/teacher_detail?teacherId={userId}" class="mcheck"">查看</a>'
                +       '<a href="'+Path.web_path+'/teacher_edit?teacherId={userId}" class="medit">编辑</a>'
                +       '<a href="javascript:;" class="mdelete" rel="{userId}">删除</a>'
                +       '<a href="'+Path.web_path+'/teacher_file?userId={userId}&name={name}" class="medit">证件管理</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("teachers_list", varr, data, tmp);
                age.deleteStudentItem();
                age.resetOrderNumber();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#teachers_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","8");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    resetOrderNumber:function(){
        $("#teachers_list input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var teacherId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/User/UserEdit/doSaveTeacher",
                    params:"teacherId="+teacherId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        Public.refreshList("teachers_list", teacherspage);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    deleteStudentItem:function(){
        $("#teachers_list a.mdelete").click(function(){
            if(confirm("确定删除该老师？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Agency/AgencyList/dodeleteAgency",
                    params:"orgId="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            teacherspage.genList(0);
        });
    },
    inipage:function(){
        this.doSearchAct();
        this.genList();
    }
};
var teacher_editpage={
    iniRatActing:function(){
        var score=$("#score").val();
        $("#star").raty({ 
            half: true ,
            starHalf: Path.web_path+'/Theme/images/admin/half_star@2x.png',
            starOff:  Path.web_path+'/Theme/images/admin/hollow_star.png',
            starOn:  Path.web_path+'/Theme/images/admin/solid_star.png',
            scoreName: "star",
            readOnly: true,
            score:score
        });
    },  
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#t_name","姓名");
        ba.inputValidate("#t_age","出生日期");
        //        ba.inputValidate("#t_colege","所在学校");
        ba.inputValidate("#skill","专业技能");
    },
    checkAll:function(){
        var age=agency_editpage;
        var sep=student_editpage;
        age.getAllcategoryIds();
        sep.getSelfImages();
        $("#tname,#t_age,#t_price,#skill").trigger("blur");
        var bl=true;
        var len=$("#edit_teacher").find("div.tip.error").length;
        //          var parentId="-1";
        //        var pcode=$("#prov").val();
        //        var ccode=$("#city").val();
        //        var qcode=$("#area").val();
        //        var tcode=$("#town").val();
        //        if(pcode=="0"){
        //            parentId="0";
        //        }else if(ccode=="-1"){
        //            parentId=pcode;
        //        }else if(qcode=="-1"){
        //            parentId=ccode;
        //        }else if(tcode=="-1"){
        //            parentId=qcode;
        //        }else{
        //            parentId=tcode;
        //        }
        //        var data_id=$("#is_editpage").val();
        //        if(data_id!=""){
        //            if(parentId!="0"){
        //                $("#cityIds").val(parentId);
        //            }
        //        }else{
        //            $("#cityIds").val(parentId);
        //        }
        if(len==0){
            var ids=$("#ids").val();
            if(ids==""){
                bl=false;
                Base.resetButton();
                alert("请选择专长");
            }else{
                bl=true;
            }
        }else{
            bl=false;
            var ht=$("#edit_teacher").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        var ag=agency_editpage;
        var sep=student_editpage;
        this.iniRatActing();
        this.iniInputCheck();
        ag.loadMapScript();
        ag.iniChosecates();
        ag.iniChosecates("a.chose");
        ag.getSubcates("/User/UserEdit/getSubcatesList",true);
        ag.removeItem();
        /*图片上传控件初始化*/
        sep.iniFun();
        sep.iniVideoUpload();
    //        var regp=region_editpage;
    //        regp.iniPlaceEdit();
    }
};
/*======================================用户管理---添加老师==============================*/
var add_teacherpage={
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#tname", "名称");
        ba.inputValidate("#birth", "出生日期");
        ba.ValidateIntNumber("#price", "课程价格");
        ba.ValidateCellphone("#phone", "手机号");
        //        ba.inputValidate("#school", "所在学校");
        ba.ValidatePassword("#pwd", "密码");
        ba.inputValidate("#skill", "专业技能");
    },
    checkAll:function(){
        var age=agency_editpage;
        age.getAllcategoryIds();
        $("#tname,#birth,#price,#phone,#pwd,#address,#skill").trigger("blur");
        var bl=true;
        var len=$("#add_teac_page").find("div.tip.error").length;
        //        var parentId="-1";
        //        var pcode=$("#prov").val();
        //        var ccode=$("#city").val();
        //        var qcode=$("#area").val();
        //        var tcode=$("#town").val();
        //        if(pcode=="0"){
        //            parentId="0";
        //        }else if(ccode=="-1"){
        //            parentId=pcode;
        //        }else if(qcode=="-1"){
        //            parentId=ccode;
        //        }else if(tcode=="-1"){
        //            parentId=qcode;
        //        }else{
        //            parentId=tcode;
        //        }
        //        var data_id=$("#is_editpage").val();
        //        if(data_id!=""){
        //            if(parentId!="0"){
        //                $("#cityIds").val(parentId);
        //            }
        //        }else{
        //            $("#cityIds").val(parentId);
        //        }
        if(len==0){
            var ids=$("#ids").val();
            if(ids==""){
                bl=false;
                Base.resetButton();
                alert("请选择专长");
            }else{
                bl=true;
                age.getAllcategoryIds();
            }
        }else{
            bl=false;
            var ht=$("#add_teac_page").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        var ag=agency_editpage;
        this.iniInputCheck();
        ag.loadMapScript();
        ag.iniChosecates();
        ag.iniChosecates("a.chose");
        ag.getSubcates("/User/UserEdit/getSubcatesList",true);
    //        var regp=region_editpage;
    //        regp.iniPlaceEdit();
    }
};
/*======================================用户管理---老师详细==============================*/
var teacher_detailpage={
    iniRatActing:function(){
        var score=$("#score").val();
        $("#star").raty({ 
            half: true ,
            starHalf: Path.web_path+'/Theme/images/admin/half_star@2x.png',
            starOff:  Path.web_path+'/Theme/images/admin/hollow_star.png',
            starOn:  Path.web_path+'/Theme/images/admin/solid_star.png',
            scoreName: "star",
            readOnly: true,
            score:score
        });
    },  
    inipage:function(){
        this.iniRatActing();
    }
};
/*======================================用户管理---审核老师管理==============================*/
var vertify_steacherspage={
    getVertifyTeachers:function(i){
        var age=vertify_steacherspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#vteachers");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var s={
            url:Path.web_path +"/User/VertifyUserList/getVertifyTeacherLists",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.AuthList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getVertifyTeachers);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var gender=o.sex;
                    if(gender==0){
                        o.sex="女";
                    }else{
                        o.sex="男";
                    }
                    var  state=o.status;
                    if(state==0){
                        o.color="blue"; 
                        o.state="未审核";
                        o.show_bar='<a href="'+Path.web_path+'/vertify_tedit?authId='+o.authId+'&&type='+o.type+'">审核</a>';
                    }else if(state==-1){
                        o.color="red"; 
                        o.state="未通过";
                        o.show_bar='<span class="gray">无</span>';
                    }else{
                        o.color="green"; 
                        o.state="已通过";
                        o.show_bar='<span class="gray">无</span>';
                    }
                    if(o.type==1){
                        o.type="身份证";
                    }else if(o.type==2){
                        o.type="毕业证";
                    }else{
                        o.type="专业考级证书";
                    }
                });
                var varr=["ops",'authId','name','color','state','show_bar','type','editTime','sex'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{sex}</td>'
                +'<td>{type}</td>'
                +'<td>{editTime}</td>'
                +'<td class="{color}">{state}</td>'
                +'<td>'
                +   '{show_bar}'
                +'</td>'
                +'</tr>';
                Base.GenTemp("vteachers", varr, data, tmp);
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#vteachers");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    inipage:function(){
        this.getVertifyTeachers();
    }  
};
var vertify_teditpage={
    doValidateUser:function(){
        $("#pass_vertify").click(function(){
            var authId=$(this).attr("rel");
            var userId=$(this).attr("ref");
            var type=$("#type").val();
            var st="1";
            var s={
                url:Path.web_path+"/User/VertifyUserEdit/doVertifyTeacher",
                params:"authId="+authId+"&status="+st+"&userId="+userId+"&type="+type,
                sucrender:function(){
                    $("#state").removeClass("").addClass("green").text("通过");
                    window.location.href=Path.web_path+"/vertify_steachers";
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        alert(data.data);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
        $("#fail_vertify").click(function(){
            var authId=$(this).attr("rel");
            var userId=$(this).attr("ref");
            var type=$("#type").val();
            var st="-1";
            var s={
                url:Path.web_path+"/User/VertifyUserEdit/doVertifyTeacher",
                params:"authId="+authId+"&status="+st+"&userId="+userId+"&type="+type,
                sucrender:function(){
                    $("#state").removeClass().addClass("red").text("不通过");
                    window.location.href=Path.web_path+"/vertify_steachers";
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        alert(data.data);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    inipage:function(){
        this.doValidateUser();
    }  
};
/*======================================留言管理==============================*/
/*留言列表*/
var messagespage={
    getMessagesList:function(i){
        var age=messagespage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#mesaage_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Message/MessageList/getMessageList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.MessagesList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getMessagesList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.name=o.user.name;
                    o.touser=o.toUser.name;
                });
                var varr=["ops",'body','name','sendTime','touser',"id"];
                var tmp='<tr class="{ops}">'
                +'<td>{body}</td>'
                +'<td>{name}</td>'
                +'<td>{sendTime}</td>'
                +'<td>{touser}</td>'
                +'<td>'
                +       '<a href="'+Path.web_path+'/message_edit?dialogId={id}" class="mcheck"">查看回复</a>'
                +       '<a href="javascript:;" class="mdelete" rel="{id}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("mesaage_list", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#mesaage_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#mesaage_list a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Message/MessageList/doDeleteMessage",
                    params:"messageIds="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            messagespage.getMessagesList(0);
        });
    },
    inipage:function(){
        this.doSearchAct();
        this.getMessagesList();
    }  
};
/*留言详细*/
var message_editpage={
    getMreplysList:function(i){
        var age=message_editpage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#replys");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        var s={
            url:Path.web_path +"/Message/MessageEdit/doGetMessage",
            params:"page="+i+"&count="+pagesize+"&dialogId="+$("#dialogId").val(),
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.MessagesList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getMreplysList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.name=o.user.name;
                    o.photo=o.user.photo;
                });
                var varr=["ops",'body','sendTime','name','photo','id'];
                tmp='<tr class="{ops}">'
                +        '<td>'
                +             '<div class="reply_item clearfix">'
                +                 '<div class="reply_l lf">'
                +                     '<img  src="'+Path.image_path+'/{photo}" class="person" alt="" />'
                +                 '</div>'
                +                 '<div class="reply_r lf">'
                +                     '<em class="sprite"></em>'
                +                     '<div class="mcont">'
                +                        ' <p class="clearfix mb10">'
                +                             '<span class="lf">{name}回复：</span>'
                +                             '<a class="rf mdelete" href="javascript:;" rel="{id}">删除</a>'
                +                        ' </p>'
                +                        '<p class="desc mb10">'
                +                             '{body}'
                +                        ' </p>'
                +                    ' </div>'
                +                    ' <p class="gray date">{sendTime}</p>'
                +                ' </div>'
                +            ' </div>'
                +        ' </td>'
                +     '</tr>';
                Base.GenTemp("replys", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#replys");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#replys a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Message/MessageEdit/doDeleteMessage",
                    params:"messageDetailsIds="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.getMreplysList();
    } 
};
/*======================================培训课程管理==============================*/
var coursespage={
    genList:function(i){
        var age=coursespage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#course_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","7");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Course/CourseList/getCourseList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.CourseList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.genList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var x=o.location.x;
                    var y=o.location.y;
                    var info=o.location.info;
                    o.address='<p>'+info+'</p>'+'<p>('+x+','+y+')'+'</p>';
                    var unit=o.unit;
                    if(unit==1){
                        o.unit="/课"
                    }else{
                        o.unit="/时";
                    }
                    var cates=o.teachTime;
                    var tl="";
                    var marker=[];
                    if(cates.length>1){
                        cates=cates.split(",");
                        $.each(cates,function(i,o){
                            var p=coursespage.turnNintoC(o);
                            marker.push(p);
                        });
                        $.each(marker,function(i,o){
                            var bl=marker[i];
                            if(i==0){
                                tl=bl;
                            }else{
                                tl+=","+bl;
                            }
                        });
                    }else if(cates.length==1){
                        tl=coursespage.turnNintoC(cates);
                    }
                    o.tl=tl;
                });
                var varr=["ops",'courseId','name','remark','address','price','unit','teachName','tl'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{tl}</td>'
                +'<td>{address}</td>'
                +'<td>{price}元{unit}</td>'
                +'<td>{teachName}</td>'
                +'<td>{remark}</td>'
                +'<td>'
                +    '<a href="'+Path.web_path+'/course_edit?courseId={courseId}" class="medit"">编辑</a>'
                +    '<a href="javascript:;" class="mdelete" rel="{courseId}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("course_list", varr, data, tmp);
                age.deleSingleItem();
                age.resetOrderNumber();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#course_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","7");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    turnNintoC:function(obj){
        var Day={
            "0":"星期一",
            "1":"星期二",
            "2":"星期三",
            "3":"星期四",
            "4":"星期五",
            "5":"星期六",
            "6":"星期日"
        };
        return Day[""+obj+""];
    },
    resetOrderNumber:function(){
        $("#teachers_list input.order_set").blur(function(){
            var bl=true;
            var cur=$(this);
            var v1=cur.val().replace(new RegExp(" ","g"),"");
            var teacherId=cur.attr("rel");
            var org=cur.attr("org");
            if(v1==""){
                if(org==""||typeof(org)=="undefined"){
                    bl=false;
                }else{
                    v1="-1";
                    bl=true;
                }
            }else{
                var bv=!!/^[1-9]{1}\d*?$/.test(v1);
                if(bv==false){
                    alert("请输入正整数");
                    bl=false;
                }else{
                    bl=true;
                }
            }
            if(bl==true){
                var s={
                    url:Path.web_path+"/User/UserEdit/doSaveTeacher",
                    params:"teacherId="+teacherId+"&order="+v1,
                    sucrender:function(){
                        alert("操作成功");
                        Public.refreshList("teachers_list", teacherspage);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    /*删除课程*/
    deleSingleItem:function(){
        $("#course_list a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var courseId=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Course/CourseList/doDeleteCourse",
                    params:"courseId="+courseId,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            coursespage.genList(0);
        });
    },
    inipage:function(){
        this.doSearchAct();
        this.genList();
    }
};
/*培训课程详细*/
var course_editpage={
    inInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#course_name","课程名称");
        ba.inputValidate("#address","上课地点");
        ba.ValidateIntNumber("#price","课程价格");
        ba.inputValidate("#bake","备注信息");
        ba.inputValidate("#signUpStartDate","报名开始日期");
        ba.inputValidate("#signUpEndDate","报名结束日期");
        ba.inputValidate("#teachStartDate","开课开始日期");
        ba.inputValidate("#teachEndDate","开课结束日期");
        ba.inputValidate("#teachStartTime","上课开始时间");
        ba.inputValidate("#teachEndTime","上课结束时间");
        $("#cid").focus(function(){
            var cur=$(this);
            if(cur.hasClass("gray")){
                cur.val("").removeClass("gray");
            }
        }).blur(function(){
            var val=$(this).val().replace(new RegExp(" ","g"),"");
            if(val==""){
                $(this).val("请输入搜索关键词").addClass("gray");
            }
        }); 
    },
    choseCourseSchedule:function(){
        $("#c_date li").click(function(){
            var cur=$(this);
            if(cur.hasClass("cur")){
                cur.removeClass("cur");
            }else{
                cur.addClass("cur"); 
            }
            course_editpage.getDaysId();
        });
    },
    getDaysId:function(){
        var par=$("#c_date li.cur");
        var ck="";
        $.each(par,function(i,o){
            if(i==0){
                ck=$(o).attr("data_value");
            }else{
                ck+=","+$(o).attr("data_value");
            }
        });
        $("#teachTime").val(ck);
    },
    checkAll:function(){
        $("#course_name,#address,#price,#bake,#signUpStartDate,#signUpEndDate,#teachStartDate,#teachEndDate,#teachStartTime,#teachEndTime").trigger("blur");
        var bl=true;
        var len=$("div.course_edit ").find("div.tip.error").length;
        var ids=$("#c_date").find("li.cur").length;
        var rid=$("#rid").val();
        var signUpStartDate=$("#signUpStartDate").val();//报名开始日期
        var signUpEndDate=$("#signUpEndDate").val();//报名结束日期
        
        var teachStartDate=$("#teachStartDate").val();//上课开始日期
        var teachEndDate=$("#teachEndDate").val();//上课结束日期
        
        var teachStartTime=$("#teachStartTime").val();//上课开始时间
        var teachEndTime=$("#teachEndTime").val();//上课结束时间
        if(len==0){
            if(ids==0){
                bl=false;
                Base.resetButton();
                alert("请选择上课时间");
            }else if(rid==""){
                bl=false;
                Base.resetButton();
                alert("请选择关联者");    
            }else{
                signUpStartDate=parseInt(signUpStartDate.replace(/-/g,""));
                signUpEndDate=parseInt(signUpEndDate.replace(/-/g,""));
                teachStartDate=parseInt(teachStartDate.replace(/-/g,""));
                teachEndDate=parseInt(teachEndDate.replace(/-/g,""));
                teachStartTime=parseInt(teachStartTime.replace(/-/g,""));
                teachEndTime=parseInt(teachEndTime.replace(/-/g,""));
                if(signUpStartDate>=signUpEndDate){
                    bl=false;
                    Base.resetButton();
                    alert("报名开始日期不得大于报名结束日期");
                }else if(teachStartDate>teachEndDate){
                    bl=false;
                    Base.resetButton();
                    alert("开课开始日期不得大于开课结束日期");
                }else if(teachStartTime>teachEndTime){
                    bl=false;
                    Base.resetButton();
                    alert("上课开始时间不得大于上课结束时间");
                }else if(signUpStartDate>teachStartDate){
                    bl=false;
                    Base.resetButton();
                    alert("报名开始日期不得大于开课开始日期");
                }else{
                    bl=true;
                }
               
            }
        }else{
            bl=false;
            var ht=$("div.course_edit").find(".red_border").eq(0).parent().offset();
            $("html,body").animate({
                scrollTop: ht
            },1000);
            Base.resetButton();
        }
        return bl;
    },
    iniClassTime:function(){
        var dv=$("#teachTime").val().split(",");
        var marker2=[];
        $.each(dv,function(i,o){
            marker2.push(o);
        });
        $.each(marker2,function(i,o){
            var m=marker2[i];
            $("#c_date").find("li[data_value='"+m+"']").addClass("cur");
        });
    },
    /*标记关联机构|老师上课地点*/
    doMarkeSelectedPosition:function(obj){
        var loc_info=$(obj).find("a").attr("address");
        var point=$(obj).find("a").attr("loc").split(",");
        point[0]=parseFloat(point[0]);
        point[1]=parseFloat(point[1]);
        var point1={
            lat:point[1],
            lng:point[0]
        };
        $("#address").val(loc_info);
        $("#latitude").val(point1.lat);
        $("#longitude").val(point1.lng);
        agency_editpage.addOverLayToMap("map",point1,15);
    },
    /*输入地址定位*/
    getLocationByTxt:function(){
        $("#address").keydown(function(e){
            if(e.keyCode=="13"){
                var myGeo = new BMap.Geocoder();
                var cur=$(this);
                var val=cur.val().replace(new RegExp(" ","g"),"");
                if(val!=""){
                    try{
                        myGeo.getPoint(val, function(point1){
                            if (point1) {
                                agency_editpage.addOverLayToMap("map",point1,15);
                            }
                        },"中国");
                    }catch(e){
                    }
                }
            }
        });
    },
    inipage:function(){
        this.inInputCheck();
        this.choseCourseSchedule();
        this.iniClassTime();
        agency_editpage.loadMapScript();
        var ve=video_editpage;
        ve.showChoseUsersOps();
        ve.doSearchUsers("course");
        this.getLocationByTxt();
    }
};
/*======================================老师评论管理==============================*/
var commentspage={
    getCommentsList:function(i){
        var age=commentspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#comments_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Comment/CommentList/getCommentList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&type="+3,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.CommentsList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getCommentsList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.touser=o.toUser.name;
                    o.name=o.user.name;
                });
                var varr=["ops",'body','name','sendTime','touser',"id"];
                var tmp='<tr class="{ops}">'
                +'<td>@{name}:{body}</td>'
                +'<td>{name}</td>'
                +'<td>{sendTime}</td>'
                +'<td>{touser}</td>'
                +'<td><a href="javascript:;" class="mdelete" rel="{id}">删除</a></td>'
                +'</tr>';
                Base.GenTemp("comments_list", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#comments_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#comments_list a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Comment/CommentList/getDelComment",
                    params:"commentIds="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            commentspage.getCommentsList(0);
        });
    },
    inipage:function(){
        this.getCommentsList();
        this.doSearchAct();
    }
};
/*======================================机构评论管理==============================*/
var org_commentspage={
    getCommentsList:function(i){
        var age=commentspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#comments_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Comment/CommentList/getCommentList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&type="+1,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.CommentsList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getCommentsList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.touser=o.toUser.name;
                    o.name=o.user.name;
                });
                var varr=["ops",'body','name','sendTime','touser',"id"];
                var tmp='<tr class="{ops}">'
                +'<td>@{name}:{body}</td>'
                +'<td>{name}</td>'
                +'<td>{sendTime}</td>'
                +'<td>{touser}</td>'
                +'<td><a href="javascript:;" class="mdelete" rel="{id}">删除</a></td>'
                +'</tr>';
                Base.GenTemp("comments_list", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#comments_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#comments_list a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Comment/CommentList/getDelComment",
                    params:"commentIds="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            org_commentspage.getCommentsList(0);
        });
    },
    inipage:function(){
        this.getCommentsList();
        this.doSearchAct();
    }
};
/*======================================课程视频评论管理==============================*/
var video_commentspage={
    getCommentsList:function(i){
        var age=commentspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#comments_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var name=$("#search_box").val();
        if($("#search_box").hasClass("gray")){
            name="";
        }
        var s={
            url:Path.web_path +"/Comment/CommentList/getVideoCommentList",
            params:"page="+i+"&count="+pagesize+"&searchKey="+name+"&type="+4,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.CommentsList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getCommentsList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.name=o.user.name;
                    o.video_name=o.video.name;
                    o.touser=o.video.user.name;
                });
                var varr=["ops",'id','name','body','sendTime','video_name','touser'];
                var tmp='<tr class="{ops}">'
                +'<td>@{name}：{body}</td>'
                +'<td>{name}</td>'
                +'<td>{sendTime}</td>'
                +'<td>{video_name}</td>'
                +'<td>{touser}</td>'
                +'<td><a href="javascript:;" class="mdelete" rel="{id}">删除</a></td>'
                +'</tr>';
                Base.GenTemp("comments_list", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#comments_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#comments_list a.mdelete").click(function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Comment/CommentList/getDelComment",
                    params:"commentIds="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    doSearchAct:function(){
        Base.doSearchInteractiveAct();
        $("#dosearch").click(function(){
            video_commentspage.getCommentsList(0);
        });
    },
    inipage:function(){
        this.getCommentsList();
        this.doSearchAct();
    }
};
/*======================================消息管理==============================*/
/*系统消息列表*/
var noticespage={
    getSysNotices:function(i){
        var age=noticespage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#notices_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","4");
        dom.append(tmp);
        var s={
            url:Path.web_path +"/Notice/NoticeList/getSystemNoticeList",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.NoticeList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getSysNotices);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.body=o.noticeSys.body;
                    o.createTime=o.noticeSys.createTime;
                    o.notice_id=o.noticeSys.id;
                    var role=o.noticeSys.roleId;
                    if(role==-1){
                        o.name="全部";
                    }else if(role==2){
                        o.name="学生";
                    }else if(role==3){
                        o.name="老师";
                    }
                });
                var varr=["ops",'body','notice_id','name','createTime','id'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{body}</td>'
                +'<td>{createTime}</td>'
                +'<td>'
                +   '<a href="'+Path.web_path+'/sys_notedit?noticeSysId={notice_id}" class="medit">编辑</a>'
                +   '<a href="javascript:;" class="mdelete" rel="{id}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("notices_list", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#notices_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","4");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#notices_list tr td a.mdelete").live("click",function(){
            if(confirm("确认删除？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Notice/NoticeList/doDeleteSystemNotice",
                    params:"noticeId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.getSysNotices();
    }
};
/*系统消息详细*/
var sys_noteditpage={
    iniInputCheck:function(){
        Base.inputValidate("#cont", "消息内容");
    },
    checkAll:function(){
        $("#cont").trigger("blur");
        var bl=true;
        var len=$("div.notice_edit").find("div.tip.error").length;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniInputCheck();
    }
};
/*推广消息列表*/
var prom_noticespage={
    getRecomNotices:function(i){
        var age=prom_noticespage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#pro_notices");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","6");
        dom.append(tmp);
        var type=14;
        var s={
            url:Path.web_path +"/Notice/NoticeList/getPromoteNoticeList",
            params:"page="+i+"&count="+pagesize+"&type="+type,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.NoticeList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getRecomNotices);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    o.body=o.noticeSys.body;
                    o.image=o.noticeSys.image;
                    o.create_time=o.noticeSys.createTime;
                    o.url=o.noticeSys.url;
                    o.notice_id=o.noticeSys.id;
                    var role=o.noticeSys.roleId;
                    if(role==-1){
                        o.name="全部";
                    }else if(role==2){
                        o.name="学生";
                    }else if(role==3){
                        o.name="老师";
                    }
                });
                var varr=["ops",'body','notice_id','name','image','create_time','url','id'];
                var tmp='<tr class="{ops}">'
                +'<td>{name}</td>'
                +'<td>{body}</td>'
                +'<td><img  src="'+Path.image_path+'/{image}" class="pro_img" /></td>'
                +'<td>{url}</td>'
                +'<td>{create_time}</td>'
                +'<td>'
                +   '<a href="'+Path.web_path+'/prom_notedit?noticeSysId={notice_id}" class="medit">编辑</a>'
                +   '<a href="javascript:;" class="mdelete" rel="{id}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("pro_notices", varr, data, tmp);
                age.deleSingleItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#pro_notices");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","6");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#pro_notices tr td a.mdelete").live("click",function(){
            if(confirm("确认删除？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Notice/NoticeList/doDeletePromoteNotice",
                    params:"noticeId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.getRecomNotices();
    }
};
/*推广消息详细*/
var prom_noteditpage={
    iniInputCheck:function(){
        Base.inputValidate("#title", "消息标题");
        $("#img_link").focus(function(){
            Base.tip(this,lang.L0001.replace("{msg}","图片链接地址"), "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var org=cur.attr("org").replace(new RegExp(" ","g"),"");
            var b=true;
            if(str==""){
                if(org==""||typeof(org)=="undefined"){
                    Base.removeTip(this);
                }else{
                    cur.val(org);
                }
            }else{
                var bl=/^(((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+\.(com|edu|gov|mil|net|org|biz|info|name|museum|us|ca|uk|cn)(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&amp;%\$#\=~_\-]+))*$/.test(str)
                if(bl==true){
                    b=true;
                }else{
                    b=false;
                    msg="请填写正确的链接地址";
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        });
    },
    checkAll:function(){
        $("#title").trigger("blur");
        var bl=true;
        var len=$("div.notice_edit").find("div.tip.error").length;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    /*推广图片上传*/
    iniSingleImageUpload:function(){
        var sed=student_editpage;
        sed.iniMultiUpload("spanButtonPlaceholder","divFileProgressContainer","progress_id");  
    },
    inipage:function(){
        this.iniSingleImageUpload();
        this.iniInputCheck();
    }
};
/*======================================应用版本管理==============================*/
var appspage={
    getAppEdtionLists:function(i){
        var age=appspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#app_lists");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","4");
        dom.append(tmp);
        var type=14;
        var s={
            url:Path.web_path +"/AppEdtion/ApplicationEdtionList/getApplicationEdtionList",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.HiggsesAppList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getAppEdtionLists);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    var status=o.isPublish;
                    if(status==1){
                        o.sta="0";
                        o.text="取消发布";
                    }else{
                        o.sta="1";
                        o.text="发布";
                    }
                });
                var varr=["ops",'versionName','description','createTime','id','sta','text','downLink'];
                var tmp='<tr class="{ops}">'
                +'<td><a href="'+Path.image_path+'/{downLink}" title="{versionName}">{versionName}</a></td>'
                +'<td>{description}</td>'
                +'<td>{createTime}</td>'
                +'<td>'
                +   '<a href="'+Path.web_path+'/appedtion_edit?higgsesAppId={id}" class="medit">编辑</a>'
                +   '<a href="javascript:;" class="mdelete" rel="{id}">删除</a>'
                +   '<a href="'+Path.image_path+'/{downLink}" class="download ml20">下载</a>'
                +   '<a href="javascript:;" class="publish" status="{sta}" rel="{id}">{text}</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("app_lists", varr, data, tmp);
                age.deleSingleItem();
                age.setPublishStatus();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#app_lists");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","4");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleSingleItem:function(){
        $("#app_lists tr td a.mdelete").live("click",function(){
            if(confirm("确认删除？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/AppEdtion/ApplicationEdtionList/doDeleApplicationEdtion",
                    params:"higgsesAppId="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    setPublishStatus:function(){
        $("#app_lists tr td a.publish").unbind("click").bind("click",function(){
            var cur=$(this);
            var cid=cur.attr("rel");
            var sta=cur.attr("status");
            var s={
                url:Path.web_path+"/AppEdtion/ApplicationEdtionList/changeApplicationStatus",
                params:"higgsesAppId="+cid+"&isPublish="+sta,
                sucrender:function(){
                    alert("操作成功");
                    if(sta==1){
                        cur.text("取消发布").attr("status","0");
                    }else{
                        cur.text("发布").attr("status","1");
                    }
                },
                failrender:function(data){
                    if(data.error_code && data.error_code=="1006"){
                        alert(data.data);
                        location.href=Path.web_path+"/login";
                    }else{
                        alert(data.data);
                    }
                }
            };
            Base.AjaxRequest(s);
        });
    },
    inipage:function(){
        this.getAppEdtionLists();
    }   
};
var appedtion_editpage={
    /*应用包上传*/
    iniSingleImageUpload:function(){
        var swfu;
        var param=$("#post_params").val();
        swfu= new SWFUpload({
            // Backend Settings
            upload_url: ""+Path.web_path+"/upload",
            post_params: {
                "PHPSESSID" : param,
                "extensionName":"apk"
            },
            file_size_limit : "61200",	// 50mB
            file_types : "*.apk;",
            file_types_description : "应用包",
            file_upload_limit : "0",

            // Event Handler Settings - these functions as defined in Handlers.js
            //  The handlers are not part of SWFUpload but are part of my website and control how
            //  my website reacts to the SWFUpload events.
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadAppBagSuccess,
            upload_complete_handler : uploadComplete,

            // Button Settings
            button_image_url : ""+Path.web_path+"/Theme/lib/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
            button_placeholder_id :"spanButtonPlaceholder",
            button_width: 180,
            button_height: 18,
            button_text : '<span class="button" style="margin-top:1px;">选择应用包(最大不超过50MB)</span>',
            button_text_top_padding: 0,
            button_text_left_padding: 18,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            // Flash Settings
            flash_url : ""+Path.web_path+"/Theme/lib/swfupload/swfupload.swf",

            custom_settings : {
                upload_target :"divFileProgressContainer" ,
                progress_id:"progress_id"
            },

            // Debug Settings
            debug: false
        });
    },
    /*初始验证*/
    iniInputCheck:function(){
        var ba=Base;
        ba.inputValidate("#app_name","版本名称" );
        ba.inputValidate("#cont","更新说明" );
        $("#app_number").focus(function(){
            Base.tip(this,lang.L0001.replace("{msg}","版本号"), "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var org=cur.attr("org").replace(new RegExp(" ","g"),"");
            var b=true;
            if(str==""){
                if(org==""||typeof(org)=="undefined"){
                    msg=lang.L0002.replace("{msg}","版本号");
                    b=false;
                }else{
                    cur.val(org);
                }
            }else{
                var v=/^[1-9]{1}\d*?$/.test(str);
                if(v==true){
                    b=true;
                }else{
                    msg="输入只能为正整数";
                    b=false;
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        });
    },
    checkAll:function(){
        $("#app_name,#app_number,#cont").trigger("blur");
        var bl=true;
        var len=$("#app_edtion").find("div.tip.error").length;
        var ev=$("#app_package").val().replace(new RegExp(" ","g"),"");
        if(len==0){
            if(ev==""){
                Base.resetButton();
                alert("请选择应用包");
                bl=false;
            }else{
                bl=true;
            }
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniSingleImageUpload();
        this.iniInputCheck();
    }   
};
/*======================================查询范围设定==============================*/
var range_editpage={
    iniInputCheck:function(){
        var ba=Base;
        ba.ValidateIntNumber("#range","查询范围" );
        ba.ValidateIntNumber("#merge","合并范围" );
    },
    checkAll:function(){
        $("#range,#merge").trigger("blur");
        var bl=true;
        var len=$("#set_range").find("div.tip.error").length;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniInputCheck();
    } 
};
/*======================================修改密码==============================*/
var profilespage={
    iniInputCheck:function(){
        Base.ValidatePassword("#opd","原密码");
        $("#npd").focus(function(){
            Base.tip(this,"请输入新密码", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg="新密码不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VPwd(str);
                var v1=$("#cpd").val().replace(new RegExp(" ","g"),"");
                if(v1!=""){
                    var bl2=HValidate.VPwd(v1);
                }
                if(bl1==true&&bl2==true){
                    if(v1!=str){
                        Base.resetButton();
                        Base.tip("#cpd", "两次输入密码不一致", "error");
                    }else{
                        b=true;
                    }
                }else{
                    if(bl1==false){
                        msg="密码长度应在6到20位之间";
                        b=false;
                        Base.resetButton();
                    }else{
                        b=true;
                    }
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
        $("#cpd").focus(function(){
            Base.tip(this,"请再次输入密码", "right");
        }).blur(function(){
            var str=$(this).val().replace(new RegExp(" ","g"),"");
            var  cur=$(this);
            cur.val(str);
            var msg="";
            var b=true;
            if(str==""){
                msg="密码不能为空";
                b=false;
                Base.resetButton();
            }else{
                var bl1=HValidate.VPwd(str);
                var v1=$("#npd").val().replace(new RegExp(" ","g"),"");
                if(v1!=""){
                    var bl2=HValidate.VPwd(v1);
                }
                if(bl1==true&&bl2==true){
                    if(v1!=str){
                        msg="两次密码输入不一致";
                        b=false;
                        Base.resetButton();
                    }else{
                        b=true;
                    }
                }else{
                    if(bl1==false){
                        msg="密码长度应在6到20位之间";
                        b=false;
                        Base.resetButton();
                    }else{
                        b=true;
                    }
                }
            }
            if(!b){
                Base.tip(this, msg, "error");
            }else{
                Base.removeTip(this);
            }
        }); 
    },
    checkAll:function(){
        $("#opd,#cpd,#npd").trigger("blur");
        var bl=true;
        var len=$("#profile").find("div.tip.error").length;
        if(len==0){
            bl=true;
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniInputCheck();
    } 
};
/*======================================日志管理==============================*/
var logspage={
    getLogsList:function(i){
        var age=logspage;
        var tmp="";
        tmp=COMMONTEMP.T0002;
        if(!!!i){
            i=0;
        }
        i+=1; 
        var dom=$("#logslist");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","4");
        dom.append(tmp);
        var type=14;
        var s={
            url:Path.web_path +"/Log/LogsList/getList",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.AllCount;
                data = data.data.AppErrorLogList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", age.getLogsList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                    if(o.type==1){
                        o.platform="IOS";
                    }else{
                        o.platform="Android";
                    }
                });
                var varr=["ops",'fileName','platform','createTime','downLink','id'];
                var tmp='<tr class="{ops}">'
                +'<td><a href="'+Path.image_path+'/{downLink}" title="{fileName}" target="_blank">{fileName}</a></td>'
                +'<td>{platform}</td>'
                +'<td>{createTime}</td>'
                +'<td>'
                +    '<a href="javascript:;" class="mdelete mr20"  rel="{id}">删除</a>'
                +   '<a href="'+Path.image_path+'/{downLink}" class="download" target="_blank">下载</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("logslist", varr, data, tmp);
                age.deleteLogItem();
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#logslist");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","4");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleteLogItem:function(){
        $("#logslist tr td a.mdelete").live("click",function(){
            if(confirm("确认删除该条日志？")){
                var cur=$(this);
                var cid=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Log/LogsList/doDelete",
                    params:"id="+cid,
                    sucrender:function(){
                        cur.parents("tr").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    inipage:function(){
        this.getLogsList();
    } 
};
/*======================================行政区域==============================*/
var regionspage={
    //    genList:function(i){
    //       var age=regionspage;
    //        var tmp="";
    //        tmp=COMMONTEMP.T0002;
    //        if(!!!i){
    //            i=0;
    //        }
    //        i+=1; 
    //        var dom=$("#regions");
    //        dom.find("td").parents("tr").remove();
    //        tmp=tmp.replace("{number}","2");
    //        dom.append(tmp);
    //        var parentId="-1";
    //        var pcode=$("#prov").val();
    //        var ccode=$("#city").val();
    //        var qcode=$("#area").val();
    //        if(pcode=="-1"){
    //            parentId="-1";
    //        }else if(ccode=="-1"){
    //            parentId=pcode;
    //        }else if(qcode=="-1"){
    //            parentId=ccode;
    //        }else{
    //            parentId=qcode;
    //        }
    //        var s={
    //            url:Path.web_path +"/Region/RegionList/getRegionsList",
    //            params:"parentId="+parentId+"&isPage=1",
    //            sucrender:function(data){
    //                var len=data.data.AllCount;
    //                data = data.data.List;
    //                if(i==1){
    //                    Base.iniPagination(len, "#pagination", age.genList);
    //                }
    //                $.each(data,function(i,o){
    //                    if(i%2==0){
    //                        o.ops="ops";
    //                    }else{
    //                        o.ops="";
    //                    }
    //                });
    //                var varr=["ops","id","name"];
    //                var tmp='<tr class="{ops}">'
    //                +'<td>{name}</td>'
    //                +'<td>'
    //                +       '<a href="'+Path.web_path+'/region_edit?id={id}" class="medit">编辑</a>'
    //                +       '<a href="javascript:;" class="mdelete" rel="{id}">删除</a>'
    //                +'</td>'
    //                +'</tr>';
    //                Base.GenTemp("regions", varr, data, tmp);
    //                age.deleteAgencyItem();
    //            },
    //            failrender:function(data){
    //                if(data.error_code && data.error_code=="1006"){
    //                    alert(data.data);
    //                    location.href=Path.web_path+"/login";
    //                }else{
    //                    $("#all_count").text(0);
    //                    Base.iniPagination(0, "#pagination", null);
    //                    var dom=$("#regions");
    //                    dom.find("td").parents("tr").remove();
    //                    tmp=COMMONTEMP.T0003;
    //                    tmp=tmp.replace("{number}","2");
    //                    dom.append(tmp);
    //                }
    //            }
    //        };
    //        Base.AjaxRequest(s);
    //    },
    //    doSearchAction:function(){
    //      $("#dosearch").click(function(){
    //         regionspage.genList(0);
    //      });
    //    },
    getChildrenClass:function(){
        var par=$("#regions tr.tr_three ul li");
        par.live("click",function(){
            var cur=$(this);
            var pid=cur.find("label").attr("data_id");
            var s={
                url:Path.web_path+"/Place/GetPublicPlace/getRegionsList",
                params:"parentId="+pid,
                sucrender:function(data){
                    var tid=cur.parent().attr("id").split("_")[1];
                    tid=parseInt(tid,10)+1;
                    var tg=$("#cls_"+tid);
                    cur.parent().find(".effect").removeClass("effect");
                    cur.addClass("effect");
                    var index=tg.parent().index();
                    if(!!tg&&!!data.data){
                        var temp='<li><label data_id="{id}">{name}</label><a href="'+Path.web_path+'/region_edit?id={id}" class="medit"></a><a href="javascript:;" class="mdelete" rel="{id}"></a></li>';
                        var varr=["id","name"];
                        Base.GenTemp("cls_"+tid, varr, data.data, temp);
                        tg.parent().parent().find("td ul:gt("+index+")").html("");
                    }else if(!!tg&&!!!data.data){
                        tg.parent().parent().find("td ul:gt("+(index-1)+")").html("");
                    }
                    regionspage.iniHoverEffect();
                },
                failrender:function(data){
                    var tid=cur.parent().attr("id").split("_")[1];
                    var tg1=$("#cls_"+tid);
                    var index=tg1.parent().index();
                    tg1.parent().parent().find("td ul:gt("+index+")").html("");
                }
            };
            Base.AjaxRequest(s);
        });
    },
    deleteAgencyItem:function(){
        $("#regions a.mdelete").live("click",function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("rel");
                var s={
                    url:Path.web_path+"/Region/RegionList/deleRegion",
                    params:"id="+id,
                    sucrender:function(){
                        cur.parents("li").remove();
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });
    },
    iniHoverEffect:function(){
        var par=$("#regions  tr td ul li");
        par.hover(function(){
            $(this).addClass("hoved");
            $(this).find("a").show();
        }, function(){
            $(this).removeClass("hoved");
            $(this).find("a").hide();
        });
    },
    inipage:function(){
        //        this.genList();
        //        this.doSearchAction();
        this.getChildrenClass();
        this.iniHoverEffect();
        this.deleteAgencyItem();
    } 
};
var  region_editpage={
    iniPlaceEdit:function(){
        $("#cur_pos a.edit_zone").unbind().bind("click",function(){
            var cur=$(this);
            var stav=cur.attr("status");
            var  level=$("#level").val();
            if(stav==0){
                cur.text("取消").attr("status",1);
                $("#new_pos").show();
                $("#prov").find("option[value=0]").attr("selected",true);
                var city=$("select.city"),area=$("select.area"),town=$("select.town");
                city.html("<option value='-1'> - 请选择市 - </option>");
                area.html("<option value='-1'> - 请选择区 - </option>");
                town.html("<option value='-1'> - 请选择商区 - </option>");
                $("div.place").find("select").not("select.prov").not("select.city").removeAttr("disabled").css("color","#555");
            }else{
                cur.text("修改").attr("status",0);
                $("#new_pos").hide();
                var  is_edit=$("#data_id").val();
                if(level!=4){
                    $("#distan,#mark").addClass("hidden");
                }else{
                    $("#distan,#mark").removeClass("hidden");
                }
                if(is_edit==""){
                    $("#range").val("");
                }else{
                    $("#range").val($("#range").attr("org"));
                }
                Base.removeTip("#range");
                $("#latitude").val($("#latitude").attr("data_org"));
                $("#longitude").val($("#longitude").attr("data_org"));
                agency_editpage.loadMapScript();
            }
        });
    },
    iniInputCheck:function(){
        Base.inputValidate("#zone_name","区域名称");  
        Base.validateInter("#range","距离" );
    },
    checkAll:function(){
        var parentId="-1";
        var pcode=$("#prov").val();
        var ccode=$("#city").val();
        var qcode=$("#area").val();
        if($("#mark").hasClass("hidden")){
            $("#latitude").val("");
            $("#longitude").val("");
        }
        if($("#distan").hasClass("hidden")){
            $("#range").val("");
        }
        if(pcode=="0"){
            parentId="0";
        }else if(ccode=="-1"){
            parentId=pcode;
        }else if(qcode=="-1"){
            parentId=ccode;
        }else{
            parentId=qcode;
        }
        if(qcode!="-1"){
            $("#zone_name,#range").trigger("blur");
        }else{
            $("#zone_name").trigger("blur");
        }
        var bl=true;
        var len=$("div.region_edit").find("div.tip.error").length;
        var data_id=$("#data_id").val();
        if(data_id!=""){
            if(parentId!="0"){
                $("#cityIds").val(parentId);
            }
        }else{
            $("#cityIds").val(parentId);
        }
        if(len==0){
            if(qcode!="-1"){
                var x=$("#latitude").val();
                var y=$("#longitude").val();
                if(x!=""&&y!=""){
                    bl=true;
                }else{
                    bl=false;
                    Base.resetButton();
                    alert("请标记地图位置");
                }
            }
        }else{
            bl=false;
            Base.resetButton();
        }
        return bl;
    },
    inipage:function(){
        this.iniPlaceEdit();
        this.iniInputCheck();
        agency_editpage.loadMapScript();
    }  
};
/*======================================老师证书上传==============================*/
var teacher_filepage={
    iniImageUpload:function(){
        var sed=student_editpage;
        sed.iniMultiUpload("spanButtonPlaceholder1","divFileProgressContainer1","progress_id1");
        sed.iniMultiUpload("spanButtonPlaceholder2","divFileProgressContainer2","progress_id2");
        sed.iniMultiUpload("spanButtonPlaceholder3","divFileProgressContainer3","progress_id3");
        sed.iniMultiUpload("spanButtonPlaceholder4","divFileProgressContainer4","progress_id4");
        sed.iniMultiUpload("spanButtonPlaceholder5","divFileProgressContainer5","progress_id5");
        sed.iniMultiUpload("spanButtonPlaceholder6","divFileProgressContainer6","progress_id6");
        sed.iniMultiUpload("spanButtonPlaceholder7","divFileProgressContainer7","progress_id7");
        sed.iniMultiUpload("spanButtonPlaceholder8","divFileProgressContainer8","progress_id8");
        /*删除自我介绍图片*/
        $("ul.screen_shot li .progressCancel").click(function(){
            var par=$(this).parents(".sel");
            var obj1=$(par).find(".thumbnails");
            obj1.next().val("delete");
            obj1.next().attr("ref","delete");
            obj1.find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
            $(this).parent().remove();
        });  
    },
    checkAll:function(){
        var sep=student_editpage;
        sep.getSelfImages(); 
        var front=$("#front").val();
        var back=$("#back").val();
        var  bl=true;
        if(front==""){
            if(back==""){
                bl=true;
            }else{
                Base.resetButton();
                alert("请上传身份证正面图片");
                bl=false;
            }
        }else{
            if(back==""){
                Base.resetButton();
                alert("请上传身份证背面图片");
                bl=false;
            }else{
                bl=true;
            } 
        }
        return bl;
        
    },
    inipage:function(){
        this.iniImageUpload();
    }  
};
/*======================================反馈管理==============================*/
var feedbackspage={
    genList:function(i){
        var tmp="";
        tmp=COMMONTEMP.T0002;
        var dom=$("#fb_list");
        dom.find("td").parents("tr").remove();
        tmp=tmp.replace("{number}","5");
        dom.append(tmp);
        if(!!!i){
            i=0;
        }
        i+=1;
        var s={
            url:Path.web_path +"/FeedBack/FeedBackList/do_feedback_list",
            params:"page="+i+"&count="+pagesize,
            sucrender:function(data){
                var len=data.data.allCount;
                data = data.data.FeedbackList;
                if(i==1){
                    Base.iniPagination(len, "#pagination", feedbackspage.genList);
                }
                $.each(data,function(i,o){
                    if(i%2==0){
                        o.ops="ops";
                    }else{
                        o.ops="";
                    }
                });
                var varr=["ops","id","body","deviceInfo","createTime"];
                var tmp='<tr class="{ops}">'
                +'<td>{id}</td>'
                +'<td>{body}</td>'
                +'<td>{deviceInfo}</td>'
                +'<td>{createTime}</td>'
                +'<td>'
                +   '<a href="javascript:;" class="mdelete" cid="{id}">删除</a>'
                +'</td>'
                +'</tr>';
                Base.GenTemp("fb_list", varr, data, tmp);
            },
            failrender:function(data){
                if(data.error_code && data.error_code=="1006"){
                    alert(data.data);
                    location.href=Path.web_path+"/login";
                }else{
                    Base.iniPagination(0, "#pagination", null);
                    var dom=$("#fb_list");
                    dom.find("td").parents("tr").remove();
                    tmp=COMMONTEMP.T0003;
                    tmp=tmp.replace("{number}","5");
                    dom.append(tmp);
                }
            }
        };
        Base.AjaxRequest(s);
    },
    deleFbitem:function(){
       $("#fb_list tr td a.mdelete").live("click",function(){
            if(confirm("确定删除？")){
                var cur=$(this);
                var id=cur.attr("cid");
                var s={
                    url:Path.web_path+"/FeedBack/FeedBackList/feedback_delete",
                    params:"id="+id,
                    sucrender:function(){
                        cur.parents("tr").remove();
                        Public.refreshList("fb_list", fblistpage);
                    },
                    failrender:function(data){
                        if(data.error_code && data.error_code=="1006"){
                            alert(data.data);
                            location.href=Path.web_path+"/login";
                        }else{
                            alert(data.data);
                        }
                    }
                };
                Base.AjaxRequest(s);
            }
        });  
    },
    inipage:function(){
       this.genList(); 
       this.deleFbitem();
    }   
};
$().ready(function(){
    var $this=eval(curpage+"page");
    if(!!$this){
        $this.inipage();
    }  
});