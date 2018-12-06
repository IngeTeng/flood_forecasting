<?php

/**
 * mypdo.class.php PDO类文件
 *
 * @version       v0.02
 * @create time   2014/7/24
 * @update time   2016/2/27
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 * 
 * 该类的使用方法简介：
 *
 * ------------SQL查询---------
 * 查询带有传入参数的，先检查参数。例如：
 * $name = $mypdo->sql_check_input(array('string', $name));
 * 经过检查的参数直接嵌入SQL语句，无需添加单引号。例如：
 * $sql = "select * from shop where shop_name = $name ";
 * 执行查询:
 * $mypdo->sqlQuery($sql);
 * 
 * ------------SQL写入---------
 * 示例：
 * $param = array(
 *      'id'   => array('number', 999),
 *		'name' => array('string', 'tester')
 * );
 * $mypdo->sqlinsert('tablename', $param);
 *
 * ------------SQL修改---------
 * 示例：
 * $param = array(
 *		'name' => array('string', 'Test')
 * );
 * $where = array(
 *      'id'   => array('number', 999)
 * );
 * $mypdo->sqlupdate('tablename', $param, $where);
 *
 * ------------SQL删除---------
 * 示例：
 * $where = array(
 *      'id'   => array('number', 999)
 * );
 * $mypdo->sqldelete('tablename', $where);
 *
 */

class MyPdo {
    
    public  $pdo      = null;
	public  $hostname = '';
	public  $username = '';
	public  $database = '';
	public  $prefix   = '';
    private $debug    = false;
	
	public function __construct(){
		
	}
	public function dbconnect($hostname, $username, $password, $database, $prefix) {
		$dsn = 'mysql:dbname='.$database.';host='.$hostname.';port=3306';
		try {
			$mypdo = new PDO($dsn, $username, $password); 
			$mypdo -> query('set names utf8;');
			$mypdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo = $mypdo;
			$this->prefix = $prefix;
			return $this;
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage();
		}
	}
	
	public function debug(){
		$this->debug = true;
	}
	
	//查询
	//本函数用于执行查询语句，增删改语句请使用其他专用函数
	//@return 查询结果集array()。
	//TODO: 禁止多条查询语句同时执行；禁止增删改语句通过这个函数执行。
	public function sqlQuery($sql){
		global $CountSQLSelect;
		try {
			//统计查询语句数量2016/6/16
			$sqlactstr = strtolower(substr($sql, 0, 6));
			if($sqlactstr == 'select') $CountSQLSelect++;
			
			$rs = $this -> pdo -> query($sql);
			$i = 0;
			$data = array();
			foreach($rs->fetchAll() as $result) {
				$data[$i] = $result;
				$i++;
			}
			$rs->closeCursor();
			return $data;
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage().'<br/>错误的SQL语句：'.$sql;
		}
	}

	//插入
	//@param $param可选值 
	//             string--表示字符串；
	//             number--表示数字 
	//             expression--表达式
	//@return 成功执行返回刚插入的ID；
	public function sqlinsert($table, $param){
		if(!is_array($param)){
			throw new Exception('sqlinsert参数错误', 905);
		}
		$sql = 'insert into '.$table.'(';
		
		$keys = array_keys($param);
		$keys_str = implode(',', $keys);
		$sql .= $keys_str.') values(';
		
		$params = array();
		foreach($param as $val){
			$params[] = $this->sql_check_input($val);
		}
		$params_str = implode(',', $params);
		$sql .= $params_str.')';

		try {
			$this->pdo->exec($sql);
			return $this->getLastId();
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage().'<br/>错误的SQL语句：'.$sql;
		}
	}

