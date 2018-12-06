<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <style type="text/css">
            #mydiv{
                position:absolute;
                left:50%;
                top:50%;
                margin-left:-200px;
                margin-top:-50px;
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
                //var url="search.php?keyword="+content.value;
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
                        //result = unescape(result);
                       
                        //解析获得的数据
                        var json = eval("("+result+")"); 
                        console.log(json);
                        //var json = eval(result);
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
                setLocation();
                console.log(contents);
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
    </head>
    <body>
    <div id="mydiv">
       <!--  输入框 -->
       <input type = "text" size="50" id="keyword" onkeyup="getMoreContents()" onblur="keywordBlur()" onfocus="getMoreContents()" />
       <input type = "button" value ="百度一下" width="50px">

       <!-- 下面是内容个展示的区域 -->

       <div id="popDiv">
            <table id="content_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0">
            <tbody id="content_table_body">
            <!-- 动态查询出来的数据显示在这个地方 -->

                <!-- <tr><td>ajax1</td></tr>
                <tr><td>ajax1</td></tr>
                <tr><td>ajax1</td></tr>
                <tr><td>ajax1</td></tr> -->
            </tbody>
            </table>
        </div>
    </div>
    </body>
</html>