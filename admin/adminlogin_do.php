<?php
	/**
	*登录表单处理
    * @version       v0.02
    * @create time   2014/9/4
    * @update time   2016/3/25
    * @author        IngeTeng
    * @copyright     Neptune工作室
	**/
	require_once('admin_init.php');

	//获取值
	$account   = safeCheck($_POST['account'], 0);
	$password  = safeCheck($_POST['pass'], 0);
    
	$vercode   = safeCheck($_POST['vercode'], 0);
	$remember  = safeCheck($_POST['remember']);//是否记住cookie

	//校验验证码
	if($vercode != $_SESSION['WiiPHP_imgcode']){
		echo action_msg('验证码错误', -4);
        exit();
	}else{
		try {
			$admin = new Admin();
			$r = $admin->login($account, $password, $remember);
			echo $r;
		}catch(MyException $e){
			echo $e->jsonMsg();
		}
	}
	
?>