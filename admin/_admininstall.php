<?php

/**
 * _admininstall.php 后台（管理员）数据初始化
 *
 * @version       v0.02
 * @create time   2014-9-17
 * @update time   2016/3/25
 * @author        wiipu
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. ( http://www.wiipu.com)
 * @informaition  
 * Update Record:
 *
 */
	require_once('../config.inc.php');

	//数据结构
	//$sqlstr[0]="DROP DATABASE IF EXISTS ".$DB_name.";";
	//$sqlstr[1]="CREATE DATABASE `".$DB_name."`;";

	$sqlstr[0]="CREATE TABLE `".$DB_prefix."admin` (
	  `admin_id` int(4) NOT NULL AUTO_INCREMENT,
	  `admin_name` varchar(20) DEFAULT NULL,
	  `admin_account` varchar(50) DEFAULT NULL,
	  `admin_password` varchar(50) DEFAULT NULL,
	  `admin_salt` varchar(20) DEFAULT NULL,
	  `admin_group` tinyint(1) DEFAULT NULL,
	  `admin_lastloginip` varchar(100) DEFAULT NULL,
	  `admin_lastlogintime` int(11) DEFAULT NULL,
	  `admin_logincount` int(10) DEFAULT '0',
	  `admin_addtime` int(11) DEFAULT NULL,
	  PRIMARY KEY (`admin_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";

	$sqlstr[1]="INSERT INTO `".$DB_prefix."admin` (`admin_id`, `admin_name`, `admin_account`, `admin_password`, `admin_salt`, `admin_group`, `admin_lastloginip`, `admin_lastlogintime`, `admin_logincount`, `admin_addtime`) VALUES(1,'管理员', 'wiipu', '8d1d9dff73b5f7e01df1de561f8b2b89', 'VBonMHnqh)', 1, NULL, NULL, 0, NULL);";

	$sqlstr[2]="CREATE TABLE `".$DB_prefix."admingroup` (
	  `admingroup_id` int(4) NOT NULL AUTO_INCREMENT,
	  `admingroup_name` varchar(50) NOT NULL,
	  `admingroup_auth` varchar(600) DEFAULT NULL,
	  `admingroup_type` tinyint(1) NOT NULL DEFAULT '0',
	  `admingroup_order` int(4) NOT NULL DEFAULT '99',
	  PRIMARY KEY (`admingroup_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

	$sqlstr[3]="INSERT INTO `".$DB_prefix."admingroup` (`admingroup_id`, `admingroup_name`, `admingroup_auth`, `admingroup_type`, `admingroup_order`) VALUES(1, '超级管理员', '7003|7002|7001', 9, 11);";

	$sqlstr[4]="CREATE TABLE `".$DB_prefix."adminlog` (
	  `adminlog_id` int(10) NOT NULL AUTO_INCREMENT,
	  `adminlog_admin` int(4) DEFAULT NULL,
	  `adminlog_time` int(11) DEFAULT NULL,
	  `adminlog_log` varchar(600) DEFAULT NULL,
	  `adminlog_ip` varchar(100) DEFAULT NULL,
	  PRIMARY KEY (`adminlog_id`),
	  KEY `adminlog_admin` (`adminlog_admin`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

	$count = count($sqlstr);

	$conn  = mysql_connect($DB_host,$DB_user,$DB_pass) or die('连接数据库错误-请检查配置文件参数！');
	mysql_select_db($DB_name);
	mysql_query("set names utf8") or die('编码错误');
	//创建数据库
	for($i=0; $i<$count; $i++){
		if($i == 0){
			if(!mysql_query($sqlstr[$i])) die($sqlstr[$i].'错误<br/>'.mysql_error());
		}else{
			mysql_select_db($DB_name);
			if(!mysql_query($sqlstr[$i])) die($sqlstr[$i].'错误<br/>'.mysql_error());
		}
	}
	echo "管理员数据库初始化完成。为安全起见，请将本文件_admininstall.php删除！";


?>