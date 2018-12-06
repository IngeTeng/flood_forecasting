<?php

/**
 * water_level_csv.class.php 数据类
 *
 * @version       v0.01
 * @create time   2016/11/16
 * @update time
 * @author        IngeTeng
 * @copyright     Neptune工作室
 */

class Water_level_csv {

    public $id     = 0;
    public $status = 0;

    public function __construct() {
    }

    /**
     * water_level_csv::getInfoById()
     *
     * @param mixed $id
     * @return
     */
    static public function getInfoById($id){
        if(empty($id)) throw new MyException('ID不能为空', 101);

        return Table_water_level_csv::getInfoById($id);
    }


    /**
     * water_level_csv::add()
     *
     * @param array $cartAttr
     * 
     *
     * @return void
     */
    static public function add($water_level_csvAttr = array()){

        //添加和修改的操作校验相同。所以单独做个函数
        $okAttr = self::checkwater_level_csvInputParam($water_level_csvAttr);

        $rs = Table_water_level_csv::add($okAttr);
        if($rs >= 0){
            return action_msg('添加成功', 1);
        }else{
            throw new MyException('操作失败', 106);
        }
    }


    /**
     * water_level_csv::edit()
     *
     * @param mixed $id
     * @param array $cartAttr
     * 
     *
     * @return
     */
    static public function edit($id, $water_level_csvAttr){

        if(empty($id)) throw new MyException('ID不能为空', 100);

        $okAttr = self::checkwater_level_csvInputParam($water_level_csvAttr);

        $rs = Table_water_level_csv::edit($id, $okAttr);

        if($rs >= 0){
            $msg = '修改成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 106);
        }
    }

    /**
     * water_level_csv::checkwater_level_csvInputParam()
     *
     * @param array $cartAttr
     *
     * @return void
     */
    static private function checkwater_level_csvInputParam($water_level_csvAttr){
        if(empty($water_level_csvAttr) || !is_array($water_level_csvAttr)) throw new MyException('参数错误', 100);
        if(empty($water_level_csvAttr['state'])) throw new MyException('状态不能为空', 201);
        if(empty($water_level_csvAttr['Water_date'])) throw new MyException('数据对应时间不能为空', 202);
        if(empty($water_level_csvAttr['Water_level'])) throw new MyException('水位数据不能为空', 203);
        return $water_level_csvAttr;
    }



    /**
     * water_level_csv::del()
     *
     * @param mixed $id
     * @return
     */
    static public function del($id){

        if(empty($id))throw new MyException('ID不能为空', 101);

        $rs = Table_water_level_csv::del($id);
        if($rs == 1){

            $msg = '删除成功!';

            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 102);
        }
    }
    /**
     * water_level_csv::getList()
     * 
     * @param integer $page
     * @param integer $pagesize
     * @return
     */
    static public function getList(){
        
        
        return Table_water_level_csv::getList();
    }
    /**
     * water_level_csv::search()
     *
     * @param integer $page
     * @param integer $pagesize
     * @param integer $choose//0 代表护工 1代表月嫂
     * @param integer $name//要查找的名字
     * @param integer $count //是否仅作统计 1 - 统计
     * @return
     */
    static public function search($page = 1, $pagesize = 10,  $begin_time=0,$end_time=0,  $count = 0){
        if(!preg_match('/^\d+$/', $page)) $page = 1;
        if(!preg_match('/^\d+$/', $pagesize)) $pagesize = 10;

        return Table_water_level_csv::search($page, $pagesize, $begin_time,$end_time,  $count);
    }


}
?>