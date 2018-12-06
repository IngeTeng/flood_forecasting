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


require($LIB_PATH.'water_level_csv.class.php');
require($LIB_TABLE_PATH.'table_water_level_csv.class.php');

$FLAG_TOPNAV    = "data";

$FLAG_LEFTMENU  = 'statistics_list';

if(!empty($_GET['begin_time'])){
    $s_begin_time  = safeCheck($_GET['begin_time'],0);
}else{
    $s_begin_time  = '2010-01-09';
}

if(!empty($_GET['end_time'])){
    $s_end_time  = safeCheck($_GET['end_time'],0);
}else{
    $s_end_time  = '2011-01-09';
}

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
    <script type="text/javascript">
    $(function() {


        $('#searchstatistics_list').click(function(){

                s_begin_time           = $('input[name=begin_time]').val();
                s_end_time             = $('input[name=end_time]').val();
                if(s_begin_time>s_end_time){
                    layer.alert('开始时间不能大于结束时间');
                }
                
                location.href='statistics_list.php?begin_time='+s_begin_time+'&end_time='+s_end_time;
            });

    //日期组件
                laydate({
                    elem: '#begin_time', //需显示日期的元素选择器
                    event: 'click', //触发事件
                    format: 'YYYY-MM-DD', //日期格式
                    istime: true, //是否开启时间选择
                    isclear: true, //是否显示清空
                    istoday: true, //是否显示今天
                    issure: true, //是否显示确认
                    festival: true, //是否显示节日
                    //min: '1900-01-01 00:00:00', //最小日期
                    //max: '2099-12-31 23:59:59', //最大日期
                    //start: '2014-6-15 23:00:00',    //开始日期
                    //fixed: false, //是否固定在可视区域
                    //zIndex: 99999999, //css z-index
                    choose: function(dates){ //选择好日期的回调
                    }
                });

                //日期组件
                laydate({
                    elem: '#end_time', //需显示日期的元素选择器
                    event: 'click', //触发事件
                    format: 'YYYY-MM-DD', //日期格式
                    istime: true, //是否开启时间选择
                    isclear: true, //是否显示清空
                    istoday: true, //是否显示今天
                    issure: true, //是否显示确认
                    festival: true, //是否显示节日
                    //min: '1900-01-01 00:00:00', //最小日期
                    //max: '2099-12-31 23:59:59', //最大日期
                    //start: '2014-6-15 23:00:00',    //开始日期
                    //fixed: false, //是否固定在可视区域
                    //zIndex: 99999999, //css z-index
                    choose: function(dates){ //选择好日期的回调
                    }
                });
     });

    </script>
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
            <div id="handlelist">
                <?php
                //初始化
              /*  $totalcount= Table_Water_level_csv::search_statis($s_begin_time , $s_end_time);
                $shownum   = 10;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);//点击页码之后在这函数里面获取页码*/
                //柱状统计图数据
                $rows      = Table_Water_level_csv::search_statis(1,$s_begin_time , $s_end_time);
                //折线统计数据
                //var_dump($rows);
                $rows_1      = Table_Water_level_csv::search_statis(0,$s_begin_time , $s_end_time);


                ?>
                
                 <input name="begin_time" type="text" placeholder="请输入开始日期"  class="text-input input-length-10" id="begin_time" value="<?php echo $s_begin_time;?>" style="width:15%;height:25px;"/>

                  <input name="end_time" type="text" placeholder="请输入结束日期" class="text-input input-length-10" id="end_time" value="<?php echo $s_end_time;?>" style="width:15%;height:25px;"/>

                <input style="margin-left:10px" class="btn-handle" id="searchstatistics_list" value="查询" type="button">
               <!--  <span class="table_info"><input type="button" class="btn-handle" id="downloadproduct" value="下载商品信息"/></span> -->
              <!--  <span class="table_info"><input type="button" class="btn-handle" id="addwater_level_csv" value="添 加"/></span> -->
                <div>
                <br>
            <div class="tablelist" >
            <h1>月降水量折线统计图</h1>
            <br />

            <div id="hero-area" style="height: 250px;"></div>
            
            <div class="span12 chart">                        
           <!--  <div id="hero-graph" style="height: 230px;"></div>
                   </div> -->
             


            <h1>年平均降水量</h1>
            <div id="hero-bar" style="height: 250px;"></div>
            </div>
            
        </div>
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
            /*data: [
                {period: '2010-03', now: 2666,  pre: 2647},
                {period: '2010-04', now: 2778, pre: 2441},
                {period: '2010-05', now: 4912,  pre: 2501},
                {period: '2010-06', now: 3767, pre: 5689},
                {period: '2011 Q1', now: 6810,  pre: 2293},
                {period: '2011 Q2', now: 5670,  pre: 1881},
                {period: '2011 Q3', now: 4820,  pre: 1588},
                {period: '2011 Q4', now: 15073,  pre: 5175},
                {period: '2012 Q1', now: 10687,  pre: 2028},
                {period: '2012 Q2', now: 8432,  pre: 1791}
            ],*/
            data:<?php echo $rows_1;?>,
            xkey: 'period',
            ykeys: ['now', 'pre'],
            labels: ['实际', '预测'],
            lineWidth: 3,
            hideHover: 'auto',
            lineColors: ["#a6e182", "#67bdf8"]
          });
                ///"#81d5d9", 

        // Morris Line Chart
        var tax_data = [
            {"period": "1", "now": 2407, "pre": 660},
            {"period": "2", "now": 3351, "pre": 729},
            {"period": "3", "now": 2469, "pre": 1318},
            {"period": "4", "now": 2246, "pre": 461},
            {"period": "5", "now": 3171, "pre": 1676},
            {"period": "6", "now": 2155, "pre": 681},
            {"period": "7", "now": 1226, "pre": 620},
            {"period": "8", "now": 2245, "pre": 500},
            {"period": "9", "now": 2245, "pre": 500},
            {"period": "10", "now": 2245, "pre": 500},
            {"period": "11", "now": 2245, "pre": 500},
            {"period": "12", "now": 2245, "pre": 500}
        ];
       /* Morris.Line({
            element: 'hero-graph',
            data: tax_data,
            xkey: 'period',
            xLabels: "month",
            ykeys: ['now', 'pre'],
            labels: ['实际数据', '预测数据']
        });*/


        // Morris Bar Chart 柱状统计图
        Morris.Bar({
            element: 'hero-bar',
            /*data: [
                {device: 'Jan', sells: 136},
                {device: 'Feb', sells: 1037},
                {device: 'Mar', sells: 275},
                {device: 'Apr', sells: 380},
                {device: 'May', sells: 655},
                {device: 'Jun', sells: 1571},
                {device: 'Jul', sells: 1571},
                {device: 'Aug', sells: 1571},
                {device: 'Sep', sells: 1571},
                {device: 'Oct', sells: 1571},
                {device: 'Nov', sells: 1571},
                {device: 'Dec', sells: 1571}
            ],*/
            //console.log('<?php echo $rows;?>');
            data:<?php echo $rows;?>,
            xkey: 'device',
            ykeys: ['sells'],
            labels: ['Sells'],
            barRatio: 0.2,
            xLabelMargin: 10,
            hideHover: 'auto',
            barColors: ["#3d88ba"]
        });

        
    </script>
</body>
</html>
