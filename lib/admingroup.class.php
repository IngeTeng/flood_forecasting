<?php

/**
 * admingroup.class.php 管理员分组类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        IngeTeng
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Admingroup {
	
	public  $gid    = 0;   //组ID
    public  $name   = '';  //组名
    public  $auth   = '';  //组权限，示例值：7001|7002|7003
    public  $type   = 0;   //组类型，默认值：0-普通管理员组；9-超级管理员组
    
    /**
     * Admingroup::__construct()   构造函数
     *
     * @param integer $gid   管理员分组组ID
     * 
     * @return
     */
	public function __construct($gid = 0) {
		if(empty($gid)) {
			throw new MyException('管理员组ID不能为空', 901);
		}else{
			$group = self::getInfoById($gid);
			if($group){
				$this->gid  = $gid;
				$this->auth = $group['auth'];
				$this->name = $group['name'];
				$this->type = $group['type'];
			}else{
				throw new MyException('管理员组不存在', 902);
			}
			
		}
	}

	/**
	 * Admingroup::add()   添加管理员组
	 * 
	 * @param string  $groupname      组名
	 * @param integer $grouptype      组类别值
	 * @return
	 */
	static public function add($groupname, $grouptype = 0){
		global $mypdo;
		
		if(empty($groupname)) throw new MyException('管理员组名不能为空', 101);

		//判断组名是否重复
        $g = Table_admingroup::getInfoByName($groupname);
		if($g) throw new MyException('组名已存在', 102); 
		
        $gid = Table_admingroup::add($groupname, $grouptype);
		if($gid){
            $msg = '添加管理员组('.$gid.':'.$groupname.')成功!';
            Adminlog::add($msg);

			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103); 
		}
	}
	
	/**
	 * Admingroup::edit() 修改管理员组
	 * 
	 * @param integer $groupid       管理组ID
	 * @param string  $groupname     管理组名
	 * @param integer $grouptype     类型
	 * 
	 * @return
	 */
	static public function edit($groupid, $groupname, $grouptype = 0){
		
		if(empty($groupid)) throw new MyException('管理员组ID不能为空', 101);
		if(empty($groupname)) throw new MyException('管理员组名不能为空', 102);
        
        //判断名称是否重复
        $g = Table_admingroup::getInfoByName($groupname);
		if($g && $g['groupid'] != $groupid) throw new MyException('组名已存在', 104);

        $rs = Table_admingroup::edit($groupid, $groupname, $grouptype);
        if($rs >= 0){//未做修改也算是修改成功

            $msg = '修改管理员组('.$groupid.')成功';
            Adminlog::add($msg);
			
			return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 103);
        }
	}

	/**
	 * Admingroup::del() 删除管理员组
	 * 
	 * @param integer $gid   管理员组ID
	 * 
	 * @return
	 */
	static public function del($gid){
		if(empty($gid)) throw new MyException('管理员组ID不能为空', 101);

		//判断该组下是否有管理员
		if(self::getAdminCount($gid) > 0)  throw new MyException('该组有管理员。请先删除或转移管理员。', 102);
		
        $rs = Table_admingroup::del($gid);
        
        if($rs == 1){
            $msg = '删除管理员组('.$gid.')成功!';
            Adminlog::add($msg);
			
			return action_msg($msg, 1);//成功
        }else{
			throw new MyException('操作失败', 103);
        }
	}

	/**
	 * Admingroup::getList() 获取管理员组列表
	 * 
	 * @return
	 */
	static public function getList(){
		return Table_admingroup::getList();
	}

	/**
	 * Admingroup::getInfoById() 管理员组详细信息
	 * 
	 * @param integer $gid 管理组ID
	 * 
	 * @return
	 */
	static public function getInfoById($gid){
		if(empty($gid)) throw new MyException('管理员组ID不能为空', 101);

		return Table_admingroup::getInfoById($gid);
	}

	/**
	 * Admingroup::updateOrder() 管理员组排序
	 * 
	 * @param integer $gid       管理员组ID
	 * @param integer $order     顺序数值
	 * @return
	 */
	static function updateOrder($gid, $order){
		if(empty($gid)) throw new MyException('管理员组ID不能为空', 101);
		if(empty($order)) throw new MyException('排序值不能为空', 102);
		if(!preg_match('/^-?\d+$/',$order)) throw new MyException('排序值必须为整数', 103);

        $r = Table_admingroup::updateOrder($gid, $order);
		if($r >= 0){
			return action_msg('排序成功', 1);
		}else{
			throw new MyException('操作失败', 104);
		}
	}

	/**
	 * Combo_cate::getCombo_cateCount() 数量
	 * 
	 * @param integer $id   管理员组ID
	 * 
	 * @return
	 */
	static public function getCombo_cateCount($id = 0){

		return Table_combo_cate::getCombo_cateCount($id);
	}
	
	/**
	 * Admingroup::updateAuth()修改管理员组权限
	 * 
	 * @param interger $gid    管理员组ID
	 * @param string   $auth   权限字符串
	 * @return
	 */
	static public function updateAuth($gid, $auth){

		if(empty($gid)) throw new MyException('管理员组ID不能为空', 101);
		//if(empty($auth)) throw new MyException('权限不能为空', 102);
	   
        $rs = Table_admingroup::updateAuth($gid, $auth);
        
		if($rs >= 0){
            $msg = '修改管理员组('.$gid.')权限成功!';
            Adminlog::add($msg);

            return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103);
		}
		
		
	}
    

	public function getAuth(){
        return $this->auth;
	}
	public function getName(){
        return $this->name;
	}

}

?>