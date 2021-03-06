<?php 
    $advertisement = file_get_contents(APPPATH."advertisement/advertisement.txt");
    $base=1024;
    $total=round(disk_total_space("C:")/(pow($base,3)),1);
    $free = round(disk_free_space("C:")/(pow($base,3)),1);
    $used=$total-$free;
?>
<style>
    .PieCharts.left-block {
    width: 45%;
    border: solid #707070 1px;
    justify-content: center;
}
.PieCharts.right-block {
    width: 55%;
    border: solid #707070 1px;
    justify-content: center;
}
</style>
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
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/adminhome.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
<script>
    window.onload = function () {
   
   var leftdata=[];
   var color=[];
   var working=<?php echo $used;?> ;
   if(working!==0){
       leftdata.push({ y: working, name:"?????????????????????" });
       color.push("#3F3F3F");
   }
   var notWorking=<?php echo $free;?> ;;
   if(notWorking!==0){
       leftdata.push({ y: notWorking, name:"??????????????????" });
       color.push("#33B800");
   }
   
   CanvasJS.addColorSet("greenShades",color);
   var leftoptions = {
       showInLegend: true,
       legendText: "{indexLabel}",
       colorSet: "greenShades",
       animationEnabled: true,
       legend: false,
       data: [{
           type: "pie",
           showInLegend: false,
           percentFormatString: "#0.##",
           indexLabel: "#percent%",
           toolTipContent: "<b>{name}</b>: {y} (#percent%)",
           legendText: "{name} (#percent%)",
           indexLabelPlacement: "inside",
           dataPoints: leftdata
       }]
   };
   $("#left-chartContainer").CanvasJSChart(leftoptions);
}
</script>
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
                            <!-- ?????????????????? - support information -->
                            <a href="https://www.dlog.jp/" class="shortcut-btn support"></a>
                            <!-- ???????????????????????? - update information -->
                            <!-- ??????????????? - logout -->
                            <a href="<?php echo base_url()?>User/logout" class="shortcut-btn logout"></a>
                        </div>
                    </div>
                </div>
                <div class="PieChart-grid flexlyr">
                    <div class="PieCharts left-block flexlyr">
                        <p class="charts-label"><?=$this->lang->line('disk_amount');?></p>
                        <!-- google charts -->
                        <!-- <div id="chart_div" class="PieChart-graph"></div> -->
                        <!-- CanvasJS Charts Object -->
                        <div id="left-chartContainer" class="PieChart-graph"></div>
                        <div class="param-block1">
                            <p class="normal-param"><?=$this->lang->line('total_disk');?></p>
                            <p class="nouse-param"><?=$this->lang->line('used_disk');?></p>
                            <p class="normal-param"><?=$this->lang->line('remain_disk');?></p>
                        </div>
                        <div class="param-block2">
                            <p class="normal-param" id="working"><?php echo $total;?>GB</p>
                            <p class="nouse-param" id="working"><?php echo $used;?>GB</p>
                            <p class="normal-param" id="notworking"><?php echo $free;?>GB</p>
                        </div>
                       
                    </div>
                    <div class="PieCharts right-block flexlyr">
                        <p class="charts-label"><?=$this->lang->line('current_login_user');?></p>
                       
                        <div class="param-block1" style="width:40%;">
                           
                            <?php foreach($loggedin_user as $val){ ?>
                                <p class="normal-param"><?php print_r($val['UserName']);?></p>
                            <?php }?>
                        </div>
                        <div class="param-block2" style="width:60%;">
                            <?php if(empty($loggedin_user)) {?>
                                <p class="nouse-param">??????????????????????????????</p>
                            <?php }?>
                            <?php foreach($loggedin_user as $val) {
                                $logintime=explode('.',$val['LoginTime'])
                            ?>
                                <p class="normal-param"><?php print_r($logintime[0]);?></p>
                            <?php }?>
                        </div>
                        
                    </div>
                </div>
            </section>
            <div class="pg-footer">
                <p class="footer-label">?????2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>


</body>

</html>