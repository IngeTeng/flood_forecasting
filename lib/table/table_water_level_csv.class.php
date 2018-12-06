<?php

/**
 * table_water_level_csv.class.php 表
 *
 * @version       $Id$ v0.01
 * @createtime    2016/11/14
 * @updatetime
 * @author        IngeTeng
 * @copyright     Neptune工作室
 */

class Table_water_level_csv extends Table{

    /**
     * Table_water_level_csv::struct()  数组转换
     *
     * @param array $data
     *
     * @return $r
     */
    static protected function struct($data){
        $r = array();

        $r['id']                = $data['water_level_csv_id'];
        $r['state']             = $data['water_level_csv_state'];
        $r['Water_date']        = $data['water_level_csv_Water_date'];
        $r['Water_level']       = $data['water_level_csv_Water_level'];
        $r['predict']           = $data['water_level_csv_predict'];
        return $r;
    }

    /**
     * Table_water_level_csv::getInfoById()
     *
     * @param mixed $id
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."water_level_csv where water_level_csv_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }



//     /**
//      * Table_water_level_csv::add() 添加
//      *
//      * @param array $Attr
//      * 
//      *
//      * @return
//      */
//     static public function add($Attr){
//         global $mypdo;

//         $title              = $Attr['title'];
//         $jing               = $Attr['jing'];
//         $wei                = $Attr['wei'];
//         $createtime         = time();

//         $param = array (
//             'water_level_csv_title'             => array('string',$title),
//             'water_level_csv_jing'              => array('string', $jing),
//             'water_level_csv_wei'               => array('string', $wei),
//             'water_level_csv_createtime'        => array('number',$createtime)

//         );
// //        var_dump($param);

//         return $mypdo->sqlinsert($mypdo->prefix.'water_level_csv', $param);
//     }


    // /**
    //  * Table_water_level_csv::edit() 修改
    //  * @param int   $id
    //  * @param array $Attr
    //  * 
    //  *
    //  * @return
    //  */
    // static public function edit($id, $Attr){
    //     global $mypdo;

    //      $title              = $Attr['title'];
    //      $jing               = $Attr['jing'];
    //      $wei                = $Attr['wei'];

    //     $param = array (

    //          'water_level_csv_title'             => array('string',$title),
    //          'water_level_csv_jing'              => array('string', $jing),
    //          'water_level_csv_wei'               => array('string', $wei)
        
    //     );
    //     $where = array(
    //         'water_level_csv_id'        => array('number', $id)
    //     );

    //     return $mypdo->sqlupdate($mypdo->prefix.'water_level_csv', $param, $where);
    // }

