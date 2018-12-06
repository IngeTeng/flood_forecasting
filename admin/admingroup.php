<?php
/**
 *管理员组列表  admingroup.php
 *
 * @version       v0.03
 * @create time   2014/8/4
 * @update time   2016/3/26
 * @author        IngeTeng
 */
	require_once('admin_init.php');
	require_once('admincheck.php');

    $POWERID        = '7001';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);
	
	$FLAG_LEFTMENU  = 'admingroup_list';
	$FLAG_TOPNAV    = 'role';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="Neptune工作室" />
		<title>管理员组 - 管理设置 - 管理系统</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript">
		$(function(){
			//添加
			$('#addgroup').click(function(){
	            layer.open({
	                type: 2,
	                title: '添加管理员组',
	                shadeClose: true,
	                shade: 0.3,
	                area: ['500px', '300px'],
	                content: 'admingroup_add.php'
	            }); 
			});
			//修改
			$(".editinfo").click(function(){
				var thisid = $(this).parent('td').find('#gid').val();
	            layer.open({
	                type: 2,
	                title: '修改管理员组',
	                shadeClose: true,
	                shade: 0.3,
	                area: ['500px', '300px'],
	                content: 'admingroup_edit.php?id='+thisid
	            }); 
			});
            //排序
			$('#order').click(function(){
				var trnum = $("table tr").length-1;//获取数据数量
				var orderlist = idlist = '';
				for(i=1;i<=trnum;i++){
					//获取order
					orderlist = $("table tr").eq(i).find('td').eq(2).find('.order-input').val()+','+orderlist;
					//获取id
					idlist = $("table tr").eq(i).find('td').eq(3).find('#gid').val()+','+idlist;
				}
				$.getJSON('admingroup_do.php?act=order',{order:orderlist, id:idlist},function(data){
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
			//删除操作
			$(".delete").click(function(){
				var thisid = $(this).parent('td').find('#gid').val();
				layer.confirm('确认删除该管理员组吗？', {
	            	btn: ['确认','取消']
		            }, function(){
		            	var index = layer.load(0, {shade: false});
		            	$.ajax({
							type        : 'POST',
							data        : {
								id : thisid
							},
							dataType : 'json',
							url : 'admingroup_do.php?act=del',
							success : function(data){
											layer.close(index);

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
										  }
						});
		            }, function(){}
		        );

			});
			$(".editpower").mouseover(function(){
				layer.tips('设置权限', $(this), {
				    tips: [4, '#3595CC'],
				    time: 500
				});
			});
			$(".editinfo").mouseover(function(){
				layer.tips('修改', $(this), {
				    tips: [4, '#3595CC'],
				    time: 500
				});
			});
			$(".delete").mouseover(function(){
				layer.tips('删除', $(this), {
				    tips: [4, '#3595CC'],
				    time: 500
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
				<div id="position">当前位置：<a href="admingroup.php">系统设置</a> &gt; 管理员组</div>
				<form method="post" action="admingroup_do.php?act=order" id="listform">
				<div id="handlelist">
                <?php
					//无需分页
                    $rows = Admingroup::getList();
                ?>
					<input type="button" class="btn-handle" href="javascript:" id="addgroup" value="添 加">
					<input type="button" class="btn-handle" href="javascript:" id="order" value="排序">
					<span class="table_info">共<?php echo count($rows);?>条数据</span>
					<div></div>
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th>组名称</th>
							<th>组类型</th>
							<th>排序</th>
							<th>操作</th>
						</tr>
						<?php
                        
							$i=1;
							if(!empty($rows)){
								foreach($rows as $row){
						?>
						<tr>
							<td><?php echo $row['name'];?></td>
							<td class="center"><?php echo $ARRAY_admin_type[$row['type']];?></td>
							<td class="center"><input type="text" class="order-input" name="ag_order[]" value="<?php echo $row['order'];?>"></td>
							<td class="center">
								<input type="hidden" id="gid" value="<?php echo $row['groupid'];?>"/>
								<a class="editpower" href="admingroup_auth.php?id=<?php echo $row['groupid']?>" title="修改组权限"><img src="images/dot_power.png"/></a> 
						
                                <a class="editinfo" href="javascript:void(0)" title="修改组信息"><img src="images/dot_edit.png"/></a>

								<a  href="javascript:void(0)" class="delete" title="删除"><img src="images/dot_del.png"/></a>
								
							</td>
						</tr>
						<?php
									$i++;
								}
							}else{
								echo '<tr><td colspan="4">没有数据</td></tr>';
							}
							
						?>
						
					</table>
				</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>