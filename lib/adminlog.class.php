<?php

/**
 * adminlog.class.php 管理员日志类
 *
 * @version       v0.01
 * @create time   2014/09/09
 * @update time   2016/02/18 2016/3/25
 * @author        IngeTeng
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Adminlog {

	public function __construct() {
	}

     /**
      * Adminlog::add() 记录管理员日志
      * 
	  * @param string $adminid   管理员ID
      * @param string $log       日志内容
      * 
      * @return
      */
     
    static public function add($log){
		if(empty($log)) throw new Exception('日志内容不能为空', 102);

		$adminid = Admin::getSession();

        return Table_adminlog::add($adminid, $log);
    }
    
	/** 
	 * Adminlog::logList()    管理员日志记录列表
	 * 
	 * @param integer $page        当前页
	 * @param integer $pagesize    每页大小
	 * 
	 * @return
	 */
	static public function logList($page = 1, $pagesize = 20){
	    return Table_adminlog::getList($page, $pagesize);
	}
    
    /** 
     * Adminlog::logAllList()    管理员日志记录列表
     * 
     * @param integer $page        当前页
     * @param integer $pagesize    每页大小
     * 
     * @return
     */
    static public function logAllList(){
        return Table_adminlog::getAllList();
    }
    
    /**
     * Adminlog::logCount()  管理员日志记录总数
     * 
     * @return
     */
    static public function logCount(){
	    return Table_adminlog::getCount();
	}

	
}
?>