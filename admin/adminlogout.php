<?php
	/**
	***登出表单处理
    * @version       v0.02
    * @create time   2014/9/4
    * @update time   2016/3/25
    * @author        IngeTeng
    * @copyright     Neptune工作室
	**/
	require_once('admin_init.php');
	Admin::logout();
	header("Location: adminlogin.html");exit();
?>