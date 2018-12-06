<?php

/**
 * table_predict.class.php 表
 *
 * @version       $Id$ v0.01
 * @createtime    2016/11/14
 * @updatetime
 * @author        IngeTeng
 * @copyright     Neptune工作室
 */

class Table_predict extends Table{

    /**
     * Table_predict::struct()  数组转换
     *
     * @param array $data
     *
     * @return $r
     */
    static protected function struct($data){
        $r = array();

        $r['id']                = $data['predict_id'];
        $r['date']              = $data['predict_date'];
        $r['level']             = $data['predict_level'];
        return $r;
    }

   
    /**
     * Table_predict::search()搜索
     *
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function search_pre($count = 0){
        global $mypdo, $mylog;

       
        $sql = "select * from ".$mypdo->prefix."predict where 1=1 ";

        //var_dump($sql);
        if($count){

            $r = $mypdo->sqlQuery($sql);
            return count($r);

        }else{

            $rs = $mypdo->sqlQuery($sql);
            if($rs){
                $r = array();
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                $i = 0;
                foreach ($r as $res ) {
                    # code...
                    $arr[$i]['period'] = date('Y-m-d' , $res['date']);
                    $arr[$i]['pre'] = $res['level'];
                    $i++;
                }
                $arr = json_encode($arr);
                return $arr;
            }else{
                return 0;
            }

        }


    }

     /**
     * Table_predict::search()搜索
     *
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function search($page = 1, $pagesize = 10, $count = 0){
        global $mypdo, $mylog;

        $page     = $mypdo->sql_check_input(array('number', $page));
        $pagesize = $mypdo->sql_check_input(array('number', $pagesize));


        $startrow = ($page - 1) * $pagesize;
        $sql = "select * from ".$mypdo->prefix."predict where 1=1 ";
        //var_dump($sql);
        if($count){

            $r = $mypdo->sqlQuery($sql);
            return count($r);

        }else{
            $sql .= " order by predict_id desc limit $startrow, $pagesize";

            $rs = $mypdo->sqlQuery($sql);
            if($rs){
                $r = array();
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                return $r;
            }else{
                return 0;
            }

        }


    }



}
?>