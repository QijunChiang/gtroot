function fileQueueError(file, errorCode, message) {
    try {
        var errorName = "";
        if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
            errorName = "You have attempted to queue too many files.";
        }

        if (errorName !== "") {
            alert(errorName);
            return;
        }

        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                alert("不能上传0字节的图片");
                break;
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                alert("文件大小超出限制");
                break;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                alert("文件类型错误");
                break;
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                alert("文件数量超出限制");
                break;
            default:
                if (file !== null) {
                  alert("未处理错误");
                }
                break;
        }

    } catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0) {
            this.startUpload();
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadProgress(file, bytesLoaded) {
    try {
        var percent = Math.ceil((bytesLoaded / file.size) * 100);
        var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
        progress.setProgress(percent);
        if (percent === 100) {
            progress.setStatus("Creating thumbnail...");
            progress.toggleCancel(false, this);
        } else {
            progress.setStatus("正在上传...");
            progress.toggleCancel(false, this);
        }
    } catch (ex) {
        this.debug(ex);
    }
}
/*上传图片成功*/
function uploadSuccess(file, serverData) {
    try {
        var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
        if (!!serverData) {
           eval('var data='+serverData);
            addImage(data,this.customSettings.upload_target);
            progress.toggleCancel(false);
        } else {
            progress.toggleCancel(false);
            alert(serverData);
        }
                

    } catch (ex) {
        this.debug(ex);
    }
}
/*excel文件上传成功处理*/
function uploadExcelFileSuccess(file, serverData){
    try {
        var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
        if (serverData.substring(0, 7) === "FILEID:") {
            addValuetoInput( serverData.substring(7),this.customSettings.upload_target);
            progress.toggleCancel(false);
        } else {
            progress.toggleCancel(false);
            alert(serverData);
        }
    } catch (ex) {
        this.debug(ex);
    }
}
/*应用包上传成功处理*/
function uploadAppBagSuccess(file, serverData){
     try {
        var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
        if (serverData.substring(0, 7) === "FILEID:") {
            showAppEdition( serverData.substring(7),this.customSettings.upload_target);
            progress.toggleCancel(false);
        } else {
            progress.toggleCancel(false);
            alert(serverData);
        }
    } catch (ex) {
        this.debug(ex);
    }
}
/*展示应用包*/
function showAppEdition(src,tid){
    var par=$("#"+tid).parents(".sel").find("div.thumbnails");
    par.html(src);
    par.next().val(src);
}
/*赋值保存服务器返回值*/
function addValuetoInput(src,tid){
     var par=$("#"+tid).parents(".sel").find("div.thumbnails");
    par.next().val(src);
}
/*视频上传成功处理*/
function uploadVideoSuccess(file, serverData){
    try {
        var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
        if (serverData.substring(0, 7) === "FILEID:") {
            playerVideo( serverData.substring(7),this.customSettings.upload_target);
            progress.toggleCancel(false);
        } else {
            progress.toggleCancel(false);
            alert(serverData);
        }
    } catch (ex) {
        this.debug(ex);
    }
}
function playerVideo(src,tid) {
    var flv=Path.web_path+"/swfupload_dir/"+src+"";
    var par=$("#"+tid).parents(".sel").find("div.thumbnails");
    par.next().val(src);
    CKobject.getObjectById('ckplayer_a1').ckplayer_videoclear();
    CKobject.getObjectById('ckplayer_a1').ckplayer_newaddress('{f->'+flv+'}');
}
function uploadComplete(file) {
    try {
        /*  I want the next upload to continue automatically so I'll call startUpload here */
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            var progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
            progress.setComplete();
            progress.setStatus("");
            progress.toggleCancel(true);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadError(file, errorCode, message) {
    var progress;
    try {
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                try {
                    progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
                    progress.setCancelled();
                    progress.setStatus("Cancelled");
                    progress.toggleCancel(false);
                }
                catch (ex1) {
                    this.debug(ex1);
                }
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                try {
                    progress = new FileProgress(file,  this.customSettings.upload_target,this.customSettings.progress_id);
                    progress.setCancelled();
                    progress.setStatus("Stopped");
                    progress.toggleCancel(false);
                }
                catch (ex2) {
                    this.debug(ex2);
                }
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                break;
            default:
//                alert(message);
                break;
        }

    } catch (ex3) {
        this.debug(ex3);
    }

}
/*上传成功显示图片*/
function addImage(src,tid) {
    var par=$("#"+tid).parents(".sel").find("div.thumbnails");
    /*视频封面上传回调处理*/
    if(par.hasClass("cover")){
         $("#my_avatar").removeClass("cancled").removeAttr("style");
         avatarRender.c(src);
    }else{
        var obj=par.find("img");
        obj.attr("src",Path.web_path+"/swfupload_dir/"+src.filename+"");
    }
    par.next().val(src.filename);
}
/*取消已上传的图片*/
function CancleuploadedImg(par,ele){
    $("#"+ele).remove();
    var obj1=$(par).find(".thumbnails");
    var orig=obj1.next().attr("ref");
    if(obj1.hasClass("cover")){
        $('.select_area').html("");
    }
    if(orig==""){
        obj1.next().val("");
        obj1.find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
         if(obj1.hasClass("cover")){
            $("#b_avatar_div").find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
             $("#my_avatar").addClass("cancled").removeAttr("style");
             $("#x").val("");
             $("#y").val("");
             $("#w").val("");
             $("#h").val("");
             $("#percent").val("");
         }
    }else{
        if(orig=="unChange"){
            if(obj1.hasClass("cover")){
                 $("#b_avatar_div").find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
                 $("#b_avatar_div").find("img").removeAttr("style");
                 $("#my_avatar").addClass("cancled").removeAttr("style");
                 $("#x").val("");
                 $("#y").val("");
                 $("#w").val("");
                 $("#h").val("");
                 $("#percent").val("");
                 obj1.next().val("");
                 obj1.find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
            }else{
                obj1.next().val("unChange");
                obj1.find("img").attr("src",obj1.find("img").attr("orig"));
            }
        }else{
            obj1.next().attr("ref","delete");
            obj1.next().val("delete");
            obj1.find("img").attr("src",Path.web_path+'/Theme/images/admin/null.png');
        }
    }
}
/*取消已上传的视频*/
function CancleuploadedVideo(par,ele){
    CKobject.getObjectById('ckplayer_a1').ckplayer_videoclear();
    CKobject.getObjectById('ckplayer_a1').ckplayer_changeface();
    $("#"+ele).remove();
    var obj1=$(par).find(".thumbnails");
    var orig=obj1.next().attr("ref");
    if(orig==""){
        obj1.next().val("");
    }else{
        obj1.next().val("unChange");
        var flv=$("#video_url").val();
        CKobject.getObjectById('ckplayer_a1').ckplayer_newaddress('{f->'+flv+'}');
    }
}
/*取消已上传的应用包*/
function CancleuploadedApplication(par,ele){
   $("#"+ele).remove();
    var obj1=$(par).find(".thumbnails");
    var orig=obj1.next().attr("ref");
    if(orig==""){
        obj1.next().val("");
        obj1.text("");
    }else{
        if(orig=="unChange"){
            obj1.next().val("unChange");
            obj1.html("<a href='"+Path.image_path+"/"+obj1.attr("orig")+"'>下载</a>");
        }else{
            obj1.next().attr("ref","delete");
            obj1.next().val("delete");
            obj1.text("");
        }
    }  
}
/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID,progressID) {
    this.fileProgressID =progressID;
    this.fileProgressWrapper = document.getElementById(this.fileProgressID);
    if (!this.fileProgressWrapper) {
        this.fileProgressWrapper = document.createElement("div");
        this.fileProgressWrapper.className = "progressWrapper";
        this.fileProgressWrapper.id = this.fileProgressID;

        this.fileProgressElement = document.createElement("div");
        this.fileProgressElement.className = "progressContainer";

        var progressCancel = document.createElement("a");
        progressCancel.className = "progressCancel";
        progressCancel.href = "javascript:;";
        progressCancel.style.display = "none";
        progressCancel.appendChild(document.createTextNode(""));

        var progressText = document.createElement("div");
        progressText.className = "progressName";
        progressText.appendChild(document.createTextNode(file.name));

        var progressBar = document.createElement("div");
        progressBar.className = "progressBarInProgress";

        var progressStatus = document.createElement("div");
        progressStatus.className = "progressBarStatus";
        progressStatus.innerHTML = "&nbsp;";

        this.fileProgressElement.appendChild(progressCancel);
        this.fileProgressElement.appendChild(progressText);
        this.fileProgressElement.appendChild(progressStatus);
        this.fileProgressElement.appendChild(progressBar);

        this.fileProgressWrapper.appendChild(this.fileProgressElement);

        $("#"+targetID).find(".progressWrapper").remove();
        document.getElementById(targetID).appendChild(this.fileProgressWrapper);
        fadeIn(this.fileProgressWrapper, 0);

    } else {
        this.fileProgressElement = this.fileProgressWrapper.firstChild;
        this.fileProgressElement.childNodes[1].firstChild.nodeValue =file.name;
    }

    this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
    this.fileProgressElement.className = "progressContainer green";
    this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
    this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
    this.fileProgressElement.className = "progressContainer blue";
    this.fileProgressElement.childNodes[3].className = "progressBarComplete";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
    this.fileProgressElement.className = "progressContainer red";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
    this.fileProgressElement.className = "progressContainer";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
    this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
    this.fileProgressElement.childNodes[0].style.display = show ? "block" : "none";
    if (swfuploadInstance) {
        var fileID = this.fileProgressID;
        this.fileProgressElement.childNodes[0].onclick = function () {
//            swfuploadInstance.cancelUpload(fileID,false);
//            return false;
            var cur=$(this);
            var parent_id=cur.parents(".progressWrapper").attr("id");
            var parents=$("#"+parent_id).parents(".sel");
            if(parents.find("div.thumbnails").hasClass("video_type")){
                 CancleuploadedVideo(parents,fileID);
                 swfuploadInstance.cancelUpload(fileID,false);
                 return false;
            }else if(parents.find("div.thumbnails").hasClass("file")){
                 CancleuploadedApplication(parents,fileID);
                  swfuploadInstance.cancelUpload(fileID,false);
                 return false;
            }else{
                 CancleuploadedImg(parents,fileID);
                 swfuploadInstance.cancelUpload(fileID,false);
                 return false;
            }
        };
    }
};
