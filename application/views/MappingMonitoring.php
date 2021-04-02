<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BSCM</title>
    <!-- viewport setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
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
    <!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  -->
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <link href="<?php echo base_url()?>assets/css/draganddrop.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/js/draganddrop.js"></script>

    
    <!-- img object fit -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>

    
</head>
<style>
    .oneday{
        display: flex;
    }
    .week{
        display:none;
    }
    .twoday{
        display: none;
    }
</style>
<script>
    objectFitImages();
    $( function() {
        $( "#map-layer" ).draggable();
        $( ".sensorGroup" ).draggable().css("position", "absolute");
    } );
</script>

<body id="pg_index" class="pg_index mapping">
    
    <div class="pg-header flexlyr">
        <a href="<?php echo base_url()?>home" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>
        <div class="user-infor flexlyr">
            <a href="<?php echo base_url()?>Message/inbox" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
                <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
            </a>
            <a href="<?php echo base_url()?>User/logout" class="user-name"><img src="<?php echo base_url()?>assets/img/asset_02.png" alt=""><span><?php echo $user_name;?></span></a>
        </div>
    </div>
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php include("menu.php"); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('mappingMonitoring_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div class="infor-block">
                        <h2 class="block-title" id="mapName"><?php echo $mapName;?></h2>
                        <div class="color-bar flexlyr">
                            <p class="param">10℃</p>
                            <p class="temperature lvl1"></p>
                            <p class="temperature lvl2"></p>
                            <p class="temperature lvl3"></p>
                            <p class="temperature lvl4"></p>
                            <p class="temperature lvl5"></p>
                            <p class="param">60℃</p>
                            <p class="empty"></p>
                            <p class="param">0%</p>
                            <p class="humidity lvl1"></p>
                            <p class="humidity lvl2"></p>
                            <p class="humidity lvl3"></p>
                            <p class="humidity lvl4"></p>
                            <p class="humidity lvl5"></p>
                            <p class="param">100%</p>
                        </div>
                        <?php if($this->session->userdata('lang')=='english'){?>
                            <div class="manual">Information mark of the sensor<br>
                            to the installed position<br>
                            Please move it.</div>
                        <?php }else{?>
                            <div class="manual">センサーの情報マークを<br>
                            設置した位置まで<br>
                            移動させてください。</div>
                        <?php }?>
                        
                    </div>
                    <!-- Main Map Block -->
                    <div class="map-box d-flex">
                        <p class="zoom-btn plus" id="plus">➕</p>
                        <p class="zoom-btn minus" id="minus">➖</p>
                        <div class="map-layer zoom3 col-md-9" id="map-layer" style="margin:auto;margin-top:100px;">
                            <!-- bg -->
                            <?php echo $mapUrl;?>

                            <?php echo $mapSensors;?>

                        </div>
                        <div class="map-layer zoom3 col-md-3" id="unregistered-layer">
                            <?php echo $unregSensor;?>
                            
                        </div>
                        <!-- Toast message -->
                        <div class="toast" data-delay="3000" id="max-zoom">
                            <div class="toast-header">Zoom</div>
                            <div class="toast-body"> <?=$this->lang->line('zoomMax');?> </div>
                        </div>

                        <div class="toast" data-delay="3000" id="min-zoom">
                            <div class="toast-header">Zoom</div>
                            <div class="toast-body"> <?=$this->lang->line('zoomMin');?> </div>
                        </div>

                    </div>
                    <!-- Main Map Block -->
                    <div class="map-datetime flexlyr">
                        <div class="date-block"><span id="currentday"><?php echo date("Y-m-d");?></span><br>
                            <a href="" class="select"><?=$this->lang->line('1day');?></a>
                            <a href=""><?=$this->lang->line('2day');?></a>
                            <a href=""><?=$this->lang->line('1week');?></a>
                        </div>
                        <div class="seek-block">
                            <?php if($this->session->userdata('lang')=='english'){?>
                                <div class="time-line flexlyr oneday"><p>24hours ago</p><p>12hours ago</p><p>Current</p></div>
                                <div class="seek-bar oneday">
                                    <input type="range" value="24" max="24" step="1" class="time-ctrl" id="time-ctrl">
                                    <progress value="24" max="24" class="time-bar"></progress>
                                </div>
                                <div class="time-line flexlyr twoday"><p>48hours ago</p><p>24hours ago</p><p>Current</p></div>
                                <div class="seek-bar twoday">
                                    <input type="range" value="0" max="48" step="2" class="time-ctrl" id="time-ctrl">
                                    <progress value="0" max="48" class="time-bar"></progress>
                                </div>
                                <div class="time-line flexlyr week"><p>1week</p><p>6days</p><p>5days</p><p>4days</p><p>3days</p><p>2days</p><p>1day</p><p>Current</p></div>
                                <div class="seek-bar week">
                                    <input type="range" value="0" max="168" step="24" class="time-ctrl" id="time-ctrl">
                                    <progress value="0" max="168" class="time-bar"></progress>
                                </div>
                            <?php }else{?>
                                <div class="time-line flexlyr oneday"><p>24時間前</p><p>12時間前</p><p>現在</p></div>
                                <div class="seek-bar oneday">
                                    <input type="range" value="24" max="24" step="1" class="time-ctrl" id="time-ctrl">
                                    <progress value="24" max="24" class="time-bar"></progress>
                                </div>
                                <div class="time-line flexlyr twoday"><p>48時間前</p><p>24時間前</p><p>現在</p></div>
                                <div class="seek-bar twoday">
                                    <input type="range" value="0" max="48" step="2" class="time-ctrl" id="time-ctrl">
                                    <progress value="0" max="48" class="time-bar"></progress>
                                </div>
                                <div class="time-line flexlyr week"><p>1週前</p><p>6日前</p><p>5日前</p><p>4日前</p><p>3日前</p><p>2日前</p><p>1日前</p><p>現在</p></div>
                                <div class="seek-bar week">
                                    <input type="range" value="0" max="168" step="24" class="time-ctrl" id="time-ctrl">
                                    <progress value="0" max="168" class="time-bar"></progress>
                                </div>
                            <?php }?>
                            
                        </div>
                    </div>

                </div>

                <div class="side-bar flexlyr">
                    
                    <!-- trans view type - マップ -->
                    <div class="side-bar-block flexlyr">
                        <p class="side-block-header map-list"><?=$this->lang->line('map');?></p>

                        <div class="map-block">
                            <?php $n=1;
                            if(empty($maps)){?>
                                <a class="map-btn select-on"><?=$this->lang->line('noMap');?></a>
                            <?php }else{
                                foreach($maps as $val){ ?> 
                                    <?php if($n==1) {?>   <a class="map-btn select-on" id="map<?php echo $val['ID'];?>"><?php print_r($val['name']);?></a>
                                    <?php }else {?> <a class="map-btn" id="map<?php echo $val['ID'];?>"><?php print_r($val['name']);?></a>
                                <?php }$n++; } ?>
                            <?php } ?>
                            
                                
                            </ul>
                           
                        </div>
                        <p class="set-view"><a href="<?php echo base_url()?>setting/mappingManagement"><?=$this->lang->line('mapManagement');?></a></p>
                    </div>

                    
                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block sensor-list-block flexlyr" style="width:100%;">
                        <p class="side-block-header sensor-list"><?=$this->lang->line('filter');?></p>

                        <div class="sensor-block">
                            <select name="" id="">
                                <option value="" disabled selected><?=$this->lang->line('sensor');?></option>
                            </select>
                            <?php echo $sensors;?>
                            
                            <p class="set-view"><a href=""><?=$this->lang->line('undo');?></a></p>
                        </div>
                    </div>

                    <!-- trans view type - 表示切り替え -->
                    <div class="side-bar-block flexlyr">
                        <p class="side-block-header column-trans"><?=$this->lang->line('display_switching');?></p>

                        <div class="trans-block">
                            <a class="trans-btn btn-view-01 select-on" id="view1"><?=$this->lang->line('fullView');?></a>
                            <a class="trans-btn btn-view-02" id="view2"><?=$this->lang->line('iconView');?></a>
                            <a class="trans-btn " id="view3"><?=$this->lang->line('heatIndexValue');?></a>
                            <a class="trans-btn " id="view4"><?=$this->lang->line('heatIndexLevel');?></a>
                            <a class="trans-btn " id="view5"><?=$this->lang->line('saturationValue');?></a>
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
        $(document).on('click','.sensor-item li a',function () {
            // $(".trans-btn").removeClass('select-on');
            $(this).parent('li').toggleClass('view-on');
            $("#sensorName").children('li').each(function(index) {
                check=$(this).hasClass('view-on');
                if(check){
                    $("#sensor-0"+(index+1)).css('display','block');
                }else{
                    $("#sensor-0"+(index+1)).css('display','none');
                }
            });
        });
        $(document).on('click','.seek-bar input',function () {
            var mapId="";
            $('.map-block a').each(function() {
                var check=this.classList.contains('select-on');
                if(check){
                    var id=this.getAttribute('id');
                    mapId=id.substring(3);
                } 
            });
            var datetime=$(this).val();
            $.ajax({
                url:"<?php echo base_url()?>MappingMonitoring/dateTimeMap",
                type:'post',
                data:{
                    'mapId':mapId,
                    'datetime':datetime
                },
                success:function(responce){
                    $('#mapSensor').replaceWith(responce);
                    $( "#map-layer" ).draggable();
                   
                }
            })
        });
        // mapping page - sensor select on/off
        $(document).on('click','.date-block a',function (event) {
            // $(".trans-btn").removeClass('select-on');
            event.preventDefault();
            $(this).siblings().removeClass('select');
            $(this).addClass('select');
            var day=$(this).text();
            
            switch(day){
                case '1日':
                    $('.oneday').css('display','flex');
                    $('.twoday').css('display','none');
                    $('.week').css('display','none');
                    $('.seek-bar.oneday input').val(24);
                    $('.seek-bar.oneday progress').val(24);
                    break;           
                case '2日':
                    $('.oneday').css('display','none');
                    $('.twoday').css('display','flex');
                    $('.week').css('display','none');
                    $('.seek-bar.twoday input').val(48);
                    $('.seek-bar.twoday progress').val(48);
                    break;
                case '1週間':
                    $('.oneday').css('display','none');
                    $('.twoday').css('display','none');
                    $('.week').css('display','flex');
                    $('.seek-bar.week input').val(168);
                    $('.seek-bar.week progress').val(168);
                    break;
                default: 
                    break;
            }
        });
        
        // mapping page - map select
    $('.map-btn').click(function () {
        $(".map-btn").removeClass('select-on');
        $(this).toggleClass('select-on');
        var id=$(this).prop('id');
        mapId=id.substring(3);
        $.ajax({
            url:"<?php echo base_url()?>MappingMonitoring/showMapSensor",
            type:'post',
            data:{
                'mapId':mapId
            },
            success:function(responce){
                data=JSON.parse(responce);
                $('#sensorName').replaceWith(data['sensorName']);
                $('#mapSensor').replaceWith(data['mapSensors']);
                $('#mapUrl').replaceWith(data['mapUrl']);
                $('#mapName').replaceWith(data['mapName']);
                $( "#map-layer" ).draggable();
                $( ".sensorGroup" ).draggable().css("position", "absolute");
                $('.oneday').css('display','flex');
                $('.twoday').css('display','none');
                $('.week').css('display','none');
                $('.seek-bar.oneday input').val(24);
                $('.seek-bar.oneday progress').val(24);
            }
            
        })
    });
      // page2 display trans    
      $('.trans-btn').click(function () {
        $(".trans-btn").removeClass('select-on');
        $(this).addClass('select-on');
        var id=$(this).prop('id');
        viewId=id.substring(4);
        hideAll();
        switch(viewId) {
            case '1':
                $('.layer1').css('display','block');
                break;
            case '2':
                $('.layer2').css('display','block');
                break;
            case '3':
                $('.layer3').css('display','block');
                break;
            case '4':
                $('.layer4').css('display','block');
                break;
            case '5':
                $('.layer5').css('display','block');
                break;
            default:
                // code block
        }
    });
    function hideAll(){
        $('.layer1').css('display','none');
        $('.layer2').css('display','none');
        $('.layer3').css('display','none');
        $('.layer4').css('display','none');
        $('.layer5').css('display','none');
    }
    </script>
</body>

</html>