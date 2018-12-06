<?php

/**
 * config.inc.php 配置文件
 *
 * @version       v1.0.0
 * @create time   2016/11/28
 * @update time
 * @author        ty
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

//基础设置=================================================
//error_reporting(0);                          //网站开发时，务必关闭此项；网站上线时，务必打开此项。
define("WIIPHP_VERSION", '2.10.1');             //WiiPHP版本号（年-2015.月.当月第x次发布）
define("WIIPHP_UPDATE",  '20161010');          //WiiPHP更新日期
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');              //时区设置，服务器放置在国外的需要打开此项
session_start();
error_reporting(0);
//ob_start();
define("PROJECTCODE",    'WiiPHP');            //项目编号，建议修改，每个项目应该不同

//路径定义=================================================
$FILE_PATH = str_replace('\\','/',dirname(__FILE__)).'/'; //网站根目录路径
$LIB_PATH        = $FILE_PATH.'lib/';
$LIB_COMMON_PATH = $LIB_PATH.'common/';
$LIB_TABLE_PATH  = $LIB_PATH.'table/';
$HTTP_PATH = 'http://www.yanxin325.com/flood_forecasting/';              //网站访问路径，根据实际情况修改

//数据库连接参数设置=======================================
$DB_host   = '127.0.0.1';                                 //数据库地址
$DB_user   = 'admin';                                      //数据库用户
$DB_pass   = 'admin';                                      //数据库用户密码
$DB_name   = 'flood';                                     //数据库名称
$DB_prefix = 'flood_';                                    //表前缀，可以为空

//日志文件路径==============================================
//请给以下日志文件设置写权限
$LOG_PATH   = $FILE_PATH.'logs/';
$LOG_config = array(
	'common'      => $LOG_PATH.'common.log',
	'debug'       => $LOG_PATH.'debug.log'
);

//管理员Cookie 和 Session===================================
$cookie_ADMINID      = PROJECTCODE.'ACID';
$cookie_ADMINCODE    = PROJECTCODE.'ACCODE';
$session_ADMINID     = PROJECTCODE.'ASID';

?>