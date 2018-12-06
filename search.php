<?php
/**
 * @Author: anchen
 * @Date:   2017-03-26 22:16:25
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-03-27 14:55:06
 */
require('conn.php');
require($LIB_PATH.'link.class.php');
require($LIB_TABLE_PATH.'table_link.class.php');
$keyword = $_GET['keyword'];
$keyword=unescape($keyword);
//var_dump($keyword);
//获得关键字后进行处理
//echo stristr("Helloworld!","!");
$rows=table_link::getTitle();

if(!empty($rows)){
       $m=0;
    for( $i=0 ; $i<sizeof($rows);$i++){    
            $arr[$m] = $rows[$i]['link_title'];
             ++$m;
        
        
    }
}
$rowss= array_unique($arr);
$row=array_values($rowss);
//var_dump($row);
//exit;


/*$row[0]='admin';
$row[1]='billy';
$row[2]='caa';
$row[3]='jams';*/
if(!empty($keyword)){
    $k=0;
    for( $i=0 ; $i<sizeof($row);$i++){

        if(stristr($row[$i],$keyword)){    
            
            $attr[$k] =unescape($row[$i]);
             ++$k;
        }
        
    }
}

/*$a = unescape($attr[0]);*/
//var_dump($attr);

$attr=json_encode($attr);
echo $attr;

//编码转换
function unescape($str){   
    $ret = '';   
    $len = strlen($str);   
    for ($i = 0; $i < $len; $i++){   
    if ($str[$i] == '%' && $str[$i+1] == 'u'){   
    $val = hexdec(substr($str, $i+2, 4));   
    if ($val < 0x7f) $ret .= chr($val);   
    else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));   
    else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));   
    $i += 5;   
    }   
    else if ($str[$i] == '%'){   
    $ret .= urldecode(substr($str, $i, 3));   
    $i += 2;   
    }   
    else $ret .= $str[$i];   
    }   
    return $ret;   
} 
function phpescape($str){ 
    preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/",$str,$newstr); 
    $ar = $newstr[0]; 
    foreach($ar as $k=>$v){ 
        if(ord($ar[$k])>=127){ 
            $tmpString=bin2hex(iconv("GBK","ucs-2",$v)); 
            if (!eregi("WIN",PHP_OS)){ 
                $tmpString = substr($tmpString,2,2).substr($tmpString,0,2); 
            } 
            $reString.="%u".$tmpString; 
        } else { 
            $reString.= rawurlencode($v); 
        } 
    } 
    return $reString; 
}  