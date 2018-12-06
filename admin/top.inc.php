		<script>
		$(function(){
            //管理员修改密码
			$("#newpass").click(function(){
	            layer.open({
	                type: 2,
	                title: '修改密码',
	                shadeClose: true,
	                shade: 0.3,
	                area: ['500px', '300px'],
	                content: 'admin_resetpass.php'
	            }); 
			});
		});
		</script>
		<div class="top">
			<div class="logo" style="position:absolute;top:18px;left:0;">
				<a href="index.php"><img src="images/logo.jpg" height="60" alt="" /></a>
			</div>
			<div class="topinfo2">
				管理员，欢迎您！ 帐号：<?php echo $ADMIN->getAccount();?> <?php echo $ADMINGROUP->getName();?>
			</div>
			<div class="topinfo">
				[<a id="newpass" href="javascript:void(0);" title="修改密码">修改密码</a>] [<a href="adminlogout.php" title="退出登录">退出登录</a>]
			</div>
		</div>