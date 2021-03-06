<?php

/**
 * conn.php 初始化文件
 *
 * @version       v0.01
 * @create time   2016/12/8
 * @update time
 * @author        ty
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

//加载配置文件
require('config.inc.php');
//加载常用函数文件
require($LIB_PATH.'function_common.php');
//require($LIB_PATH.'function.inc.php');

//自动加载类文件 2016/6/23更新
require($LIB_COMMON_PATH.'autoloader.class.php');
spl_autoload_register('Autoloader::autoload');

/**
//2016/6/23起使用自动加载
//加载常用类文件
require($LIB_COMMON_PATH.'mypdo.class.php');
require($LIB_COMMON_PATH.'log.class.php');
require($LIB_COMMON_PATH.'exception.class.php');

//加载数据库table基础类
require($LIB_TABLE_PATH.'table.class.php');
 **/

//加载日志和数据库配置
$mylog = new MyLog($LOG_config);
$mypdo = new MyPdo();
$mypdo->debug(); //数据库调试，上线请关闭
$dbconn = $mypdo->dbconnect($DB_host, $DB_user, $DB_pass, $DB_name, $DB_prefix);
if(!$dbconn) die('数据库连接错误！');

?>