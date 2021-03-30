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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- img object fit -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>

    <!-- canvasjs -->
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/modules/data.js"></script>
    <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
    
    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
</head>

<script>
    objectFitImages();
</script>

<body id="pg_index" class="pg_index graph">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('graph_analysis_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <!-- <div id="stockChartContainer" class="graph1"></div> -->
                    <div id="container" style="height: 100%; min-width: 100%"></div>
                </div>

                <div class="side-bar flexlyr">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring" class="type1 active"></a></li>
                        <li class="view-type-btn"><a href="<?php echo base_url()?>alarmHistory" class="type2 "></a></li>
                    </ul>
                    
                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter"><?=$this->lang->line('filter');?></p>

                        <div class="srh-block">
                            <select name="" id="">
                                <option value="" disabled selected><?=$this->lang->line('sensor');?></option>
                            </select>
                            <?php echo $sensors;?>
                            <p class="set-view"><a  onclick="showAll()"><?=$this->lang->line('showall');?></a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listManagement"><?=$this->lang->line('rearrange');?></a></p>
                        </div>

                        <div class="srh-block">
                            <p class="srh-title"><?=$this->lang->line('sensortype');?></p>
                            <ul>
                                <li class="view-on" id="temperature"><a ><?=$this->lang->line('temperature');?></a></li>
                                <li class="view-on" id="humidity"><a ><?=$this->lang->line('humidity');?></a></li>
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
        // A $( document ).ready() block.
        $( document ).ready(function() {
            $(document).on ("click", ".srh-block li a", function () {
                 // $(".trans-btn").removeClass('select-on');
                //  $(this).parent('li').toggleClass('view-on');
                
                
                var type=$(this).parent('li')[0].id;
                if((type=="temperature")||(type=="humidity")){
                    var check1=$('#temperature').hasClass('view-on');
                    var check2=$('#humidity').hasClass('view-on');
                    
                    if(check1&&check2){$(this).parent('li').toggleClass('view-on');}
                    $(this).parent('li').siblings().removeClass('view-on');
                    if(!$(this).parent('li').hasClass('view-on')){
                        $(this).parent('li').addClass('view-on')
                    }
                    check1=$('#temperature').hasClass('view-on');
                    check2=$('#humidity').hasClass('view-on');
                    if(check1)type='temperature';
                    if(check2)type='humidity';
                    $.ajax({
                        url:"<?php echo base_url()?>Analysis/GraphAnalysis/checkSensor",
                        type:'post',
                        data:{
                            'type':type
                        },
                        success:function(responce){
                            data=JSON.parse(responce);
                            if(data===false){
                                alert("there is no data!");return;
                            }
                            $("#sensorType").replaceWith(data);
                            $('#sensor1').addClass('view-on');
                            var type=$('#sensor1').children().text();
                            var content='temperature';
                            $.ajax({
                                url:"<?php echo base_url()?>Analysis/GraphAnalysis/getData",
                                type:'post',
                                data:{
                                    'type':type,
                                    'content':content
                                },
                                success:function(responce){
                                    data=JSON.parse(responce);
                                    if(data===false){
                                        alert("there is no data!");return;
                                    }
                                    // Create the chart
                                    Highcharts.stockChart('container', {


                                        rangeSelector: {
                                            selected: 1
                                            },

                                        title: {
                                            text: 'センサーの履歴'
                                            },
                                        
                                        series: [{
                                            name: type,
                                            data: data,
                                            tooltip: {
                                                valueDecimals: 2
                                            }
                                            }]
                                        });
                                }
                                
                            })
                        }
                        
                    })
                }else{
                    $(this).parent('li').siblings().removeClass('view-on');
                    if(!$(this).parent('li').hasClass('view-on')){
                        $(this).parent('li').addClass('view-on')
                    }
                    var check=$('#temperature').hasClass('view-on');
                    var content='';
                    if(check){
                        content='temperature';
                    }else{
                        content='humidity';
                    }
                    $("#sensorType").children().each(function() {
                        var $this = $(this);
                        if($this.hasClass('view-on'))type=$this.children().text();
                    });
                    
                    
                    $.ajax({
                        url:"<?php echo base_url()?>Analysis/GraphAnalysis/getData",
                        type:'post',
                        data:{
                            'type':type,
                            'content':content
                        },
                        success:function(responce){
                            data=JSON.parse(responce);
                            if(data===false){
                                alert("there is no data!");return;
                            }
                            // Create the chart
                            Highcharts.stockChart('container', {


                                rangeSelector: {
                                    selected: 1
                                    },
                                
                                title: {
                                    text: 'センサーの履歴'
                                    },
                                
                                series: [{
                                    name: type,
                                    data: data,
                                    tooltip: {
                                        valueDecimals: 2
                                    }
                                    }]
                                });
                        }
                    })
                }      
            });
            $('#sensor1').addClass('view-on');
            var type=$('#sensor1').children().text();
            var content='temperature';
            $.ajax({
                url:"<?php echo base_url()?>Analysis/GraphAnalysis/getData",
                type:'post',
                data:{
                    'type':type,
                    'content':content
                },
                success:function(responce){
                    data=JSON.parse(responce);
                    if(data===false){
                        alert("there is no data!");return;
                    }
                     // Create the chart
                    Highcharts.stockChart('container', {


                        rangeSelector: {
                            selected: 1
                            },

                        title: {
                            text: 'センサーの履歴'
                            },
                        
                        series: [{
                            name: type,
                            data: data,
                            tooltip: {
                                valueDecimals: 2
                            }
                            }]
                        });
                }
                
            })
        });
        function showAll(){
            $("#temperature").addClass('view-on');
            $("#humidity").addClass('view-on');
            $.ajax({
                url:"<?php echo base_url()?>Analysis/GraphAnalysis/showAll",
                type:'get',
                
                success:function(responce){
                    data=JSON.parse(responce);
                    if(data===false){
                        alert("there is no data!");return;
                    }
                    $("#sensorType").replaceWith(data);
                    $('#sensor1').addClass('view-on');
                    var type=$('#sensor1').children().text();
                    var content='temperature';
                    $.ajax({
                        url:"<?php echo base_url()?>Analysis/GraphAnalysis/getData",
                        type:'post',
                        data:{
                            'type':type,
                            'content':content
                        },
                        success:function(responce){
                            data=JSON.parse(responce);
                            if(data===false){
                                alert("there is no data!");return;
                            }
                            // Create the chart
                            Highcharts.stockChart('container', {


                                rangeSelector: {
                                    selected: 1
                                    },

                                title: {
                                    text: 'センサーの履歴'
                                    },
                                
                                series: [{
                                    name: type,
                                    data: data,
                                    tooltip: {
                                        valueDecimals: 2
                                    }
                                    }]
                                });
                        }
                        
                    })
                }
                
            })
        }
    </script>
</body>

</html>