	//批量插入
	//@param $param可选值
	//             string--表示字符串；
	//             number--表示数字
	//             expression--表达式
	//@return 成功执行返回刚插入的ID；
	public function manysqlinsert($table, $param){
		if(!is_array($param)){
			throw new Exception('sqlinsert参数错误', 905);
		}
		$sql = 'insert into '.$table.'(';

		$keys = array_keys($param);
		$keys_str = implode(',', $keys);
		$sql .= $keys_str.') values';

		$params = array();
		foreach($param as $val){
			$params[] = $this->sql_check_input($val);
		}
		$temp = array_slice($params,0,-1);//将数组中除了userid之外的元素取出来
        $params_str = implode(',', $temp);//将数组转化为字符串
		$id = explode(',',$param['owncoupon_userid'][1]);//批量添加，传过来的是多个用户的id组成的字符串，这里是将用户id用数组存起来

        foreach($id as $item){
            $sql .= '('.$params_str.','.$item.'),';
        }

        $sql = rtrim($sql,',');//去掉sql语句最右边的逗号，去掉的内容是自定义的，组建出批量插入的sql语句，如：insert intotable_name value（, , ,） values (),(),();
		try {
			$this->pdo->exec($sql);
			return $this->getLastId();
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage().'<br/>错误的SQL语句：'.$sql;
		}
	}


	//删除
	//$where的参数形式形同上面的函数sqlinsert的$param
	//@return 成功执行返回delete操作影响的数量
	public function sqldelete($table, $where){
		if(!is_array($where)){
			throw new Exception('sqldelete参数错误', 906);
		}
		$sql = 'delete from '.$table.' where ';
		$wheres = array();
		foreach($where as $key => $val){
			$wheres[] = $key.'='.$this->sql_check_input($val);
		}
		$wheres_str = implode(' and ', $wheres);

		$sql .= $wheres_str;
		
		try {
			$num = $this->pdo->exec($sql);
			return $num;//返回删除记录数
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage().'<br/>错误的SQL语句：'.$sql;
		}
	}

	//修改
	//$where和$param的参数形式形同上面的函数sqlinsert的$param
	//@return 成功执行返回update操作影响的数量
	public function sqlupdate($table, $param, $where){
		if(!is_array($param)){
			throw new Exception('sqlupdate参数错误', 907);
		}
		$sql = 'update '.$table.' set ';

		$params = array();
		foreach($param as $key => $val){
			$params[] = $key.'='.$this->sql_check_input($val);
		}
		$params_str = implode(',', $params);
		$sql .= $params_str;

		$wheres = array();
		foreach($where as $key => $val){
			$wheres[] = $key.'='.$this->sql_check_input($val);
		}
		$wheres_str = implode(' and ', $wheres);

		$sql .= ' where '.$wheres_str;
		

		try {
			$num = $this->pdo->exec($sql);
			return $num;//返回删除记录数
		} catch(PDOException $e) {
			if($this->debug) echo $e->getMessage().'<br/>错误的SQL语句：'.$sql;
		}
	}

	//过滤数据，防止注入
	//$arr_val为数组，形如：array('string', 'abcdef')
	//第1个参数可接受的值为：number、string、expression
	public function sql_check_input($arr_val)
	{
		$value = $arr_val[1];
		
		if (get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		// 如果是数字进行验证
		$type = $arr_val[0];
		if ($type == 'number'){
			$isint   = preg_match('/^-?\d+$/', $value);
			$isfloat = preg_match('/^(-?\d+)(\.\d+)?/', $value);
			if(!$isint && !$isfloat){
				throw new MyException('参数值'.$value.'应该为数字', 904);
			}
		}
		// 如果是字符串加引号
		if ($type == 'string'){
			$value = "'" . $this->sql_escape_mimic($value) . "'";
		}
		return $value;
	}

	//得到最后一条插入ID
	public function getLastId() {
    	return $this->pdo->lastInsertId();
  	}

	/**
	 * sql_escape_mimic  用于在无mysql连接情况下替代mysql_real_escape_string的作用（防止SQL注入）
	 * 
	 * @param mixed $str
	 * @return
	 */
	public function sql_escape_mimic($str) {
		if($str === '0' || $str === 0) return $str;//2016/10/10修正等于0的情况
		if(!empty($str) && is_string($str)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $str);
		}
	}
}

?>