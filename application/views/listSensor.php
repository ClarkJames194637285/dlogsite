
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
    
    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- toast window -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    

    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    
    <!-- jquery dragable -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- img object fit -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>

    <!-- custom jscript -->
    <!-- <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
</head>

<script>
    objectFitImages();
    $( function() {
        $( "#map-layer" ).draggable();
        $( ".senseor-icon" ).draggable().css("position", "absolute");
    } );
</script>

<body id="pg_index" class="pg_index alarm-history">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php include("menu.php"); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">アラーム履歴</h1>
            <section class="main-content ">

                <div class="content-grid">
                    <?php echo $Sensors;?>




                    

                </div>

                <div class="side-bar flexlyr">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring" class="type1"></a></li>
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring/listSensor" class="type2 active"></a></li>
                    </ul>
                    
                    <!-- search filter type - フィルター -->
                    
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter">フィルター</p>

                        <div class="srh-block">
                            <select name="option" id="option">
                                <option value="0" selected>グループ</option>
                                <!-- <option value="" >センサー</option> -->
                                <option value="1" >バッテリー要交換</option>
                                <option value="2" >アラーム発生中</option>
                                <option value="3" >オフライン</option>
                                
                            </select>
                            <ul class="filter-type">
                            <?php foreach($Group as $val){ ?> 
                                    <li class="view-on" id="group<?php echo $val['ID'];?>"><a ><?php print_r($val['GroupName']);?></a></li>
                                <?php } ?>
                                
                            </ul>
                            <p class="set-view"><a  onclick="showAll()">全て表示する</a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listmanagement">並び替える</a></p>
                        </div>

                        <div class="srh-block temp-hum">
                            <p class="srh-title">センサータイプ</p>
                            <ul>
                                <li class="view-on" id="temp_view"><a >温度計</a></li>
                                <li class="view-on" id="humidity_view"><a >温湿度計</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </section>

            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>

    <script>
    
    function showAll(){
        $(".temperature").css("display","");
        $(".humidity").css("display","");
        $(".offline").css('display','');
        $("#temp_view").addClass('view-on');
        $("#humidity_view").addClass('view-on');
        $(".filter-type").children().each(function( index ) {
            $(this).addClass('view-on');
        })
    }
   
   
    $('.srh-block li a').click(function () {
        // $(".trans-btn").removeClass('select-on');
        $(this).parent('li').toggleClass('view-on');
        var check=$(this).parent('li').hasClass('view-on');
        var str=$(this).parent('li')[0].id;
        if(check){
            if(str=='temp_view'){
                $(".temperature").css("display","");
            }
            if(str=='humidity_view'){
                $(".humidity").css("display","");
            }
            else
                $("."+str).css('display','');
        }
        else{
            if(str=='temp_view'){
                $(".temperature").css("display","none");
            }
            if(str=='humidity_view'){
                $(".humidity").css("display","none");
            }
            else
                $("."+str).css('display','none');
        }
    });
    $('select').on('change', function () {
    //ways to retrieve selected option and text outside handler
        var check=$('#option option:selected').val();
        showWay(check);
    });
    function showWay(check){
        
        if(check==0){
            allshow();
        }else if(check==1){
            allhidden();
            $(".reqbattery").parent().css('display','');
        }else if(check==2){
            allhidden();
            $(".warning").css('display','');
        }else if(check==3){
            allhidden();
            $(".offline").css("display",'');
        }
    }
    function allhidden(){
        $(".alarm-block").css('display','none');
       
    }
    function allshow(){
        $(".alarm-block").css('display','');
       
    }

 </script>  
</body>

</html>