    /**
     * Table_water_level_csv::del() 删除
     *
     * @param mixed $id
     * @return
     */
    static public function del($id){
        global $mypdo;

        $where = array(
            'water_level_csv_id' => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'water_level_csv', $where);
    }
     /**
      * Table_water_level_csv::getList()    节点列表
      * 
      * @param int $page         当前页
      * @param int $pagesize     每页数量
      * @return
      */
     static public function getList(){
         global $mypdo;
        
         $sql = "select * from ".$mypdo->prefix."water_level_csv where 1=1 ";
         $sql .= " order by water_level_csv_id desc  ";
               
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
    /**
     * Table_water_level_csv::search()搜索
     *
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function search($page = 1, $pagesize = 10, $begin_time=0 , $end_time=0 ,$count = 0){
        global $mypdo, $mylog;

        $page     = $mypdo->sql_check_input(array('number', $page));
        $pagesize = $mypdo->sql_check_input(array('number', $pagesize));


        $startrow = ($page - 1) * $pagesize;
        $sql = "select * from ".$mypdo->prefix."water_level_csv where 1=1 ";
        //$sql .= " and water_level_csv_title like $title ";
        //var_dump($sql);
        //
        $begin = strtotime($begin_time);
        $end = strtotime($end_time);
        if(!empty($begin)){
            $sql .= " and water_level_csv_Water_date > $begin ";
        }

        if(!empty($end)){
            $sql .= "  and water_level_csv_Water_date < $end ";
        }
        if($count){

            $r = $mypdo->sqlQuery($sql);
            return count($r);

        }else{
            $sql .= " order by water_level_csv_id desc limit $startrow, $pagesize";
            //var_dump($sql);
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


     /**
     * Table_water_level_csv::search_statis()统计
     *
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function search_statis($type, $begin_time=0 , $end_time=0 ,$count = 0){
        global $mypdo, $mylog;

        $sql = "select * from ".$mypdo->prefix."water_level_csv where 1=1 ";
        //$sql .= " and water_level_csv_title like $title ";
        //var_dump($sql);
        //
        $begin = strtotime($begin_time);
        $end = strtotime($end_time);
        if(!empty($begin)){
            $sql .= " and water_level_csv_Water_date > $begin ";
        }

        if(!empty($end)){
            $sql .= "  and water_level_csv_Water_date < $end ";
        }
        if($count){

            $r = $mypdo->sqlQuery($sql);
            return count($r);

        }else{
            $sql .= " order by water_level_csv_id asc ";
            //var_dump($sql);
            $rs = $mypdo->sqlQuery($sql);
            if($rs){
                $r = array();
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                //var_dump($r);
                if($type){
                    //type == 1代表柱状统计图
                    $i =0;

                    $count_Jan = 0;
                    $count_Feb = 0;
                    $count_Mar = 0;
                    $count_Apr = 0;
                    $count_May = 0;
                    $count_Jun = 0;
                    $count_Jul = 0;
                    $count_Aug = 0;
                    $count_Sep = 0;
                    $count_Oct = 0;
                    $count_Nov = 0;
                    $count_Dec = 0;
                    $amount_Jan = 0;
                    $amount_Feb = 0;
                    $amount_Mar = 0;
                    $amount_Apr = 0;
                    $amount_May = 0;
                    $amount_Jun = 0;
                    $amount_Jul = 0;
                    $amount_Aug = 0;
                    $amount_Sep = 0;
                    $amount_Oct = 0;
                    $amount_Nov = 0;
                    $amount_Dec = 0;
                foreach ($r as $res) {
                    # code...
                    $result[$i]['Water_date']=date('m',$res['Water_date']);
                    
                    

                    if($result[$i]['Water_date']=='01'){
                       
                        
                        $amount_Jan=$amount_Jan+$res['Water_level'];
                        $count_Jan ++;
                        //var_dump($count_Jan);exit;
                        //var_dump('01');exit;

                    }
                        
                    if($result[$i]['Water_date']=='02'){
                       
                        $amount_Feb=$amount_Feb+$res['Water_level'];
                        $count_Feb ++;
                        //var_dump('02');exit;
                    }

                    if($result[$i]['Water_date']=='03'){
                       
                        $amount_Mar=$amount_Mar+$res['Water_level'];
                        $count_Mar ++;
                        //var_dump($count_Mar);exit;
                    }

                    if($result[$i]['Water_date']=='04'){
                       
                        $amount_Apr=$amount_Apr+$res['Water_level'];
                        $count_Apr ++;
                    }

                    if($result[$i]['Water_date']=='05'){
                       
                        $amount_May=$amount_May+$res['Water_level'];
                        $count_May ++;
                    }

                    if($result[$i]['Water_date']=='06'){
                       
                        $amount_Jun=$amount_Jun+$res['Water_level'];
                        $count_Jun ++;
                    }

                    if($result[$i]['Water_date']=='07'){
                       
                        $amount_Jul=$amount_Jul+$res['Water_level'];
                        $count_Jul ++;
                    }

                    if($result[$i]['Water_date']=='08'){
                       
                        $amount_Aug=$amount_Aug+$res['Water_level'];
                        $count_Aug ++;
                    }

                    if($result[$i]['Water_date']=='09'){
                       
                        $amount_Sep=$amount_Sep+$res['Water_level'];
                        $count_Sep ++;
                    }

                    if($result[$i]['Water_date']=='10'){
                       
                        $amount_Oct=$amount_Oct+$res['Water_level'];
                        $count_Oct ++;
                    }

                    if($result[$i]['Water_date']=='11'){
                       
                        $amount_Nov=$amount_Nov+$res['Water_level'];
                        $count_Nov ++;
                    }

                     if($result[$i]['Water_date']=='12'){
                       
                        $amount_Dec=$amount_Dec+$res['Water_level'];
                        $count_Dec ++;
                    }

    
                    $i++;
                }

                //循环外对柱状统计图的组装
                
                $res_zhu[0]['device']='Jan';
                if(!$count_Jan){
                    $res_zhu[0]['sells']=0;
                }else{
                    $res_zhu[0]['sells']=$amount_Jan/$count_Jan;
                }
                
                //var_dump($res_zhu);exit;

                $res_zhu[1]['device']='Feb';
                if(!$count_Feb){
                    $res_zhu[1]['sells']=0;
                }else{
                    $res_zhu[1]['sells']=$amount_Feb/$count_Feb;
                }
                


                $res_zhu[2]['device']='Mar';
                if(!$count_Mar){
                    $res_zhu[2]['sells']=0;
                }else{
                    $res_zhu[2]['sells']=$amount_Mar/$count_Mar;
                }
                
                

                $res_zhu[3]['device']='Apr';
                if(!$count_Apr){
                    $res_zhu[3]['sells']=0;
                }else{
                    $res_zhu[3]['sells']=$amount_Apr/$count_Apr;
                }
                


                $res_zhu[4]['device']='May';
                if(!$count_May){
                    $res_zhu[4]['sells']=0;
                }else{
                    $res_zhu[4]['sells']=$amount_May/$count_May;
                }
                


                $res_zhu[5]['device']='Jun';
                if(!$count_Jun){
                    $res_zhu[5]['sells']=0;
                }else{
                    $res_zhu[5]['sells']=$amount_Jun/$count_Jun;
                }
                


                $res_zhu[6]['device']='Jul';
                if(!$count_Jul){
                    $res_zhu[6]['sells']=0;
                }else{
                    $res_zhu[6]['sells']=$amount_Jul/$count_Jul;
                }
                


                $res_zhu[7]['device']='Aug';
                if(!$count_Aug){
                    $res_zhu[7]['sells']=0;
                }else{
                    $res_zhu[7]['sells']=$amount_Aug/$count_Aug;
                }
                


                $res_zhu[8]['device']='Sep';
                if(!$count_Sep){
                    $res_zhu[8]['sells']=0;
                }else{
                    $res_zhu[8]['sells']=$amount_Sep/$count_Sep;
                }
                


                $res_zhu[9]['device']='Oct';
                if(!$count_Oct){
                    $res_zhu[9]['sells']=0;
                }else{
                    $res_zhu[9]['sells']=$amount_Oct/$count_Oct;
                }
                


                $res_zhu[10]['device']='Nov';
                if(!$count_Nov){
                    $res_zhu[10]['sells']=0;
                }else{
                    $res_zhu[10]['sells']=$amount_Nov/$count_Nov;
                }
                

                $res_zhu[11]['device']='Dec';
                if(!$count_Dec){
                    $res_zhu[11]['sells']=0;
                }else{
                    $res_zhu[11]['sells']=$amount_Dec/$count_Dec;
                }
                
                    
                 $res_zhu = json_encode($res_zhu);
                return $res_zhu;
                //var_dump($)
                }else{
                    //type == 0代表折线统计图
                    //拿出数组中的日期数据，去重并重新排列
                    $i = 0;
                    foreach ($r as $res ) {
                        # code...
                        $date[$i]=date('Y-m',$res['Water_date']);

                        $i++;
                    }
                    $arrays = array_unique($date);
                    $j = 0;
                    foreach ($arrays as $arr) {
                        # code...
                        $date_arr[$j] = $arr; 
                        $j++;
                    }

                   // var_dump($date_arr);
                   // 将原数据与日期数据进行比较运算处理
                    $m = 0;

                    //$data_middle =array() ;
                    //日期数据
                foreach ($date_arr as $date_arrr) {
                        $n = 0;
                        $p=0;
                    foreach ($r as $rr) {
                        # code...
                        //var_dump($rr);
                        $arrr=date('Y-m',$rr['Water_date']);
                        //var_dump($date_arrr);
                        //var_dump($arrr);exit;
                             
                            if($arrr == $date_arrr){
                                //var_dump($arrr);exit;

                                $data_middle[$m]['date'] =$rr['Water_date'];
                                $data_middle[$m]['now_all'] =$data_middle[$m]['now_all']+$rr['Water_level'];  
                                $data_middle[$m]['pre_all'] =$data_middle[$m]['pre_all']+$rr['predict'];
                                $data_middle[$m]['num'] = $p;
                                $p++;
                                
                            }
                           
                            $n++;

                        }


                        $m++;
                    }


                   //var_dump($data_middle);exit;
                   $key = 0;
                   foreach ($data_middle as $middle) {
                       # code...
                       $date_year = date('Y',$middle['date']);
                       $date_month= date('m',$middle['date']);
                       $date_month = intval($date_month);
                       //$data_final[$key]['period'] = $date_year.' Q'.$date_month; 
                       //var_dump($data_final[$key]['period']);exit;
                       $data_final[$key]['period'] = date('Y-m',$middle['date']);
                       $data_final[$key]['now'] = $middle['now_all']/$middle['num'];
                       $data_final[$key]['pre'] = $middle['pre_all']/$middle['num'];
                       $key++;
                   }
                   //var_dump($data_final);exit;
                   $data_final = json_encode($data_final);
                        return $data_final;
                }



                 
            }else{
                return 0;
            }




        }


    }


}
?>