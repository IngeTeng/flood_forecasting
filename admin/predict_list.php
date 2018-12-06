<?php
/**
 * 节点表  water_level_csv_list.php
 *
 * @version       v0.01
 * @create time   2016-11-14
 * @update time   2016-11-15
 * @author        IngeTeng
 * @copyright     Neptune工作室
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID        = '7001';
Admin::checkAuth($POWERID, $ADMINAUTH);


require($LIB_TABLE_PATH.'table_predict.class.php');

$FLAG_TOPNAV    = "data";

$FLAG_LEFTMENU  = 'predict_list';


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Neptune工作室" />
    <title>统计管理 - 数据设置 - 管理系统 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.Framer.css" type="text/css" />
    <link href="css/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/morris.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.Framer.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="laydate/laydate.js"></script>
    <script src="js/Vague.js"></script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('data_menu.inc.php');?>
        <div id="maincontent">
            <div id="position">当前位置：<a href="water_level_csv_list.php">数据管理</a> > 内容设置</div>
            <?php
                //初始化
                //预测6天的数据
                $pre      = Table_predict::search_pre();
                //var_dump($rows);
                ?>
            <div id="handlelist">
                <?php
                //初始化
                $totalcount= Table_predict::search(0, 0,  1);
                $shownum   = 6;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);//点击页码之后在这函数里面获取页码
                $rows      = Table_predict::search($page, $shownum);
                ?>

                </div>
                <div class="tablelist" >
            <table>
                <tr>
                    <th>ID</th>  
                    
                    <th>预测日期</th>
                    <th>预测水位</th>

                </tr>
                <?php

                $i=1;
                //  var_dump($rows);
                if(!empty($rows)){//如果列表不为空
                    foreach($rows as $row){
                       
                        $Water_date     = date('Y-m-d H:m', $row['date']);
        
                        echo '<tr>          
                        
                                            <td class="center">'.$row['id'].'</td>
                                            
                                            <td class="center">'.$Water_date.'</td> 
                                            <td class="center">'.$row['level'].'</td>
                                                        
                                           
                                        </tr>
                                    ';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="8">没有数据</td></tr>';
                }
                ?>


            </table>
        </div>

        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $shownum, $totalcount, $pagecount);
            }
            ?>
        </div>
                <div>
                </div>
            </div>
                <br>



            <br>
            <div class="tablelist" >
        
            <br />
            </div>

            <div id="hero-area" style="height: 250px;"></div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
  <script src="js/sample.js"></script>
   <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="js/morris.min.js"></script>
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.stack.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script type="text/javascript">
       

        // Morris Area Chart
        Morris.Area({
            element: 'hero-area',
            // data: [
            //     {period: '2010-03',   pre: 2647},
            //     {period: '2010-04',  pre: 2441},
            //     {period: '2010-05',   pre: 2501},
            //     {period: '2010-06',  pre: 5689},
            //     {period: '2011 Q1',  pre: 2293},
            //     {period: '2011 Q2',  pre: 1881},
            //     {period: '2011 Q3',  pre: 1588},
            //     {period: '2011 Q4',   pre: 5175},
            //     {period: '2012 Q1',   pre: 2028},
            //     {period: '2012 Q2',   pre: 1791}
            // ],
            data:<?php echo $pre;?>,
            xkey: 'period',
            ykeys: ['pre'],
            labels: [ '预测'],
            lineWidth: 3,
            hideHover: 'auto',
            lineColors: [ "#67bdf8"]
          });

        
        
    </script>
</body>
</html>
