function isNum(s) {
    //var regu = "^([0-9]*)$";
    var regu = "^([0-9]*[.0-9])$"; // 小数测试
    var re = new RegExp(regu);
    if (s.search(re) != -1)
        return true;
    else
        return false;
}

//读取cookie
function getCookie(name) 
{ 
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	
		if(arr=document.cookie.match(reg))
	 
			return unescape(arr[2]); 
		else 
			return null; 
}