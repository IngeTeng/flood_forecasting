<div id="leftmenu">
    <div class="menu1"><a href="water_level_csv_list.php">数据管理
    </a></div>
    <?php
        //商品表，分类表和购物车表
        $sessionAuth = explode('|', $ADMINAUTH);
        if(in_array('7001', $sessionAuth)){
            echo '<div class="menu2"><a';
            if($FLAG_LEFTMENU == 'water_level_csv_list') echo ' class="active"';
            echo ' href="water_level_csv_list.php">数据管理</a></div><div class="menuline"></div>';
        }
        if(in_array('7001', $sessionAuth)){
            echo '<div class="menu2"><a';
            if($FLAG_LEFTMENU == 'statistics_list') echo ' class="active"';
            echo ' href="statistics_list.php">统计管理</a></div><div class="menuline"></div>';
        }

        if(in_array('7001', $sessionAuth)){
            echo '<div class="menu2"><a';
            if($FLAG_LEFTMENU == 'predict_list') echo ' class="active"';
            echo ' href="predict_list.php">预测管理</a></div><div class="menuline"></div>';
        }
    
  ?>
</div>