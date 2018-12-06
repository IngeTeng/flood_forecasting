<?php

/**
 * table_admingroup.class.php 数据库表:管理员组
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_admingroup extends Table {
	
	/**
	 * Table_admingroup::struct()  数组转换
	 * 
	 * @param array $data
	 * 
	 * @return
	 */
	static protected function struct($data){
	   	$r = array();
     
        $r['groupid']      = $data['admingroup_id'];
		$r['name']         = $data['admingroup_name'];
		$r['auth']         = $data['admingroup_auth'];
		$r['type']         = $data['admingroup_type'];
		$r['order']        = $data['admingroup_order'];
        
        return $r;
	}
    
    /**
     * Table_admingroup::getList() 管理員组列表
     * 
     * 
     * @return
     */
    static public function getList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."admingroup order by admingroup_order asc, admingroup_id desc";

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
     * Table_admingroup::getInfoByName() 根据名称查询管理员组详情
     * 
     * @param string $groupname  管理员组名
     * 
     * @return
     */
    static public function getInfoByName($groupname){
        global $mypdo;
        
        $groupname = $mypdo->sql_check_input(array('string', $groupname));
        
        $sql = "select * from ".$mypdo->prefix."admingroup where admingroup_name = $groupname limit 1";
        $rs  = $mypdo->sqlQuery($sql);
        if($rs){
    		$r = array();
    		foreach($rs as $key => $val){
    			$r[$key] = self::struct($val);
    		}
    		return $r[0];
    	}else{
    		return 0;
    	}
    }
    
    /**
     * Table_admingroup::add()  添加管理员组
     * 
     * @param string   $groupname  组名称
     * @param integer  $grouptype  组类别
     * 
     * @return
     */
    static public function add($groupname, $grouptype){
        global $mypdo;
        
        $param = array (
			'admingroup_name'    => array('string', $groupname),
			'admingroup_type'    => array('number', $grouptype)
		);
        return $mypdo->sqlinsert($mypdo->prefix.'admingroup', $param);
    }
    
    /**
     * Table_admingroup::getInfoById() 根据ID查询管理员组详情
     *
     * @param interger $groupId   管理员组ID
     * 
     * @return
     */
    static public function getInfoById($groupId){
    	global $mypdo;
    
    	$groupId = $mypdo->sql_check_input(array('number', $groupId));

    	$sql = "select * from ".$mypdo->prefix."admingroup where admingroup_id = $groupId limit 1";
    	$rs = $mypdo->sqlQuery($sql);
    	if($rs){
    		$r = array();
    		foreach($rs as $key => $val){
    			$r[$key] = self::struct($val);
    		}
    		return $r[0];
    	}else{
    		return 0;
    	}
    }
    
    /**
     * Table_admingroup::updateOrder() 修改排序
     * 
     * @param interger $id      管理员组ID
     * @param interger $order   管理员组顺序值
     * @return
     */
    static public function updateOrder($id, $order){
        global $mypdo;
        
        $where = array(
			'admingroup_id' => array('number', $id)
		);

		$param = array(
			'admingroup_order'=>array('number', $order)
		);
        return $mypdo->sqlupdate($mypdo->prefix.'admingroup', $param, $where);
    }
    
    /**
     * Table_admingroup::edit() 修改管理员组信息
     * 
     * @param string   $groupname  组名称
     * @param integer  $grouptype  组类型
     * @param integer  $groupid    组ID
     * @return
     */
    static public function edit($groupid, $groupname, $grouptype){
        global $mypdo;
        
        //参数
		$param = array (
			'admingroup_name'  => array('string', $groupname),
			'admingroup_type'  => array('number', $grouptype)
		);
		
		//where条件
		$where = array("admingroup_id" => array('number', $groupid));
        
        return $mypdo->sqlupdate($mypdo->prefix.'admingroup', $param, $where);
    }
    
    /**
     * Table_admingroup::getAdminCount() 管理员组所属管理员总数
     * 
     * @param interger $id   管理组ID
     * 
     * @return
     */
    static public function getAdminCount($id){
        global $mypdo;
        
        $id = $mypdo->sql_check_input(array('number', $id));

		$sql = "select count(*) as ct from ".$mypdo->prefix."admin where admin_group = $id";
		$r = $mypdo->sqlQuery($sql);
		if($r){
			return $r[0]['ct'];
		}else{
			return -1;
		}
    }
    
    /**
     * Table_admingroup::del() 删除管理员组
     * 
     * @param interger $groupid   管理组ID
     * 
     * @return
     */
    static public function del($groupId){
        global $mypdo;
        
        $where = array("admingroup_id" => array('number', $groupId));
        
		return $mypdo->sqldelete($mypdo->prefix.'admingroup', $where);
        
    }
    
    /**
     * Table_admingroup::updateAuth() 修改管理员组权限
     *
     * @param interger $groupid     管理组ID
     * @param string   $groupauth   权限字符串
     * @return
     */
    static public function updateAuth($groupId, $groupauth){
        global $mypdo;

		//参数
		$param = array (
			'admingroup_auth'  => array('string', $groupauth)
		);
		//where条件
		$where = array("admingroup_id" => array('number', $groupId));

		return $mypdo->sqlupdate($mypdo->prefix.'admingroup', $param, $where);
        
    }
    
 }
    