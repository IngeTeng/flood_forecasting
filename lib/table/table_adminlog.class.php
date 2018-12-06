<?php

/**
 * table_adminlog.class.php  数据库表:管理员日志表
 *
 * @version       $Id$ v0.01
 * @createtime    2014/09/05
 * @updatetime    2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_adminlog extends Table {
	
	/**
	 * Table_adminlog::struct() 数组转换
	 * 
	 * @param array $data
	 * 
	 * @return
	 */
	static protected function struct($data){
	   	$r = array();
     
        $r['logid']      = $data['adminlog_id'];
		$r['adminid']    = $data['adminlog_admin'];
		$r['logtime']    = $data['adminlog_time'];
		$r['logcontent'] = $data['adminlog_log'];
		$r['ip']         = $data['adminlog_ip'];
        
        return $r;
	}
	
    /**
     * Table_adminlog::getList()    管理员日志列表
     * 
     * @param integer $page         当前页
     * @param integer $pagesize     每页大小
     * 
     * @return
     */
    static public function getList($page = 1, $pagesize = 10){
        global $mypdo;
         
        $startrow = ($page - 1) * $pagesize;
        $sql = "select * from ".$mypdo->prefix."adminlog order by adminlog_id desc limit $startrow, $pagesize";
               
        $rs = $mypdo->sqlQuery($sql);
		if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return 0;
        }
    }
       /**
     * Table_adminlog::getAllList()    管理员日志列表
     * 
     * @param integer $page         当前页
     * @param integer $pagesize     每页大小
     * 
     * @return
     */
    static public function getAllList(){
        global $mypdo;
         

        $sql = "select * from ".$mypdo->prefix."adminlog order by adminlog_id desc ";
               
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return 0;
        }
    }
    
    /**
     * Table_adminlog::add()    添加日志
     *
     * @param string $log 日志内容
     * 
     * @return
     */
    static public function add($adminid, $log){
        global $mypdo;
        
        $time  = time();
		$ip    = Env::getIP();
        
		$param = array (
			'adminlog_admin'    => array('number', $adminid),
			'adminlog_ip'       => array('string', $ip),
			'adminlog_time'     => array('number', $time),
			'adminlog_log'      => array('string', $log)
		);
        return $mypdo->sqlinsert($mypdo->prefix.'adminlog', $param);
        
    }
    
     /**
      * Table_adminlog::getCount()   管理员日志总记录数
      * 
      * @return
      */
     static public function getCount(){
        global $mypdo;
        
        $sql = "select count(*) as ct from ".$mypdo->prefix."adminlog";

		$r = $mypdo->sqlQuery($sql);
		if($r){
			return $r[0]['ct'];
		}else{
			return 0;
		}
    }
  
    
 }
    