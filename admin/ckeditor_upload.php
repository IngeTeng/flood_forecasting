<?php
    /**
     * CKeditor编辑器上传  ckeditor_upload.php
     *
     * @version       v0.01
     * @create time   2016-4-17
     * @update time   
     * @author        IngeTeng
     * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
     */

    require_once('admin_init.php');
    require_once('admincheck.php'); 
    
    $POWERID = '7001';//权限
    Admin::checkAuth($POWERID, $ADMINAUTH);
    
    require($LIB_COMMON_PATH.'fileupload.class.php');
    
    
    $req  = strCheck($_GET['CKEditorFuncNum'], false);//ckeditor回调参数
    $type = strCheck($_GET['type'], false);
    
    $fileElementName = 'upload';
    
    switch($type){
        case 'img'://上传图片
           $allowext        = array( 'jpg', 'png', 'gif');
           $filepath_rel    = 'userfiles/upload/img/';//相对路径
           $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径
           
           try{
                $fup = new FileUpload('2M', $allowext);
                $r = $fup->upload($fileElementName, $filepath_abs, '', true);
                
                //TODO对图片进行处理，比如加水印、裁剪等可以放在这里
                
                $name_rel = $filepath_rel.$r;
                $imgpath = $HTTP_PATH . $name_rel;
                echo "<script>window.parent.CKEDITOR.tools.callFunction($req , '$imgpath');</script>";
           }catch(Exception $e){
                //echo $e->getMessage();
                echo "<script>alert('".$e->getMessage()."');</script>";
           }
           
        break;
        
        case 'file'://上传文件
           $filepath_rel    = 'userfiles/upload/file/';//相对路径
           $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径
           
           try{
                $fup = new FileUpload('8M');
                $r = $fup->upload($fileElementName, $filepath_abs, '', true);
                $name_rel = $filepath_rel.$r;
                $filepath = $HTTP_PATH . $name_rel;
                echo "<script>window.parent.CKEDITOR.tools.callFunction($req , '$filepath');</script>";
           }catch(Exception $e){
                echo "<script>alert('".$e->getMessage()."');</script>";
           }
        break;
        /*
        case 'flash':
            $allowext        = array( 'mp4', 'mp3', 'flv');
            $filepath_rel    = 'userfiles/upload/video/';//相对路径
            $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径
           
            try{
                
                $fup = new FileUpload('50M', $allowext);
                $r = $fup->upload($fileElementName, $filepath_abs, '', true);
                $name_rel = $filepath_rel.$r;
                $filepath = $HTTP_PATH . $name_rel;
                echo "<script>window.parent.CKEDITOR.tools.callFunction($req , '$filepath');</script>";
            }catch(Exception $e){
                echo "<script>alert('".$e->getMessage()."');</script>";
            }
        break;
        */
    }

    
?>