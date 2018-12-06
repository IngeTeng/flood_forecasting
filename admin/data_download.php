<?php
/**
 * @Author: anchen
 * @Date:   2016-12-07 14:31:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-05-10 21:48:11
 */
require_once 'Classes/PHPExcel.php';          //路径根据自己实际项目的路径进行设置
require_once('admin_init.php');
require_once('admincheck.php');
    //加载所需的类

$title="数据列表";
/*$table = "adminlog";
$pretable = $DB_prefix.$table;*/
$msg = '成功下载('.$title.')';
Adminlog::add($msg);
$objPHPExcel = new PHPExcel();  //创建PHPExcel实例
   

// 单元格密码保护不让修改
$objPHPExcel->getActiveSheet()->getProtection()->setSheet( true);  // 为了使任何表保护，需设置为真
$objPHPExcel->getActiveSheet()->protectCells( 'A1:E1001', 'PHPExcel' ); // 将A3到E13保护  加密密码是 PHPExcel



/*--------------设置表头信息------------------*/

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID编号')
            ->setCellValue('B1', '水位状态')
            ->setCellValue('C1', '水位数据')
            ->setCellValue('D1', '水位数据')
            ->setCellValue('E1', '预测水位');
/*--------------开始从数据库提取信息插入Excel表中------------------*/
$i=2;                //定义一个i变量，目的是在循环输出数据是控制行数
 $rs=table_water_level_csv::getList();
   foreach($rs as $r){
  //var_dump($rs);
 // exit;
  //$rm = iconv("GB2312","UTF-8",$rs[1]);                 //对字符进行编码将数据库里GB2312的中文字符转换成UTF-8格式
 // var_dump($rs);
      $date = date('Y-m-d H:i:s',$r['Water_date']);
      //$admin = Admin::getInfoById($r['adminid']);
      $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$i, $r['id'])
            ->setCellValue("B".$i, $r['state'])
            ->setCellValue("C".$i, $date)
            ->setCellValue("D".$i, $r['Water_level'])
            ->setCellValue("E".$i, $r['predict']);          
            $i++;
 }
$time = time();
$date = date('Y-m-d',$time);
/*--------------下面是设置其他信息------------------*/
$objPHPExcel->getActiveSheet()->setTitle($title);      //设置sheet的名称
 $objPHPExcel->setActiveSheetIndex(0);                            //设置sheet的起始位置
 //设置加密信息


 $fn="$title-$date.xls"; 
 header('Content-Type: application/vnd.ms-excel; charset=utf-8');  
 header("Content-Disposition: attachment;filename=$fn");  
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
 //$objWriter->save(str_replace('.php', '.xls', __FILE__));     //设置以什么格式保存，及保存位置
 $objWriter->save('php://output');  
exit;  