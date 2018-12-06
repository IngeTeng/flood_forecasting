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

$FLAG_LEFTMENU  = 'water_level_csv_list';



if(!empty($_GET['begin_time'])){
    $s_begin_time  = safeCheck($_GET['begin_time'],0);
}else{
    $s_begin_time  = '';
}

if(!empty($_GET['end_time'])){
    $s_end_time  = safeCheck($_GET['end_time'],0);
}else{
    $s_end_time  = '';
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Neptune工作室" />
    <title>水位列表 - 数据设置 - 管理系统 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.Framer.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.Framer.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="laydate/laydate.js"></script>
    <script src="js/Vague.js"></script>
  
    <script type="text/javascript">
        $(function() {

            //查询
            $('#searchwater_level_csv').click(function(){

                s_begin_time           = $('input[name=begin_time]').val();
                s_end_time             = $('input[name=end_time]').val();
                if(s_begin_time>s_end_time){
                    layer.alert('开始时间不能大于结束时间');
                }
                
                location.href='water_level_csv_list.php?begin_time='+s_begin_time+'&end_time='+s_end_time;
            });

            $('#btn_download').click(function(){
                    location.href = 'data_download.php';
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

            //添加
            $('#addwater_level_csv').click(function(){

                location.href='water_level_csv_add.php';
            });

            //删除评论
            $(".delete").click(function () {
                var thisid = $(this).parent('td').find('#water_level_csvid').val();
                layer.confirm('确认删除数据信息吗？', {
                        btn: ['确认', '取消']
                    }, function () {
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type: 'POST',
                            data: {
                                id: thisid
                            },
                            dataType: 'json',
                            url: 'water_level_csv_do.php?act=del',
                            success: function (data) {
                                layer.close(index);

                                code = data.code;
                                msg = data.msg;
                                switch (code) {
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function (index) {
                                            location.reload();
                                        });
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    }, function () {
                    }
                );
            });
            /* $(".see").mouseover(function(){
                    layer.tips('查看详情', $(this), {
                        tips: [4, '#3595CC'],
                        time: 500
                    });
                });*/
    
            $(".editinfo").mouseover(function(){
                layer.tips('修改', $(this), {
                    tips: [4, '#3595CC'],
                    time: 500
                });
            });

            $(".delete").mouseover(function(){
                layer.tips('删除', $(this), {
                    tips: [4, '#3595CC'],
                    time: 500
                });
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
                $totalcount= Water_level_csv::search(0, 0,  $s_begin_time , $s_end_time,  1);
                $shownum   = 10;
                $pagecount = ceil($totalcount / $shownum);
                $page      = getPage($pagecount);//点击页码之后在这函数里面获取页码
                $rows      = Water_level_csv::search($page, $shownum,$s_begin_time , $s_end_time );
                ?>
                
                 <input name="begin_time" type="text" placeholder="请输入开始日期"  class="text-input input-length-10" id="begin_time" value="<?php echo $s_begin_time;?>" style="width:15%;height:25px;"/>

                  <input name="end_time" type="text" placeholder="请输入结束日期" class="text-input input-length-10" id="end_time" value="<?php echo $s_end_time;?>" style="width:15%;height:25px;"/>

                <input style="margin-left:10px" class="btn-handle" id="searchwater_level_csv" value="查询" type="button">

                <span class="table_info"><input type="button" class="btn-handle" id="btn_download" value="下载数据信息信息"/></span>
                    <div>

               <!--  <span class="table_info"><input type="button" class="btn-handle" id="downloadproduct" value="下载商品信息"/></span> -->
              <!--  <span class="table_info"><input type="button" class="btn-handle" id="addwater_level_csv" value="添 加"/></span> -->
                <div>
                </div>
            </div>
            <br>
            <div class="tablelist" >
            <table>
                <tr>
                    <th>wid</th>  
                    <th>state</th>
                    <th>Water_date</th>
                    <th>Water_level</th>
                    
                </tr>
                <?php

                $i=1;
                //  var_dump($rows);
                if(!empty($rows)){//如果列表不为空
                    foreach($rows as $row){
                       
                        $Water_date     = date('Y-m-d H:m', $row['Water_date']);
        
                        echo '<tr>          
                        
                                            <td class="center">'.$row['id'].'</td>
                                            <td class="center">'.$row['state'].'</td>
                                            <td class="center">'.$Water_date.'</td> 
                                            <td class="center">'.$row['Water_level'].'</td>
                                                        
                                            
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
        </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
  <script src="js/sample.js"></script>
</body>
</html>
