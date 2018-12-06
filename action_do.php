<?php
/**
 * @Author: anchen
 * @Date:   2017-03-16 20:58:03
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-03-24 21:37:04
 */
require('conn.php');
require($LIB_PATH.'link.class.php');
require($LIB_TABLE_PATH.'table_link.class.php');

$act = $_GET['act'];

switch($act){
 

    case 'select'://编辑

        $title           = safeCheck($_POST['title'],0);

        $res = table_link::search_Bytitle($title);

        //var_dump($cart);
        if(!empty($res)){
        foreach ($res as $re) {
        # code...
            $count = $count+1;
        }
        }
            $msg = $count;
            echo action_msg_res($msg, 1,$res);

        break;

       

    
}