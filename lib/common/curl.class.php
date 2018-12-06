<?php

/**
 * curl.class.php CURL类
 *
 * @version       $Id$
 * @createtime    2016/7/5
 * @updatetime    
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Curl {
	
	/**
	 * post()
	 * 
	 * @param mixed $url
	 * @param mixed $postData 格式形如：id=3&name=tester //// TODO: wait 补充cookie情况和文件提交情况
	 * @param bool $is_https
	 * @return
	 */
	static public function post($url, $postData, $is_https = false){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if($is_https){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$re = curl_exec($ch);
		curl_close($ch);
		return $re;
	}

	/**
	 * get()
	 * 
	 * @param mixed $url
	 * @return
	 */
	static public function get($url, $is_https = false){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($is_https){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		$re = curl_exec($ch);
		curl_close($ch);
		return $re;
	}

	

}
?>