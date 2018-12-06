<?php
	/**
	 * 管理员日志列表  adminlog_list.php
	 *
	 * @version       v0.03
	 * @create time   2014-8-3
	 * @update time   2016/3/25
	 * @author        IngeTeng
	 */
	require_once('admin_init.php');
    require_once('admincheck.php');
    
    $FLAG_TOPNAV   = "role";
	$FLAG_LEFTMENU = 'adminlog_list';

	$POWERID       = '7001';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="Neptune工作室" />
		<title>管理员日志 - 管理设置 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link rel="stylesheet" href="css/boxy.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
           <script type="text/javascript">
            $(function(){
                //添加按钮
                 $('#btn_downloadadminlog').click(function(){
                    location.href = 'adminlog_download.php';
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
				<div id="position">当前位置：<a href="adming_list.php">系统设置</a> &gt; 管理员日志</div>
                <div id="handlelist">

                    <span class="table_info"><input type="button" class="btn-handle" id="btn_downloadadminlog" value="下载管理员日志信息"/></span>
                    <div>
                    </div>
                </div>
				<div class="tablelist">
					<table>
						<tr>
							<th>时间</th>
                            <th>管理员帐号</th>
                            <th>内容</th>
                            <th>IP</th>
						</tr>
						<?php
                            //初始化
                        	$totalcount= Adminlog::logCount();
                        	$shownum   = 10;
                        	$pagecount = ceil($totalcount / $shownum);
                        	$page      = getPage($pagecount);
                        	$rows      = Adminlog::logList($page, $shownum);
							$i=1;
							//如果列表不为空
							if(!empty($rows)){
								foreach($rows as $row){

								    //获取管理员账号
                                    try {
                                        $admin       = new Admin($row['adminid']);
                                        $account     = $admin->getAccount();
                                    }catch(MyException $e){
                                        $account = '-';
                                    }
                                    

									if(!empty($row['logtime']))
										$logtime = date('Y-m-d H:i:s', $row['logtime']);
									else
										$logtime = '';
									echo '<tr>
                                            <td width="20%" class="center">'.$logtime.'</td>
											<td width="20%">'.$account.'</td>
                                            <td width="40%">'.$row['logcontent'].'</td>
                                            <td width="20%">'.$row['ip'].'</td>
										</tr>
									';
									$i++;
								}
							}else{
								echo '<tr><td class="center" colspan="4">没有数据</td></tr>';
							}
						?>
						
					</table>
				</div>
                <!-- 分页信息 -->
				<div id="pagelist">
					<div class="pageinfo">
						<span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
					</div>
					<?php 
						if($pagecount>1){
							echo dspPages("adminlog_list.php", $page, $shownum, $totalcount, $pagecount);
						}
					?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>