<?php
require_once '../configs/smarty-config.php';
header('Content-Type:text/html;charset=utf-8');
userLoginCheckFilter();
$userProfile = get_user_profile_student($currUser->sessionKey);
if(!$userProfile->result){
	msgAlert('登录超时或未登录,请先登录', __SITE_DOMAIN.__SITE_PATH.'/user/login.php');
	return;
}
$userAllSettins = get_all_settings($currUser->sessionKey);
if(!$userProfile->result){
	msgAlert('登录超时或未登录,请先登录', __SITE_DOMAIN.__SITE_PATH.'/user/login.php');
	return;
}

$smarty->assign('userProfile',$userProfile->data);
$smarty->assign('userAllSettins',$userAllSettins->data);
$action = empty($_REQUEST['action']) ? "" : $_REQUEST['action'];
switch ( $action ) {
	case 'edit':
		$smarty->display('user/edit.tpl');
		break;
	case 'updateUserProfile':
		$name = empty($_REQUEST['name']) ? "" : $_REQUEST['name'];
		$sex = empty($_REQUEST['sex']) ? "0" : $_REQUEST['sex'];
		$birthday = empty($_REQUEST['birthday']) ? "" : $_REQUEST['birthday'];
		$photo = uploadFileHandle('photo');
		$ret = update_profile_student($currUser->sessionKey, $photo, $name, $sex, $birthday);
		if($ret->result){
			echo "<script>alert('更新个人信息成功');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/my.php';</script>";
		}else{
			if($ret->data->error_code==1006){
				echo $errorScript;
			}else{
				echo "<script>alert('".$ret->data->error."');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/my.php';</script>";
			}
		}
		break;
	case 'modifypwd':
		$smarty->display('user/modifypwd.tpl');
		break;
	case 'updateUserPwd':
		$oldPassword = empty($_REQUEST['oldPassword']) ? "" : $_REQUEST['oldPassword'];
		$newPassword = empty($_REQUEST['newPassword']) ? "" : $_REQUEST['newPassword'];
		$ret = update_password($currUser->sessionKey, $oldPassword, $newPassword);
		if($ret->result){
			echo "<script>alert('更新密码成功,请重新登录');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/logout.php';</script>";
		}elseif($ret->data->error_code==1006){
			echo $errorScript;
		}elseif($ret->data->error_code==1016){
			echo "<script>alert('你的旧密码错误');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/my.php';</script>";
		}else{
			echo "<script>alert('".$ret->data->error."');window.location ='".__SITE_DOMAIN.__SITE_PATH."/user/my.php';</script>";
		}
		break;
	default:
		$smarty->display('user/my.tpl');
		break;
}
function uploadFileHandle($elName){
	//echo $_FILES[$elName]["type"].'|||'.$_FILES[$elName]["size"].'|||'.$_FILES[$elName]["error"].'|||'.$_FILES[$elName]["tmp_name"];
	if ((($_FILES[$elName]["type"] == "image/gif")
		|| ($_FILES[$elName]["type"] == "image/jpeg")
		|| ($_FILES[$elName]["type"] == "image/png")
		|| ($_FILES[$elName]["type"] == "image/pjpeg"))
		&& ($_FILES[$elName]["size"] < 20000)){
		if ($_FILES[$elName]["error"] == 0){
			$uploadFilePath = __UPLOAD_PATH.$_FILES[$elName]["name"];
			if (file_exists($uploadFilePath)){
				//echo $_FILES["file"]["name"] . " already exists. ";
		    }else{
				move_uploaded_file($_FILES[$elName]["tmp_name"], $uploadFilePath);
				//echo "Stored in: " . $uploadFilePath;
			}
			return $uploadFilePath;
		}else{
		   return '';
	    }
	}else{
		 return '';
	}
}
?>