<?php

/**
 * function.inc.php  与业务有关的函数
 *
 * @version       v0.01
 * @create time   2014/9/1
 * @update time   
 * @author        IngeTeng
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

/**
 * 获得执行程序的时间(秒)
 * 
 * @param $starttime 
 * @param $endtime
 *
 * @return
 */
function getRunTime($starttime = 0, $endtime = 0){
	global $PageStartTime;
	if(empty($starttime)){
		$starttime = $PageStartTime;
	}
	if(empty($endtime)){
		$PageEndTime = explode(' ',microtime());
		$PageEndTime = $PageEndTime[1] + $PageEndTime[0];
		$endtime = $PageEndTime;
	}
	
	$runtime = number_format(($endtime - $starttime), 3);
	return $runtime;
}

/**
 * 分页参数page传递后的处理
 * 
 * @param mixed $pagecount 页数
 * @return
 */
function getPage($pagecount){

	$page = empty($_GET['page']) ? 1 : trim($_GET['page']);
	if(!is_numeric($page)) $page = 1;
	if($page < 1) $page = 1;
    if(empty($pagecount)) 
        $page = 1;
	elseif($page > $pagecount) 
        $page = $pagecount;

	return $page;
}
/**
 * 分页显示 dspPages()--具体样式再通过CSS控制
 * 形如：
 * 1 2 3 × × × 98 99 100
 * 1 × × × 7 8 9 × × × 100
 *
 * @param $url       链接URL
 * @param $page      当前页数
 * @param $pagesize  页数
 * @param $rscount   记录总数
 * @param $pagecount 总页数
 * @return
 */
function dspPages($url, $page, $pagesize, $rscount, $pagecount){
		
		//参数安全处理
		$url  = str_replace(array(">", "<"), array("&gt;", "&lt;"), $url);
		if(!is_numeric($page))       $page = 0;
		if(!is_numeric($pagesize))   $pagesize = 0;
		if(!is_numeric($rscount))    $rscount = 0;
		if(!is_numeric($pagecount))  $pagecount = 0;
		
		//处理Page参数
		$p1 = strpos($url, '?page=');
        if($p1) $url = substr($url, 0, $p1);
        
        $p2 = strpos($url, '&page=');
        if($p2) $url = substr($url, 0, $p2);
		
		//构建显示
		$temppage="";
		$temppage.="<div class=\"pagenum\">";

		if($page>1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page-1)."\">上一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">上一页</div>";
		}
		
		If($pagecount<9){
			for($p=1;$p<=$pagecount;$p++){
				if($p!=$page)
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				else
					$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
			}
		}else{
			if($page<=3){
				for($p=1;$p<=5;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$pagecount-3;$p<=$pagecount;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
			}else if($pagecount-$page<=3){
				for($p=1;$p<=3;$p++){
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.="<div class=\"pager\">...</div>";
				for($p=$pagecount-4;$p<=$pagecount;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}
				}
			}
			else{
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=1\">1</a></div>";
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$page-2;$p<=$page+2;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\">".$p."</div>";
					}
				}
				$temppage.="<div class=\"pager\">...</div>";
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$pagecount."\">".$pagecount."</a></div>";
			}
		}

		if($page<=$pagecount-1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page+1)."\">下一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">下一页</div>";
		}
		
		$temppage .="</div>";		


		if(!strpos($url, "?") === false)
			$temppage=str_replace("?page=", "&page=", $temppage);

		return $temppage;
}

?>