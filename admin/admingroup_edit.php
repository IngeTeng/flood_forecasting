<?php
/**
	 * 编辑管理员组  admingroup_edit.php
	 *
	 * @version       v0.03
	 * @create time   2014-8-3
	 * @update time   2016/3/26
	 * @author        IngeTeng
	 */

	require_once('admin_init.php');
	require_once('admincheck.php');

	$POWERID       = '7001';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);

    $FLAG_LEFTMENU = 'admingroup_edit';	
    
	//获得参数后，率先检查参数的合法性
    $id = strCheck(trim($_GET['id']));

	try {
		$group = new Admingroup($id);
		$groupid   = $id;
		$groupname = $group->name;
		$grouptype = $group->type;
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
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#btn_sumit').click(function(){

					var group_name = $('input[name="group_name"]').val();
					var group_type = $('select[name="group_type"]').val();
					var group_id   = <?php echo $groupid;?>;
					if(group_name == ''){
						layer.tips('组名不能为空', '#s_group_name');
						return false;
					}
					if(group_type == ''){
						layer.tips('组类型不能为空', '#s_group_type');
						return false;
					}
					$.ajax({
						type        : 'POST',
						data        : {
							group_name  : group_name,
							group_type  : parseInt(group_type),
							id               : group_id
						},
						dataType :  'json',
						url :       'admingroup_do.php?act=edit',
						success :   function(data){
											var code = data.code;
											var msg  = data.msg;
											switch(code){
												case 1:
													layer.alert(msg, {icon: 6 ,shade: false}, function(index){
														parent.location.reload();
													});
													break;
												default:
													layer.alert(msg, {icon: 5});
											}
									}
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="maincontent">
			<div id="formlist">
				<p>
					<label>组名称</label>
					<input type="text" class="text-input input-length-30" name="group_name" id="group_name" value="<?php echo $groupname;?>" />
					<span class="warn-inline" id="s_group_name">* </span>
				</p>
				<p>
					<label>组类型</label>
					<select name="group_type" id="group_type" class="select-option">
						<?php
							//获取组类型
							foreach($ARRAY_admin_type as $key => $value){
								echo '<option value="'.$key.'"';
								if($grouptype == $key){
									echo 'selected>'.$value.'</option>';
								}else{
									echo '>'.$value.'</option>';
								}
							}
						?>
					</select>
					<span class="warn-inline" id="s_group_type">* </span>
				</p>
				<p>
					<label>　　</label>
					<input type="hidden" name="group_id" class="btn_submit" value="<?php echo $groupid;?>" />
					<input type="submit" id="btn_sumit" class="btn_submit" value="修　改" />
				</p>
			</div>
		</div>
	</body>
</html>