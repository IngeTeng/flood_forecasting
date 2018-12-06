<?php
require_once('conn.php');
if(!empty($_GET['title'])){
    $s_title  =$_GET['title'];
    $s_tag =1;
}else{
    $s_title  = '';
    $s_tag =2;
}
//$s_title  = '新生报到';
$rows = table_link::search_Bytitle($s_title);
if(!empty($rows)){
    foreach ($rows as $row) {
        # code...
        $count = $count+1;
    }
}
//var_dump($count);
//var_dump($rows);
//exit;
//var_dump($rows[0]['node_jing']);
//var_dump($rows[0]['node_wei']);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>浏览器定位</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=7f8a305ca28c52b6e412e8fd365e438e&plugin=AMap.Walking"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<body>
 <style type="text/css">
        .info-title {
            color: white;
            font-size: 14px;
            background-color:blue;
            line-height: 26px;
            padding: 0px 0 0 6px;
            font-weight: lighter;
            letter-spacing: 1px
        }
        .info-content {
            font: 12px Helvetica, 'Hiragino Sans GB', 'Microsoft Yahei', '微软雅黑', Arial;
            padding: 4px;
            color: #666666;
            line-height: 23px;
        }
        .info-content img {
            float: left;
            margin: 3px;
        }
        .mouseOver{
                background:#708090;
                color:#FFFAFA;
            }
        .mouseOut{
                background:#FFFAFA;
                color:#000000;
            }
    </style>
