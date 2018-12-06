<?php
/**
 * 登陆验证  admincheck.php
 *
 * @version       v0.03
 * @create time   2014/9/4
 * @update time   2016/3/25
* @author        IngeTeng
 */
require_once('admin_init.php');

$check = Admin::checkLogin();
if(empty($check)) {
	header('Location: adminlogin.html');
	exit();//header()之后一定要加上退出
}else{
    $adminId = Admin::getSession();
    $ADMIN = new Admin($adminId);
    $ADMINGROUP = new Admingroup($ADMIN->getGroupID());
    $ADMINAUTH = $ADMINGROUP->getAuth();
}
	
?>