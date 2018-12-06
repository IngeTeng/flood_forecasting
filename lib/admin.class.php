<?php

/**
 * admin.class.php 管理员类
 *
 * @version       v0.02
 * @create time   2014/9/4
 * @update time   2016/3/25
 * @author        dxl jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class Admin {

	public $id = 0;                //管理员ID
	public $account = '';          //管理员账号           
        public $gid  = 0;              //属组ID
    
	public function __construct($id = 0) {
		if(!empty($id)) {
			$admin = self::getInfoById($id);
			if($admin){
				$this->id      = $admin['id'];
				$this->account = $admin['account'];
                $this->name    = $admin['name'];
                $this->phone   = $admin['phone'];
                $this->gid     = $admin['group'];
			}else{
				throw new MyException('管理员不存在', 902);
			}
		}
	}
	
    /**
     * Admin::login() 管理员登录
	 * 
	 * @param string  $account   账号
	 * @param string  $password  密码
	 * @param integer $cookie    
	 * 
     * @return
     */
    public function login($account, $password, $cookie = 0){
        
		if(empty($account))throw new MyException('账号不能为空', 101);
		if(empty($password))throw new MyException('密码不能为空', 102);

		//检查账号
		$admininfo = Table_admin::getInfoByAccount($account);
        if($admininfo == 0) {
			//不让用户准确知道是账号错误
			throw new MyException('账号或密码错误', 104);
		}

		//验证密码
		$password = self::buildPassword($password, $admininfo['salt']);
		if($password[0] == $admininfo['password']){
			//登录成功
			$this->id         = $admininfo['id'];
			$this->account    = $admininfo['account'];
            $this->gid        = $admininfo['group'];
			
			//设置cookie;
			if($cookie) $this->buildCookie();

			//设置session
			self::setSession(1, $this->id);
			
			//记录登陆信息
			$this->updateLoginInfo();
            
			//记录管理员日志log表
            $log = '成功登录!';
           Adminlog::add($log);

			return action_msg('登录成功', 1);//登陆成功
		}else{
			 throw new MyException('账号或密码错误', 104);
		}
	}

    /**
	 * Admin::buildCookie()   设置登陆cookie
	 * 
	 * @return void
	 */
	private function buildCookie(){
		global $cookie_ADMINID, $cookie_ADMINCODE;
		
		$cookie_time = time()+(3600*24*7);//7天

		setcookie($cookie_ADMINID, $this->id, $cookie_time);
		setcookie($cookie_ADMINCODE, self::getCookieCode($this->id, $this->account,$this->gid), $cookie_time);
	}

	//消除cookie
	static private function rebuildCookie(){
		global $cookie_ADMINID, $cookie_ADMINCODE;

		setcookie($cookie_ADMINID, '', time()-3600);
		setcookie($cookie_ADMINCODE, '', time()-3600);
	}
	
	//生成cookie校验码
	static private function getCookieCode($id = 0, $account = '', $group = 0){
		if(empty($id))throw new MyException('ID不能为空', 101);
		if(empty($account))throw new MyException('账号不能为空', 102);
        if(empty($group))throw new MyException('Group不能为空', 103);

		return md5(md5($account).md5($group).md5($id));//校验码算法
	}
	/**
	 * Admin::setSession()   设置登陆Session
	 * 
	 * @param $type  1--记录Session  2--清除记录
	 * @return void
	 */
	static private function setSession($type, $id = 0){
		global $session_ADMINID;
		
		if($type == 1){
			if(empty($id))throw new MyException('ID不能为空', 101);
			$_SESSION[$session_ADMINID]    = $id;
		}else{
			$_SESSION[$session_ADMINID]    = 0;
		}
	}
	
    /**
	 * Admin::updateLoginInfo() 更新登陆信息
	 * 
	 * @return
	 */
	public function updateLoginInfo(){
        return Table_admin::updateLoginInfo($this->id);
	}
	
     /**
	 * Admin::getInfo()    管理员详细信息
	 * 
	 * @param integer $aid  管理员ID
	 * 
	 * @return
	 */
	static public function getInfoById($id){
		if(empty($id))throw new MyException('ID不能为空', 101);

		return Table_admin::getInfoById($id);
	}
	
    /**
	 * Admin::logout()  退出登录
	 * 
	 * @return void
	 */
	static public function logout(){
		
		$log = '退出登录!';
        Adminlog::add($log);

		self::setSession(2);
		self::rebuildCookie();

	}
	
	/**
	 * Admin::checkLogin()  检查是否登录
	 * 
	 * @return
	 */
	static public function checkLogin(){
		global $session_ADMINID;
		global $cookie_ADMINID, $cookie_ADMINCODE;
		
		//是否存在session
		if(@$_SESSION[$session_ADMINID]){
			return true;
		}
		
		//不存在session则检查是否有cookie
		$cid   = $_COOKIE[$cookie_ADMINID];
		if(empty($cid)){
			return false;
		}
		
		//检查cookie数据是否对应，防止伪造
		$vcode = $_COOKIE[$cookie_ADMINCODE];
        $admin = Table_admin::getInfoById($cid);
		
		if(!$admin) {
			//cookie数据不正确，清理掉
			self::rebuildCookie();
			return false;
		}

		$code = self::getCookieCode($cid, $admin['account'], $admin['group']);
		
        if($vcode != $code){
			//cookie数据不正确，清理掉
			self::rebuildCookie();
            return false;
        }

		//cookie数据正确，重写Session
		self::setSession(1, $cid);
		return true;
	}
	
    /**
	 * Admin::getList()   管理员列表
	 * 
	 * @param integer $group
	 * 
	 * @return
	 */
	static public function getList($group = 0){

		//$startrow = ($page - 1) * $pagesize;

		return Table_admin::getList($group);
	}

	/**
	 * Admin::addAdmin() 添加管理员
	 * 
	 * @param string  $account   账号
	 * @param string  $password  密码
	 * @param integer $group     群组ID
	 * 
	 * @return
	 */
	static public function add($account, $password,$phone,$name,$group){
		
		//检查参数
		if(empty($account))throw new MyException('账号不能为空', 101);
        if(empty($name))throw new MyException('姓名不能为空', 102);
        if(empty($phone))throw new MyException('电话不能为空', 105);
        if(empty($group))throw new MyException('管理员组不能为空', 103);
		if(ParamCheck::is_weakPwd($password)) throw new MyException('密码太弱', 103);
        
		//获取信息//判断管理帐号是否重复
		$admin = Table_admin::getInfoByAccount($account);
		if($admin) throw new MyException('账号已经存在', 104);

		//检查管理员组是否存在
        $admingroup = Table_admingroup::getInfoById($group);
        if(!$admingroup) throw new MyException('管理员组不存在', 105);

        //生成管理员密码
        $password = self::buildPassword($password);

        $rs = Table_admin::add($account, $password,$phone,$name,$group);

		if($rs > 0){
			//记录管理员日志log表
			$msg = '成功添加管理员('.$account.')';
			Adminlog::add($msg);
            
            return action_msg($msg, 1);
		}else{
            throw new MyException('操作失败', 106);
		}

	}

    /**
     * Admin::search()
     * 
     * @param integer $page
     * @param integer $pagesize
     * @param integer $count //是否仅作统计 1 - 统计
     * @return
     */
    static public function search($page = 1, $pagesize = 10, $name='',$count = 0){
   
        if(!preg_match('/^\d+$/', $page)) $page = 1;
        if(!preg_match('/^\d+$/', $pagesize)) $pagesize = 10;
        
        return Table_admin::search($page, $pagesize, $name,$count);
    }




	/**
	 * Admin::buildPassword()  生成管理员密码
	 * 
	 * @param string $pwd   原始密码
	 * @param string $salt  密码Salt
	 * @return 
	 */
	static private function buildPassword($pwd, $salt = ''){

		if(empty($pwd))throw new MyException('密码不能为空', 101);
		if(empty($salt)) $salt = randcode(10, 4);//生成Salt

		$pwd_new = md5(md5($pwd).$salt);//加密算法

		return array($pwd_new, $salt);
	}
    /**
	 * Admin::deleteAdmin()  删除管理员
	 * 
	 * @param integer $adminId   管理员ID
	 * 
	 * @return
	 */
	static public function del($adminId){

        if(empty($adminId))throw new MyException('管理员ID不能为空', 101);
        
        $rs = Table_admin::del($adminId);
        if($rs == 1){
            $msg = '删除管理员('.$adminId.')成功!';
            Adminlog::add($msg);
			
            return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 102);
        }
	}
	/**
	 * Admin::edit() 修改管理员信息
	 * 
	 * @param integer $id      管理员ID
	 * @param string  $account 账号
	 * @param integer $group   群组
	 * 
	 * @return
	 */
	static public function edit($id, $account,$phone,$name,$group){
		
		if(empty($id))throw new MyException('管理员ID不能为空', 101);
		if(empty($account))throw new MyException('管理员账号不能为空', 102);
        if(empty($phone))throw new MyException('管理员密码不能为空', 103);
        if(empty($name))throw new MyException('管理员姓名不能为空', 107);
        if(empty($group))throw new MyException('管理员组不能为空', 104);
		//验证ID是否存在
		$admin = Table_admin::getInfoById($id);
		if(empty($admin)) throw new MyException('管理员不存在', 104);

		//验证账号是否改变，如果改变则需要检查账号的重复性
		if($admin['account'] != $account){
			$admin2 = Table_admin::getInfoByAccount($account);
			if($admin2) throw new MyException('账号已经存在', 105);
		}

        $rs = Table_admin::edit($id, $account,$phone,$name,$group);
        if($rs >= 0){
            $msg = '修改管理员('.$id.')信息成功!';
            Adminlog::add($msg);

            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 106);
        }
	}
    /**
	 * Admin::resetPwd()  重置密码
	 * @param integer  $id   管理员ID
	 * @param string  $newpass   新密码
	 * 
	 * @return
	 */
	static public function resetPwd($id, $newpass){
		
		if(empty($id))throw new MyException('管理员ID不能为空', 101);
		if(empty($newpass))throw new MyException('新的密码不能为空', 102);

		if(ParamCheck::is_weakPwd($newpass)) throw new MyException('新密码太弱', 103);

		$pass = self::buildPassword($newpass);

        $rs = Table_admin::updatePwd($id, $pass);

        if($rs == 1){
            $msg = '管理员('.$id.')密码成功重置为'.$newpass.'。';
            Adminlog::add($msg);

            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 104);
        }
	}

	/**
	 * Admin::updatePwd()      修改密码
	 * 
	 * @param string  $oldpwd   旧密码
	 * @param string  $newpwd   新密码
	 * 
	 * @return
	 */
	public function updatePwd($oldpwd, $newpwd){

		if(empty($oldpwd))throw new myException('旧密码不能为空', 101);
		if(empty($newpwd))throw new myException('新密码不能为空', 102);
		if(ParamCheck::is_weakPwd($newpwd)) throw new myException('新密码太弱', 104);

		$admin = self::getInfoById($this->id);

		//验证密码是否正确
		$oldpass = self::buildPassword($oldpwd, $admin['salt']);
		if($oldpass[0] != $admin['password']){
			throw new myException('旧密码错误', 103);
		}

		//产生新密码
		$newpass = self::buildPassword($newpwd);

		//修改密码
        $rs = Table_admin::updatePwd($this->id, $newpass);
        if($rs == 1){
            $msg = '修改密码成功';

            //Adminlog::add($msg);
            return action_msg($msg, 1);
        }else{
            throw new myException('操作失败', 444);
        }
	}
	/**
	 * Admin::getSession() 获得Session
	 * 
	 * @return 
	 */
	static public function getSession(){
		global $session_ADMINID;

		return $_SESSION[$session_ADMINID];
    }

	/**
	 * Admin::getGroupID() 获得管理组
	 * 
	 * @return 
	 */
	public function getGroupID(){
		return $this->gid;
    }

	public function getAccount(){
        return $this->account;
	}

    public function getName(){
        return $this->name;
    }

    public function getPhone(){
        return $this->phone;
    }

	//检查是否拥有权限
	static function checkAuth($powerId, $auth){
		if(empty($powerId))throw new MyException('权限ID不能为空', 101);
		//if(empty($auth))throw new MyException('权限序列不能为空', 102);

		$powers = explode('|', $auth);
		if(in_array($powerId, $powers)) {
			return true;
		}else{
			die('无访问权限');
		}
	}
}

?>