<script type="text/javascript">
$(function() {
            //查询
            $('#searchkey').click(function(){
                s_title        = $('#keyword').val();
                
                location.href='qh.php?title='+s_title;
               // getRoute([<?php echo $rows[0]['jing'];?>],[<?php echo $rows[0]['wei'];?>]);
            });


});

            /*获得用户输入内容的关联信息的函数*/
            function getMoreContents(){
                //获得用户的输入内容
               
                var content = document.getElementById("keyword");
                if(content.value ==""){
                    clearContent();
                    return;
                }
                //alert(content.value);
                //给服务器发送用户输入的内容，因为我们采用的是ajax异步发送数据
                //所以我们要使用一个对象，叫做XmlHttp对象
                xmlHttp = createXMLHttp();
                //alert(xmlHttp);
                //给服务器发送数据
                var url="search.php?keyword="+escape(content.value);
                //true 表示javascript脚本会在send()方法之后继续执行，而不会等待来自服务器的响应
                xmlHttp.open("GET",url,true);
                //xmlHttp绑定毁掉方法，这个毁掉方法会在xmlHttp状态改变的时候被调用
                //xmlHttp的状态0-4，我们只关心4（complete）这个状态，所以说
                //当完成之后，在调用回调方法才有意义
                xmlHttp.onreadystatechange = callback;
                xmlHttp.send(null);

            }
            //获得XmlHTTP对象
            function createXMLHttp(){
                //对于大多数浏览器都适用
                var xmlHttp;
                if(window.XMLHttpRequest){
                    xmlHttp = new XMLHttpRequest();
                }
                //要考虑浏览器的兼容性
                if(window.ActiveXObject){
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                    if(!xmlHttp){
                        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                }

                return xmlHttp;
            }


            function callback(){
                if(xmlHttp.readyState==4){
                    if(xmlHttp.status==200){
                        var result = xmlHttp.responseText;
                        
                        //解析获得的数据
                        var json = eval("("+result+")");
                        //alert(json);
                        //获得数据之后，就可以动态的显示这些数据了，把这些数据展示到输入框下面
                        setContent(json);
                    }
                }
            }
            //设置关联数据的展示,参数代表的是服务器传递过来的关联数据
            function setContent(contents){

                clearContent();
                //获得关联数据的长度，以此来确定生成多少<tr></tr>tr>
                //setLocation();
                var size = contents.length;
                //设置内容
                for(var i=0 ;i<size;i++){
                    var nextNode = contents[i];//代表的是json格式数据的第i个元素
                    var tr = document.createElement("tr");
                    var td = document.createElement("td");
                    td.setAttribute("border","0");
                    td.setAttribute("bgcolor", "#FFFAFA");
                    td.onmouseover = function(){
                        this.className = 'mouseOver';
                    };
                    td.onmouseout = function(){
                        this.className = 'mouseOut';
                    };
                    td.onmousedown=function(){
                       //当鼠标点击一个关联数据的时候,被选中的数据 自动填充到输入框里面
                       document.getElementById("keyword").value=this.innerHTML;
                       //清除div边框
                       document.getElementById("popDiv").style.border="none";
        
                    }

                    var text = document.createTextNode(nextNode);
                    td.append(text);
                    tr.appendChild(td);
                    document.getElementById("content_table_body").append(tr);

                }


            }
            //清空之前的数据
            function clearContent(){
                var contentTableBody = document.getElementById("content_table_body");
                var size = contentTableBody.childNodes.length;
                for(var i =size-1; i>=0;i--){
                    contentTableBody.removeChild(contentTableBody.childNodes[i]);
                }
                document.getElementById("popDiv").style.border = "none";
            }

            function keywordBlur(){
                clearContent();
            }
            //设置显示关联信息的位置
            function setLocation(){
              var content = document.getElementById("keyword");
                var width = content.offsetWidth;
                var left = content["offsetLeft"];
                var top = content["offsetTop"]+content.offsetHeight;

                var popDiv = document.getElementById("popDiv");
                popDiv.style.border = "black 1px solid";
                popDiv.style.left = left+"px";
                popDiv.style.top = top+"px";
                popDiv.style.width = width+"px";
                document.getElementById("content_table").style.width=width+"px";
            }
</script>
<div id="refresh">
<div id="container"></div>

</div>
<div class="button-group">
 <!--    <input type="button" class="button" value="路线规划" onclick="getRoute([<?php echo $rows[1]['node_jing'];?>,<?php echo $rows[1]['node_wei'];?>])"/> -->
    <input type="button" class="button" value="退出导航" onclick="out()"/> 
    <!-- <input type="button" class="button" value="下一个事项" onclick="getRoute1()"/>-->
    <input type="button" class="button" value="校园周边" onclick="infoClick()"/> 
</div>
<div id="tip">
    <div id="mydiv">
    <input type="text" id="keyword" name="keyword" onkeyup="getMoreContents()" onblur="keywordBlur()" onfocus="getMoreContents()" placeholder="请输入关键字" value="<?php echo $s_title?>"/>
     
    <input type="button" class="button" value="搜索" id="searchkey" />
   <div id="popDiv">
            <table id="content_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0">
            <tbody id="content_table_body">
            <!-- 动态查询出来的数据显示在这个地方 -->

                
            </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
/***************************************
由于Chrome、IOS10等已不再支持非安全域的浏览器定位请求，为保证定位成功率和精度，请尽快升级您的站点到HTTPS。
***************************************/
    var map, geolocation;
    var mapObj;
    var marker;  
    var marker2;  
    var marker3;  
    var cluster;  
    var markers = []; 
    var lineArr;
    var cloudDataLayer;
    var arrays=[]; //保存获取的定位坐标
    var  infowindow;
    
    


    //初始化函数
    ////加载地图，调用浏览器定位服务
    function init(){
    map = new AMap.Map('container', {
        resizeEnable: true
    });
    map.plugin('AMap.Geolocation', function() {
        geolocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            timeout: 20000,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            buttonPosition:'RB'
        });

        map.addControl(geolocation);
        geolocation.getCurrentPosition();
        console.log(geolocation);
        //map.setZoomAndCenter(18, geolocation);
        AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
    });

        //解析定位结果
       function onComplete(data) {
            var str=['定位成功'];
            var jing = data.position.getLng();
            var wei = data.position.getLat();
            arrays = [data.position.getLng(),data.position.getLat()];
            str.push('经度：' + data.position.getLng());
            str.push('纬度：' + data.position.getLat());
            //标记地点
            var marker = new AMap.Marker({
            position: [data.position.getLng(),data.position.getLat()]
            });
            marker.setMap(map);

            if(data.accuracy){
                 str.push('精度：' + data.accuracy + ' 米');
            }//如为IP精确定位结果则没有精度信息
           str.push('是否经过偏移：' + (data.isConverted ? '是' : '否'));
            //document.getElementById('tip').innerHTML = str.join('<br>');
            if(<?php echo $s_tag;?>==1){
                //AJAX

                $.ajax({
                            type: 'POST',
                            data: {
                                title        : '<?php echo $s_title;?>'
                            },
                            dataType: 'json',
                            url: 'action_do.php?act=select',
                            success: function (data) {
                                code = data.code;
                                msg = data.msg;
                                res = data.res;
                                switch (code) {
                                    case 1: 
                                            //分隔函数
                                           // var arr=res[0]['node_jing'].split('.');
                                           //  getRoute([res[i]['node_jing'],res[i]['node_jing']]);
                                        var i =0;
                                        //alert(res[i]['node_jing'],res[i]['node_jing']);
                                        //alert(msg);
                                        while(i< msg){

                                            Dx = calculate(jing ,res[i]['node_jing'], wei,res[i]['node_wei'] ,i);
                                            if(Dx<1300){
                                                i = i+1;
                                                //清屏函数
                                                clearMap();
                                            }
                                        }

                                        break;
                                    default:
                                       alert('failed');
                                }
                            }
                        });
                
            }
            
        }

        //算法计算
        function calculate(jing ,jing_target, wei, wei_target,i){
          getRoute([jing_target,wei_target]);
            //当前的经纬后缀
          jing_ex = jing.split('.');
          wei_ex = wei.split('.');
            //目的地的经纬度后缀
          var jing_target = jing_target.split('.');
          var wei_target = wei_target.split('.');
          //当前和目的地的后缀方差
          var Dx = (jing_target[1]-jing_ex)*(jing_target[1]-jing_ex)+(wei_target[1]-wei_ex)*(wei_target[1]-wei_ex);
          Dx = Dx/2;
          return Dx;     
        }
        //解析定位错误信息
        function onError(data) {
            document.getElementById('tip').innerHTML = '定位失败';
        }

    }  
        //无线循环
        function wuxian(tag ){
            init();

            var delaytime = 5000;
            if(tag ==1){
                //定时器函数
                timename=setInterval("init();",delaytime);
            }
        
        }
        //------------------------------函数开始的地方----------------------------------------   
        wuxian(<?php echo $s_tag;?>);
        //获取路线规划
        function getRoute(arrs){
        var walking = new AMap.Walking({
            map: map
        });
        walking.search(arrays, arrs);
      
        }
        //信息弹窗
        function infoClick(){
            //信息弹窗
            location.href='qh.html';
            
        }
        //规划线路2
         function getRoute1(){
            clearMap();
            var walking1 = new AMap.Walking({
            map: map
         });
        walking1.search([108.943531,34.226388], [108.937351,34.219007]);
        }
        //清除地图
        function clearMap(){    
        map.clearMap();
        //init();
        }

        function out(){
            location.href='qh.php';
        }


        //刷新地图操作
        function refresh(jing , wei){
            document.getElementById("refresh").innerHTML='';
             document.getElementById("refresh").innerHTML='<div id="container"></div>';
            init();
            getRoute([jing,wei]);
        }


       
</script>
</body>
</html>