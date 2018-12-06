<?php
/**
 * @Author: anchen
 * @Date:   2016-12-07 14:31:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-12-21 22:08:14
 */
require_once 'Classes/PHPExcel.php';          //路径根据自己实际项目的路径进行设置
require_once('admin_init.php');
require_once('admincheck.php');
    //加载所需的类

$title="管理员操作日志";
/*$table = "adminlog";
$pretable = $DB_prefix.$table;*/
$msg = '成功下载('.$title.')';
Adminlog::add($msg);
$objPHPExcel = new PHPExcel();  //创建PHPExcel实例
   //下面是对MySQL数据库的连接   
   
/*$conn = mysqli_connect($DB_host,$DB_user,$DB_pass) or die("数据库连接失败！");   
mysqli_select_db($conn,$DB_name);               //连接数据库
mysqli_query($conn,"set names utf8");                 //转换字符编码
$sql = mysqli_query($conn,"select * from $pretable ");    //查询sql语句*/



/*--------------设置表头信息------------------*/

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID编号')
            ->setCellValue('B1', '管理员账号')
            ->setCellValue('C1', '管理员操作时间')
            ->setCellValue('D1', '管理员操作')
            ->setCellValue('E1', '管理员IP');
/*--------------开始从数据库提取信息插入Excel表中------------------*/
$i=2;                //定义一个i变量，目的是在循环输出数据是控制行数
 $rs=Adminlog::logAllList();
   foreach($rs as $r){
  //var_dump($rs);
 // exit;
  //$rm = iconv("GB2312","UTF-8",$rs[1]);                 //对字符进行编码将数据库里GB2312的中文字符转换成UTF-8格式
 // var_dump($rs);
      $rst = date('Y-m-d H:i:s',$r['logtime']);
      $admin = Admin::getInfoById($r['adminid']);
      $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$i, $r['logid'])
            ->setCellValue("B".$i, $admin['account'])
            ->setCellValue("C".$i, $rst)
            ->setCellValue("D".$i, $r['logcontent'])
            ->setCellValue("E".$i, $r['ip']);          
            $i++;
 }
$time = time();
$date = date('Y-m-d',$time);
/*--------------下面是设置其他信息------------------*/
$objPHPExcel->getActiveSheet()->setTitle($title);      //设置sheet的名称
 $objPHPExcel->setActiveSheetIndex(0);                            //设置sheet的起始位置
 $fn="$title-$date.xls"; 
 header('Content-Type: application/vnd.ms-excel; charset=utf-8');  
 header("Content-Disposition: attachment;filename=$fn");  
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
 //$objWriter->save(str_replace('.php', '.xls', __FILE__));     //设置以什么格式保存，及保存位置
 $objWriter->save('php://output');  
exit;  