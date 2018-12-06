<?php

/**
 * table_admin.class.php 数据库表:管理员
 *
 * @version       $Id$ v0.01
 * @createtime    2014/9/3
 * @updatetime    2016/2/27
 * @author        dxl
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_admin extends Table {
	
	static protected function struct($data){
	   	$r = array();

		$r['id']         = $data['admin_id'];
		$r['name']       = $data['admin_name'];
        $r['phone']      = $data['admin_phone'];
		$r['account']    = $data['admin_account'];
		$r['password']   = $data['admin_password'];
		$r['salt']       = $data['admin_salt'];
        $r['group']      = $data['admin_group'];
		$r['loginip']    = $data['admin_lastloginip'];
		$r['logintime']  = $data['admin_lastlogintime'];
		$r['logincount'] = $data['admin_logincount'];
		$r['addtime']    = $data['admin_addtime'];
        
        return $r;
	}
    
     /**
     * Table_admin::getInfoByAccount() 根据账号获取详情
     * 
     * @param string $acount 账号
     * 
     * @return
     */
    static public function getInfoByAccount($account){
        global $mypdo;
        
        $account = $mypdo->sql_check_input(array('string', $account));
        
        $sql = "select * from ".$mypdo->prefix."admin where admin_account = $account limit 1";
        
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
     * Table_admin::getInfoById() 根据ID获取详情
     * 
     * @param Integer $adminId  管理员ID
     * 
     * @return
     */
    static public function getInfoById($adminId){
        global $mypdo;
        
        $adminId = $mypdo->sql_check_input(array('number', $adminId));
        
        $sql = "select * from ".$mypdo->prefix."admin where admin_id = $adminId limit 1";
        
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
     * Table_admin::updateLoginInfo() 登录时更新管理员信息
     * 
     * @param Integer $id 管理员ID
     * 
     * @return void
     */
    static public function updateLoginInfo($id){
        
        global $mypdo;
        
        $ip = Env::getIP();
		$param = array(
			'admin_lastloginip'   => array('string',$ip),
			'admin_lastlogintime' => array('number',time()),
			'admin_logincount'    => array('expression','admin_logincount+1'),
		);
		$where = array('admin_id'=>array('number',$id));
        
        return $mypdo->sqlupdate($mypdo->prefix.'admin', $param, $where);
    }
    
    /**
     * Table_admin::edit() 修改管理员账号和组信息
     * 
     * @param Integer $id         管理员ID
     * @param string  $account    账号
     * @param Integer $group      管理员所属组
     * 
     * @return
     */
    static public function edit($id, $account,$phone,$name,$openid,$group){
        
        global $mypdo;
        
        //参数
    	$param = array (
    		'admin_account'   => array('string', $account),
            'admin_phone'     => array('number' ,$phone),
            'admin_name'      => array('string' ,$name),
            'admin_group'     => array('number', $group)
    	);
        $where = array('admin_id'=> array('number', $id));
            
        return $mypdo->sqlupdate($mypdo->prefix.'admin', $param, $where); 
    }
    
    /**
     * Table_admin::add()  添加管理员
     * 
     * @param string  $account   管理员账号
     * @param array   $password  密码及salt
     * 
     * @return
     */
    static public function add($account, $password,$phone,$name,$openid,$group){
        
        global $mypdo;

		//写入数据库
		$param = array (
			'admin_account'   => array('string', $account),
            'admin_phone'     => array('number' ,$phone),
            'admin_name'      => array('string' ,$name),
			'admin_password'  => array('string', $password[0]),
			'admin_salt'      => array('string', $password[1]),
            'admin_group'     => array('number', $group),
			'admin_addtime'   => array('number', time())
		);
        return $mypdo->sqlinsert($mypdo->prefix.'admin', $param);
    }
   
    /**
     * Table_admin::del() 删除管理员
     * 
     * @param Integer $adminId   管理员ID
     * 
     * @return 
     */
    static public function del($adminId){
        
        global $mypdo;
        
		$where = array(
			"admin_id" => array('number', $adminId)
		);
        
        return $mypdo->sqldelete($mypdo->prefix.'admin', $where);
    }
    
    /**
     * Table_admin::getList() 管理员列表
     * 
     * @param mixed $group      管理员组类型
     * 
     * @return
     */
    static public function getList($group = 0){
        
        global $mypdo;
        $group   = $mypdo->sql_check_input(array('number', $group));
        $sql = "select * from ".$mypdo->prefix."admin";
         if($group){
            $sql .= ' and admin_group = '.$group;
        }
        $sql .=" order by admin_id desc";
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
      * Table_admin::updatePwd() 修改密码
      * 
      * @param Integer $id        管理员ID
      * @param array   $newpass   新密码及salt
      * 
      * @return
      */
     static public function updatePwd($id, $newpass){
        
        global $mypdo;

		//修改参数
		$param = array(
			"admin_password" => array('string', $newpass[0]),
			"admin_salt"     => array('string', $newpass[1])
		);
		//where条件
		$where = array(
			"admin_id" => array('number', $id)
		);
		//返回结果
		$r = $mypdo->sqlupdate($mypdo->prefix.'admin', $param, $where);
        return $r;
    }



  /**
     * Table_admin::search() 新闻搜索
     * 
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function search($page = 1, $pagesize = 10, $name='', $count = 0){
        global $mypdo, $mylog;
        
        $page     = $mypdo->sql_check_input(array('number', $page));
        $pagesize = $mypdo->sql_check_input(array('number', $pagesize));
        
        $name = "%$name%";
        $name    = $mypdo->sql_check_input(array('string', $name));
        
        $startrow = ($page - 1) * $pagesize;
        
        $sql = "select * from ".$mypdo->prefix."admin where 1=1 ";
        $sql .= " and admin_name like $name ";     
        if($count){
            
            $r = $mypdo->sqlQuery($sql);
            return count($r);

        }else{
            $sql .= " order by admin_id desc limit $startrow, $pagesize";
               
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
        
        
    }




     /**
      * Table_admin::getCount()   新闻统计
      * 
      * @param int $sort         新闻分类
      * @param int $status       新闻状态
      * @return
      */
     static public function getCount(){
        global $mypdo;
        $sql = "select count(*) as ct from ".$mypdo->prefix."admin where 1=1";
        
        $r = $mypdo->sqlQuery($sql);
        if($r){
            return $r[0]['ct'];
        }else{
            return 0;
        }
    }
    

}
?>