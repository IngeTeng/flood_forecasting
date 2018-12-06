<?php

/**
 * fileupload.class.php 
 *
 * @version       v0.02
 * @create time   2014/7/24
 * @update time   2016/3/24
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 
    //PHP ini中关于上传的限制
	post_max_size  最大post数据大小
	upload_max_filesize搜索  最大能上传多大的文件

 */

class FileUpload{
	
	//允许的后缀
	private $allowext = array(
		//office
		'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
		//压缩包
		'zip', 'rar' , '7z' ,
		//文档
		'txt', 'pdf' ,
		//图片
		'jpg', 'jpeg', 'gif', 'png',
		//音频视频
		'mp3', 'mp4' , 'flv', 'swf'
	);
	
	//禁止的后缀
	private $forbidext = array(
		//程序语言
		'jsp', 'php', 'asp', 'py', 'js', 'css', 'html', 'htm', 'aspx', 'cgi', 'sql', 'c',
		//Windows执行文件
		'exe', 'dll', 'com', 'reg', 'bat', 'cmd'
	);

	private $allowsize = 0; 
	
	//构造函数，，，
	//allowsize：文件最大值(单位M，形如 8M)；$allowext: 数组，可以为空
	public function __construct($allowsize = 0, $allowext = array()) {

		if(!empty($allowext)) $this->allowext = $allowext;
		if(!$allowsize) $allowsize = '8M';

		$this->allowsize = $this->getMByte($allowsize);
		
	}
	
	//参数$elename : file域的名称。<input type='file' name='elename'/>
	//$newname --上传以后的名字；可以为空。
	//$savepath --文件保存路径，一定要以 “/” 结尾。
	//$auto_rename 是否自动重命名
	//如果$newname 为空 且 auto_rename 为false ，将保留原来的文件名字
	function upload($elename, $savepath, $newname = '', $auto_rename = false){
		
		$f_name    = basename($_FILES[$elename]["name"]); //被上传文件的名称
		$f_type    = $_FILES[$elename]["type"]; //被上传文件的类型
		$f_size    = $_FILES[$elename]["size"]; //被上传文件的大小，以字节计
		$f_tmpname = $_FILES[$elename]["tmp_name"]; //存储在服务器的文件的临时副本的名称
		$f_error   = $_FILES[$elename]["error"]; //由文件上传导致的错误代码

		//是否发生错误
		if($f_error) $this->uploadFileError($f_error);
		
		//文件后缀
		$f_ext     = $this->getFileExt($f_name);
		
		//检查上传类型
		//--是否在禁止列表
		$forbidext = $this->forbidext;
		if(in_array($f_ext, $forbidext)) {
			throw new Exception('文件类型禁止上传', 901);
		}
		//--是否在允许列表
		$allowext = $this->allowext;
		if(!in_array($f_ext, $allowext)) {
			throw new Exception('文件类型未被允许', 902);
		}

		//文件大小是否允许
		$allowsize = $this->allowsize;
		if($f_size > $allowsize) {
			throw new Exception('文件超过允许的大小', 903);
		}

		//文件是否是上传的文件
		if(!is_uploaded_file($f_tmpname)) {
			throw new Exception('非上传的文件', 904);
		}
		
		//文件重命名
		if(empty($newname) && $auto_rename) 
			$new_name = $this->setFileNameByDate().'.'.$f_ext;
		elseif(!empty($newname))
			$new_name = $newname.'.'.$f_ext;
		else
			$new_name = $f_name;

		//保存文件
		$f_path = $savepath.$new_name;
		if(move_uploaded_file($f_tmpname, $f_path)){
			return $new_name;//上传成功，返回文件名。
		}else{
			throw new Exception('文件写入失败，请检查上传目录是否可写', 905);
		}
	}
	
	//生成一个日期命名的文件名
	private function setFileNameByDate(){
		return date('YmdHis').rand(1000,9999);
	}
	
	//得到文件的后缀
	private function getFileExt($filename){
		//return strtolower(preg_replace('/.*\.(.*[^\.].*)*/iU', '\\1', $filename));
		$fext = pathinfo($filename, PATHINFO_EXTENSION);
		$fext = strtolower($fext);
		return $fext;
	}

	private function uploadFileError($err){
		switch($err){
			case 0:
				$info = '文件上传成功';
				break;
			case 1:
				$info = '文件大小超过了PHP.ini中upload_max_filesize的值('.$this->getMaxFileSize(true).')';
				break;
			case 2:
				$info = '文件大小HTML表单中MAX_FILE_SIZE选项指定的值';
				break;
			case 3:
				$info = '文件未上传完整';
				break;
			case 4:
				$info = '没有文件上传';
				break;
			case 5:
				$info = '文件大小为0';
				break;
			case 6:
				$info = '找不到临时文件夹';
				break;
			case 7:
				$info = '文件写入失败，请检查目录写权限';
				break;
			default:
				$info = '未知的上传错误';
				break;
			
		}
		throw new Exception($info, $err);
	}

	//将Byte数变为KB、MB
	public function getUploadSizeStr($bytes){
		if($bytes > 1048576){
			$bytes = $bytes / 1048576;
			$bytes = number_format($bytes, 2)."MB";
		}else{
			$bytes = $bytes / 1024;
			$bytes = intval($bytes)."KB";
		}
		return $bytes;
	}
	
	//从例如8M or 8MB 变成字节数
	private function getMByte($size){

		$size = strtoupper($size);
		$size = str_replace('M' , '', $size);
		$size = str_replace('MB', '', $size);
		$size = trim($size);
		$size = $size * 1024 * 1024;
		return $size;

	}

	//get系统最大POST量 ，单位byte
	public function getMaxPost($origin = false){
		$size = ini_get('post_max_size');
		if($origin) 
			return $size;
		else
			return $this->getMByte($size);
	}
	
	//get系统最大上传，单位byte
	public function getMaxFileSize($origin = false){
		$size = ini_get('upload_max_filesize');
		if($origin) 
			return $size;
		else
			return $this->getMByte($size);
	}

	//get允许的文件后缀
	public function getAllowExt(){
		return $this->allowext;
	}
}
?>