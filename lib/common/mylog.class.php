<?php

/**
 * mylog.class.php 系统日志类文件
 *
 * @version       v0.02
 * @create time   2014/7/24
 * @update time   2016/3/25
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	
	//三种功能
	一种普通日志，记录在logs/common.log中
	一种调试日志，记录在logs/debug.log中
	一种自定义日志，写入文件地址需要作为参数传入。

 */

class Mylog {

	public $errcode = 0;
	public $errmsg  = '';
	
	//当前时间
	private $datetime;  //Y-m-d H:i:s

	//当前日志文件
	private $logfile = '';
	private $maxlogfilesize = 100;    //最大的日志，单位MB

	//log文件存放位置
	private $file_common = '';
	private $file_debug = '';


	public function __construct(array $logconfig){
		$this->datetime = date('Y-m-d H:i:s');
		
		$this->file_common     = $logconfig['common'];
		$this->file_debug      = $logconfig['debug'];

	}
	
	//普通日志
	public function filelog($log, $filepath = ''){
		if(empty($filepath)) 
			$this->logfile = $this->file_common;
		else
			$this->logfile = $filepath;

		if($this->checkfile()){
			$content = $this->datetime.' ';
			$content .= $log."\r\n";
			$this->writefile($content);
		}
	}

	//调试日志
	public function debug($log, $is_exit = true){
		$this->logfile = $this->file_debug;
		if($this->checkfile()){
			$content = $this->datetime.' ';
			$content .= $log."\r\n";
			$this->writefile($content);
		}
		if($is_exit) exit();
	}

	//检查文件
	private function checkfile(){
		$file = $this->logfile;
		
		if($this->isExistFile()){
			//如果日志文件大小超过预期，就把当前的日志文件重命名，再新建一个日志文件
			$filesize     = $this->getFileSize();
			$filepathinfo = $this->getFilePathInfo();
			if($filesize > $this->maxlogfilesize * 1024 * 1024){
				$dir = $filepathinfo['dirname'];
				$name = $filepathinfo['filename'];
				$newname = $dir.'/'.$name.time().'.log';
				rename($file, $newname);
				$this->buildfile($file);
			}
			//检查文件是否可写
			if(!is_writeable($file)){
				$errmsg = '文件'.$file.'不可写';
				throw new myException($errmsg, 901);
			}
		}else{
			$errmsg = '文件'.$file.'不存在，并且尝试创建该文件失败';
			throw new myException($errmsg, 902);
		}
		return true;
	}
	
	//检查文件是否存在，不存在则尝试新建
	private function isExistFile(){
		$file = $this->logfile;
		if(!is_file($file)) {
			if($this->buildfile()) return true;
			return false;
		}
		return true;
	}

	//新建文件
	//@return 建立成功true 建立失败false
	private function buildfile($newfile = ''){
		if(empty($newfile)){ 
			$file = $this->logfile;
		}else{
			$file = $newfile;
			$this->logfile = $newfile;
		}
		
		if($this->checkDir()){
			if(touch($file))
				return true;
			else{
				return false;
			}
		}else{
			return false;
		}
	}

	//检查文件所在目录的存在与可写
	private function checkDir(){
		$file = $this->logfile;
		$dir = pathinfo($file, PATHINFO_DIRNAME);
		if($dir){
			if(!is_writeable($dir)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	//得到文件大小
	private function getFileSize(){
		$file = $this->logfile;
		return filesize($file);
	}

	//得到文件信息
	private function getFilePathInfo(){
		$file = $this->logfile;
		return pathinfo($file);
	}

	//写入文件
	private function writefile($content){
		$file = $this->logfile;
		$file_h = fopen($file, "a");
		fwrite($file_h, $content);
		fclose($file_h);
	}
}
?>