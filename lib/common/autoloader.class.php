<?php

/**
 * autoloader.class.php 自动加载类
 *
 * 只自动加载lib下的业务类、common下的常用类以及table层的类。
 *
 * @version       $Id$
 * @createtime    2016/6/23
 * @updatetime    
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Autoloader {
	
	/**
	 * 构造函数
	 * 
	 * @return void
	 */
	public function __construct() {
		
	}
	
	/**
	 * autoload
	 * 
	 * @return void
	 */
	public static function autoload($classname) {
		global $LIB_PATH, $LIB_COMMON_PATH, $LIB_TABLE_PATH;
        
		$classname = strtolower($classname);//文件名都是小写的
		
		//加载公用类
		$filename = $LIB_COMMON_PATH.$classname.".class.php";
        if(is_file($filename)) {
            include $filename;
            return;
        }

		//加载table层类
		$filename = $LIB_TABLE_PATH.$classname.".class.php";
        if(is_file($filename)) {
            include $filename;
            return;
        }
		
		//加载业务类
        $filename = $LIB_PATH.$classname.".class.php";
        if(is_file($filename)) {
            include $filename;
            return;
        }
    }
}
?>