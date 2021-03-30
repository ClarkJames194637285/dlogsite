
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    
    <!-- PieCharts library -->
    <!--Load the Google AJAX API-->
    <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
    <!-- Load CanvasJS -->
    <!-- <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>   -->
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

    
    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/home.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>

</head>

<body id="pg_index" class="pg_index home">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php include("menu.php"); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('home');?></h1>
            <section class="main-content ">

                <div class="content-grid ">
                    <div>
                        <div class="shortcut-grid flexlyr">
                            <!-- <a href="page2.php" class="shortcut-btn monitor nonV"></a> -->
                            <!-- センサー監視 - sensor monitor -->
                            <a href="<?php echo base_url()?>SensorMonitoring" class="shortcut-btn monitor"></a>
                            <!-- メッセージ - message -->
                            <a href="<?php echo base_url()?>Message/inbox" class="shortcut-btn message">888</a>
                            <!-- サポート情報 - support information -->
                            <a href="<?php echo base_url()?>AlarmHistory" class="shortcut-btn support"></a>
                            <!-- アップデート情報 - update information -->
                            <a href="<?php echo base_url()?>Setting/GroupManagement" class="shortcut-btn group"></a>
                            <!-- センサー管理 - sensor manage -->
                            <a href="<?php echo base_url()?>Setting/SensorManagement" class="shortcut-btn sensor"></a>
                            <!-- ログアウト - logout -->
                            <a href="<?php echo base_url()?>User/logout" class="shortcut-btn logout"></a>
                            
                        </div>
                    </div>
                    <div class="news-grid flexlyr">
                        <div class="news-block">
                            システムメンテナンスのお知らせ６７８
                            １２３４５６７８９０１２３４５６７８
                            １２３４５６７８９０１２３４５６７８
                            １２３４５６７８９０１２３４５６７８
                            １２３４５６７８９０１２３４５６７８
                            １２３４５６７８９０１２３４５６７８
                            １２３４５６７８９０１２３４５６７８
                        </div>
                        <div class="news1-block"><?=$this->lang->line('advertising');?> <img src="<?php echo base_url()?>assets/img/asset_21.png" alt=""></div>
                    </div>

                </div>
                <div class="PieChart-grid flexlyr">
                    <div class="PieCharts left-block flexlyr">
                        <p class="charts-label"><?=$this->lang->line('operatingStatus');?></p>
                        <!-- google charts -->
                        <!-- <div id="chart_div" class="PieChart-graph"></div> -->
                        <!-- CanvasJS Charts Object -->
                        <div id="left-chartContainer" class="PieChart-graph"></div>
                        <div class="param-block1">
                            <p class="normal-param"><?=$this->lang->line('normal');?></p>
                            <p class="warning-first"><?=$this->lang->line('Warning1');?></p>
                            <p class="warning-second"><?=$this->lang->line('Warning2');?></p>
                            <p class="nouse-param"><?=$this->lang->line('offline');?></p>
                        </div>
                        <div class="param-block2">
                            <p class="normal-param" id="working"><?php echo $count['working'];?></p>
                            <p class="warning-first" id="warning1"><?php echo $count['warning1'];?></p>
                            <p class="warning-second" id="warning2"><?php echo $count['warning2'];?></p>
                            <p class="nouse-param" id="notWorking"><?php echo $count['notWorking'];?></p>
                        </div>
                    </div>
                    <div class="PieCharts right-block flexlyr">
                        <p class="charts-label"><?=$this->lang->line('registeredSensor');?></p>
                        <!-- google charts -->
                        <!-- <div id="chart_div1" class="PieChart-graph"></div> -->
                        <!-- CanvasJS Charts Object -->
                        <div id="right-chartContainer" class="PieChart-graph"></div>
                        <?php echo $string;?>
                    </div>
                </div>
                

            </section>

            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>


</body>

</html>