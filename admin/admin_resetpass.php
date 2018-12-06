<?php
	/**
	 * 修改密码  admin_resetpass.php
	 *
	 * @version       v0.02
	 * @create time   2014-9-5
	 * @update time   2016/3/25
	 * @author        IngeTeng
	 */

	require_once('admin_init.php');
	require_once('admincheck.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="创富平台" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#btn_submit').click(function(){
					old_password = $('input[name="old_password"]').val();
					new_password = $('input[name="new_password"]').val();
					re_password = $('input[name="re_password"]').val();


					if(old_password == ''){
						layer.tips('旧密码不能为空', '#s_old_password');
						return false;
					}
					if(new_password == ''){
						layer.tips('新密码不能为空', '#s_new_password');
						return false;
					}
					if(re_password == ''){
						layer.tips('确认密码不能为空', '#s_re_password');
						return false;
					}
					if(new_password != re_password){
						layer.alert('两次输入密码不一致！', {icon: 5, shade: false});
						return false;
					}

					$.ajax({
						type        : 'POST',
						data        : {
								old_password  : old_password,
								new_password  : new_password,
								re_password   : re_password
						},
						dataType : 'json',
						url :         'admin_do.php?act=editpass',
						success :     function(data){
										    code = data.code;
											msg  = data.msg;
											switch(code){
												case 1:
													layer.alert('修改成功！', {icon: 6, shade: false}, function(index){
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
		<div id="formlist">
			<p>
				<label>旧密码</label>
				<input type="password" class="text-input input-length-30" name="old_password" id="old_password" value="" />
				<span class="warn-inline" id="s_old_password">* </span>
			</p>
			<p>
				<label>新密码</label>
				<input type="password" class="text-input input-length-30" name="new_password" id="new_password" />
				<span class="warn-inline" id="s_new_password">* </span>
			</p>
			<p>
				<label>确认新密码</label>
				<input type="password" class="text-input input-length-30" name="re_password" id="re_password" value="" />
				<span class="warn-inline" id="s_re_password">* </span>
			</p>
			<p>
				<label>　　</label>
				<input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
			</p>
		</div>
	</body>
</html>