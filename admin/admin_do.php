<?php
	/**
	 * 管理员处理  admin_do.php
	 *
	 * @version       v0.03
	 * @create time   2014-9-4
	 * @update time   2016/3/25
	 * @author        IngeTeng
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$POWERID = '7001';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);

	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'add'://添加管理员
			$account   =  safeCheck($_POST['account'], 0);
			$password  =  safeCheck($_POST['password'], 0);
            $name      =  safeCheck($_POST['name'], 0);
            $openid    = $_POST['openid'];
            $phone      = safeCheck($_POST['phone'], 1);
            $group     =  strCheck($_POST['group']);
            try {
				$rs = Admin::add($account, $password,$phone,$name,$openid,$group);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑管理员
			$id      = safeCheck($_POST['id']);
			$account = safeCheck($_POST['account'], 0);
            $name    = safeCheck($_POST['name'], 0);
            $openid    = $_POST['openid'];
            $phone   = safeCheck($_POST['phone'], 1);
            $group   = strCheck($_POST['group']);
   
            try {
				$rs = Admin::edit($id, $account,$phone,$name,$openid,$group);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'del'://删除管理员
			$id = safeCheck($_POST['id']);
            
            try {
				$rs = Admin::del($id);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'reset'://重置密码
			$id = safeCheck($_POST['id']);
			$newpass = 'agriculture_888';
            
            try{
                $r = Admin::resetPwd($id, $newpass);
                echo $r;
            }catch(MyException $e){
                echo $e->jsonMsg();
            }
			break;
            
		case 'editpass'://修改密码
			$old_password = safeCheck($_POST['old_password'], 0);
			$new_password = safeCheck($_POST['new_password'], 0);
			$re_password  = safeCheck($_POST['re_password'], 0);
            
			if($new_password != $re_password){
				echo action_msg('两次密码不一致', -1);
                exit();
			}
			try {
				$r = $ADMIN->updatePwd($old_password, $new_password);
				echo $r;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
	}
?>