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

    <!-- highstock -->
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
    $( function() {
        $( "#map-layer" ).draggable();
        $( ".senseor-icon" ).draggable().css("position", "absolute");
    } );
</script>

<body id="pg_index" class="pg_index graph-compare">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('graph_comparison_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div id="container" class="graph-compare" style="height: 100%; min-width: 100%"></div>
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
                            <ul class="filter-type" id="sensorType">
                            <?php  $n=1;
                            foreach($sensors as $val){ ?> 
                                    <li class="" id="sensor<?php echo $n;?>"><a ><?php print_r($val['name']);?></a></li>
                                <?php $n++;} ?>
                            </ul>
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
            var seriesOptions = [];
            seriesCounter = 0;
            names =[];
            

        
        // function showAll(){
        //     seriesOptions = []; var n=0;seriesCounter = 0;
        //     names =[];
        //     $(".filter-type").children().each(function( index ) {
        //         $(this).addClass('view-on');
        //         var groupName = $(this).children().text();
        //         names.push(groupName);
        //     })
        //     var content='temperature';
        //     for(i=0;i<names.length;i++){
        //         $.ajax({
        //             url:"<?php echo base_url()?>Analysis/GraphComparison/getData",
        //             type:'post',
        //             data:{
        //                 'type':names[i],
        //                 'content':content
        //             },
        //             success:function(responce){
        //                 data=JSON.parse(responce);
        //                 if(data===false){
        //                     alert("there is no data!");return;
        //                 }
        //                 name=data[0];
        //                 data=data.slice(1);
        //                 seriesOptions[n++] = {
        //                     name:name ,
        //                     data: data
        //                 };

        //                 // As we're loading the data asynchronously, we don't know what order it
        //                 // will arrive. So we keep a counter and create the chart when all the data is loaded.
        //                 seriesCounter += 1;

        //                 if (seriesCounter === names.length) {
        //                     Highcharts.stockChart('container', {

        //                     rangeSelector: {
        //                         selected: 4
        //                     },
                            
        //                     tooltip: {
        //                         pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
        //                         valueDecimals: 2,
        //                         split: true
        //                     },
        //                     series: seriesOptions
        //                     });
        //                 }
        //             }
                    
        //         })
        //     }
        // }
        // A $( document ).ready() block.
        $( document ).ready(function() {
            $(document).on ("click", ".srh-block li a", function () {
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
                    seriesOptions = []; var n=0;seriesCounter = 0;
                    names =[];
                    $(this).parent('li').toggleClass('view-on');
                   
                    $("#sensorType").children().each(function() {
                        var $this = $(this);
                        if($this.hasClass('view-on'))names.push($this.children().text());
                    });
                    if(names.length<1){
                        Highcharts.stockChart('container', {


                            rangeSelector: {
                                selected: 1
                                },

                            title: {
                                text: 'センサーの比較'
                                },

                            series: [{
                                name: '',
                                data: {},
                                tooltip: {
                                    valueDecimals: 2
                                }
                                }]
                            });
                    }else{
                        for(i=0;i<names.length;i++){
                        $.ajax({
                            url:"<?php echo base_url()?>Analysis/GraphComparison/getData",
                            type:'post',
                            data:{
                                'type':names[i],
                                'content':content
                            },
                            success:function(responce){
                                data=JSON.parse(responce);
                                if(data===false){
                                    alert("there is no data!");return;
                                }
                                name=data[0];
                                data=data.slice(1);
                                seriesOptions[n++] = {
                                    name:name ,
                                    data: data
                                };

                                // As we're loading the data asynchronously, we don't know what order it
                                // will arrive. So we keep a counter and create the chart when all the data is loaded.
                                seriesCounter += 1;

                                if (seriesCounter === names.length) {
                                    Highcharts.stockChart('container', {

                                    rangeSelector: {
                                        selected: 4
                                    },
                                    
                                    tooltip: {
                                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
                                        valueDecimals: 2,
                                        split: true
                                    },
                                    series: seriesOptions
                                    });
                                }
                            }
                            
                        })
                    }
                    }    
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
                            text: 'センサーの比較'
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