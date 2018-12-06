<?php
	/**
	 * 编辑管理员组权限  admingroup_auth.php
	 *
	 * @version       v0.03
	 * @create time   2014-8-11
	 * @update time   2016/3/26
	 * @author        IngeTeng
	 */
    require_once('admin_init.php');
	require_once('admincheck.php');

	$POWERID = '7001';//权限
    Admin::checkAuth($POWERID, $ADMINAUTH);

    $FLAG_TOPNAV   =  "role";
    $FLAG_LEFTMENU =  'admingroup_list';

	//组权限
	$id = strCheck($_GET['id']);
	try {
		$group = new Admingroup($id);
		$groupId   = $id;
		$groupName = $group->name;
		$groupAuth = explode('|', $group->auth);
	} catch(MyException $e){
		echo $e->getMessage();
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="Neptune工作室" />
		<title>管理员权限修改 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#btn_submit').click(function(){
				    var powernum =  $('#formlist p > .checkbox-input').length;
                    var powerlist = '';
                    var id = $("#gid").val();
                    for(i=0; i<powernum; i++){
                        if($('#formlist p > .checkbox-input').eq(i).prop('checked')){
                            powerlist = $('#formlist p > .checkbox-input').eq(i).val() + '|' + powerlist;
                        }
                    }
                    $.getJSON('admingroup_do.php?act=updateauth',{id : id, auth : powerlist},function(data){
                        var code = data.code;
						var msg  = data.msg;
						switch(code){
							case 1:
								layer.alert(msg, {icon: 6}, function(index){
									location.reload();
								});
								break;
							default:
								layer.alert(msg, {icon: 5});
						}
                    });
				});

			});
				
		</script>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<?php include('role_menu.inc.php');?>
			<div id="maincontent">
				<div id="position">当前位置：<a href="admingroup.php">系统设置</a> &gt; <a href="admingroup.php">管理员组权限</a> &gt; 管理员组权限修改</div>
				<div id="formlist" style="padding:0 40px;">
					<h3>编辑“<?php echo $groupName?>”的权限</h3>
					<p>
 						<input type="checkbox" class="checkbox-input" value="7001" <?php if(in_array(7001, $groupAuth)) echo 'checked';?> /><span class="radio-text">管理员组</span>
						<input type="checkbox" class="checkbox-input" value="7002" <?php if(in_array(7002, $groupAuth)) echo 'checked';?> /><span class="radio-text">管理员</span>
						<input type="checkbox" class="checkbox-input" value="7003" <?php if(in_array(7003, $groupAuth)) echo 'checked';?> /><span class="radio-text">管理员日志</span>
						<input type="checkbox" class="checkbox-input" value="7004" <?php if(in_array(7004, $groupAuth)) echo 'checked';?> /><span class="radio-text">新闻管理</span>
						<input type="checkbox" class="checkbox-input" value="7005" <?php if(in_array(7005, $groupAuth)) echo 'checked';?> /><span class="radio-text">新闻分类管理</span>

                        
					</p>

                    <p>
        				<input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
                        <input type="hidden" id="gid" value="<?php echo $groupId;?>" />
        			</p>
        		</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>

