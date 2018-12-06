<?php

/**
 * paramcheck.class.php 参数检查
 *
 * @version       $Id$
 * @createtime    2016/7/27
 * @updatetime    
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class ParamCheck {
	
	/**
	 * is_mobile() 手机号格式是否正确
	 * 
	 */
	static public function is_mobile($mobile){
		if(preg_match("/^1[0-9]{10}$/", $mobile)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * is_email()邮箱格式是否正确 
	 * 2016/7/27更新正则表达式
	 */
	static public function is_email($email)
	{
		if(preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]{2,}$/", $email)){
			return true;
		}else{
			return false;
		}

	}

	/**
	 * 检查时间格式
	 * 形如：yyyy-mm-dd hh:ii:ss
	 * 
	 */
	static public function is_datetime($timestr){
		
		$patten = "/^([0-9]{4})-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9])$/";
		
		if(preg_match($patten, $timestr, $parts)){
			if(checkdate($parts[2], $parts[3], $parts[1]))
				return true;
			else
				return false;
		}else{
			return false;
		}
		
	}

	/**
	 * 检查日期格式
	 * 形如：yyyy-mm-dd
	 * 
	 */
	static public function is_date($datestr){
		
		$patten = "/^([0-9]{4})-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])$/";
		
		if(preg_match($patten, $datestr, $parts)){
			if(checkdate($parts[2], $parts[3], $parts[1]))
				return true;
			else
				return false;
		}else{
			return false;
		}
		
	}

	/**
	 * 检查弱密码
	 * @return  弱密码--true 非弱密码--false
	 */
	static public function is_weakPwd($pwd){
		if(strlen($pwd)<6) return true;
		$weakPwd = array(
			'000000','0000000',
			'111111','666666','888888','999999','88888888','11111111',
			'aaaaaa',
			'123456789','1234567890','0123456789','12345678',
			'123456','654321','abcdef',
			'abc123','123123','321321','112233','abcabc',
			'aaa111','a1b2c3',
			'qwerty','qweasd',
			'password','p@ssword','passwd','passw0rd',
			'iloveyou','5201314',
			'admin1234','admin888','admin123'
		);
		if(in_array($pwd, $weakPwd)) 
			return true;
		else
			return false;
	}

	/**
	* 检查是否为数字(包括整数、小数和负数)
	* @param   string
	* @return  boolean
	*/
	static public function is_number($val){
		return (bool)preg_match('/^\-?([0-9]{1}|[1-9]+[0-9]*)(\.[0-9]+)?$/', $val);
	}
}
?>