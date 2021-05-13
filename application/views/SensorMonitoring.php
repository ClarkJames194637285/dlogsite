<?php
$ragionid = array(
    $this->lang->line('initial_sisplay') => 0,
    $this->lang->line('heat_index') => 1,
    $this->lang->line('saturation_value') => 2
);
$tname = "productgroup";
$fieldname = "UserId";
$userid = $this->session->userdata('user_id');
$user_name = $this->session->userdata('user_name');
$like = "=";
$dlogdb = new Dbclass();
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));

// dataupdate
if (isset($_GET['M'])) {
    switch ($_GET['M']) {
        case 'Edit':
            $edit_id = (int)$_GET['ids'];
            $f_action = "SensorManagement?M=Edit&ids=" .$edit_id;
            $sensor = $dlogdb->getProductSensor($dbpdo, $userid, $edit_id);
            $res = $sensor->fetchAll(\PDO::FETCH_ASSOC);
            break;
        case 'Add':
            $f_action = "SensorManagement?M=Add";
            break;
    }
}
$pg = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, $userid);
$pgres = $pg->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($pgres);
$allsensor = $dlogdb->getProductSensor($dbpdo, $userid);
$allres = $allsensor->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($allres);
$jdata = '';
// print_r($res);return 0;
if (isset($allres)) {
    foreach ($allres as $key => $val) {
        $data[$key] = array(
            'IMEI' => $val['IMEI'],
            'TypeName' => $val['TypeName'],
            'TypeID' => $val['TypeID'],
            'GroupID' => $val['GroupID']
        );
    }
    $jdata = $data;
}


$dlogdb = null;

?>
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/sitejs/page1_5-edit.js"></script>
    <script>
        var jdata = <?php echo json_encode($jdata);?>;
        var serial_check_str = <?php echo json_encode($this->lang->line('serial_check_str'));?>;
        var temperature_str = <?php echo json_encode($this->lang->line('temperature_str'));?>;
        var temperature_humidity_str = <?php echo json_encode($this->lang->line('temperature_humidity_str'));?>;
        var rf_temperature_str = <?php echo json_encode($this->lang->line('rf_temperature_str'));?>;
        var rf_temperature_humidity_str = <?php echo json_encode($this->lang->line('rf_temperature_humidity_str'));?>;
        var lora_temperature_str = <?php echo json_encode($this->lang->line('lora_temperature_str'));?>;
        var lora_temperature_humidity_str = <?php echo json_encode($this->lang->line('lora_temperature_humidity_str'));?>;
        var old_sensor_str = <?php echo json_encode($this->lang->line('old_sensor_str'));?>;
        var group_name_msg = <?php echo json_encode($this->lang->line('group_name_msg'));?>;
    </script>
<!-- <script>
    function auto_reload()
        {
        window.location = 'http://domain.com/page.php';
        }
</script> -->
<style>
  .modal-header, h4, .close {
    background-color: #3F3F3F;
    color:white !important;
    /* text-align: center; */
    font-size: 30px;
  }
  .modal-footer {
    background-color: #f9f9f9;
  }
  .def_alert{
      height:20px;
      font-size:20px;
  }
  .btn-style{
    margin-top: 25px;
    margin-bottom: -15px;
  }
  form label{
    float: left;
    font-size: 20px;
  }
  form button{
      width:120px;
      margin:10px;
  }
  </style>
</head>
<!-- onload="setTimeout('auto_reload()',3000);" -->
<body id="pg_index" class="pg_index sensor-monitor" >
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php include("menu.php"); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('sensorMonitoring_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    
                    <?php echo $Sensors;?>
                    
                   
                </div>

                <div class="side-bar flexlyr side-fix">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring" class="type1 active"></a></li>
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring/listSensor" class="type2 "></a></li>
                    </ul>
                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter"><?=$this->lang->line('filter');?></p>

                        <div class="srh-block">
                            <select name="option" id="option">
                                <option value="0" selected><?=$this->lang->line('group');?></option>
                                <option value="1" ><?=$this->lang->line('sensor');?></option>
                                <option value="2" ><?=$this->lang->line('battery');?></option>
                                <option value="3" ><?=$this->lang->line('alarm');?></option>
                                <option value="4" ><?=$this->lang->line('offline');?></option>
                                
                            </select>
                            <ul class="filter-type">
                            <?php foreach($Group as $val){ ?> 
                                    <li class="view-on" id="group<?php echo $val['ID'];?>"><a ><?php print_r($val['GroupName']);?></a></li>
                                <?php } ?>
                                
                            </ul>
                            <p class="set-view"><a  onclick="showAll()"><?=$this->lang->line('showAll');?></a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listmanagement"><?=$this->lang->line('rearrange');?></a></p>
                        </div>

                        <div class="srh-block temp-hum">
                            <p class="srh-title"><?=$this->lang->line('sensortype');?></p>
                            <ul>
                                <li class="view-on" id="temp_view"><a ><?=$this->lang->line('temperature');?></a></li>
                                <li class="view-on" id="humidity_view"><a ><?=$this->lang->line('humidity');?></a></li>
                            </ul>
                        </div>

                    </div>

                    <!-- trans view type - 3列目 表示切替 -->
                    <div class="side-bar-block flexlyr">
                        <p class="side-block-header column-trans"><?=$this->lang->line('switching_three_status');?></p>

                        <div class="trans-block">
                            <a class="trans-btn btn-view-01"><?=$this->lang->line('heatIndex');?></a>
                            <a class="trans-btn btn-view-02"><?=$this->lang->line('saturationValue');?></a>
                            <a class="trans-btn select-on"><?=$this->lang->line('initial');?></a>
                        </div>
                    </div>
                </div>
            </section>
            
          
            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>
    <!-- Modal -->
    
    <div class="modal fade" id="newGroup" tabindex="-1"role="dialog" >
        <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" >&times;</button>
            <h4><?=$this->lang->line('sensor_add');?></h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
            <form>
                <div class="form-group">
                    <label for="name"></span> <?=$this->lang->line('sensor_name');?></label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="form-group">
                    <label for="IMEI"></span> <?=$this->lang->line('sensor_number');?></label>
                    <input type="text" class="form-control" id="IMEI" onfocus="dbackup(this);" onBlur="imeiChenge();" required>
                </div>
                <div class="form-group">
                    <label for="TypeName"></span> <?=$this->lang->line('sensor_type');?></label>
                    <p id="TypeName" class="def_alert"></p>
                    <input type="hidden" id="TypeID" name="TypeID">
                </div>
                
                
                <div class="btn-style">
                    <button class="btn btn-warning btn-size" data-dismiss="modal"><?=$this->lang->line('cancel');?></button>
                    <button id="register" class="btn btn-success btn-size"><?=$this->lang->line('add');?></button>
                </div>
            </form>
            </div>
            
        </div>
        
        </div>
    </div> 
    

<script>
    
    setInterval(function(){
        $.get(
            "SensorMonitoring/getTerminalInfo",
            function(responce){
                res=JSON.parse(responce);
                var obj=new Object();
                var status=[];
                $(".srh-block ul li").each(function(value){
                    obj={};
                    obj.id = $(this).prop('id');
                    obj.show = $(this).hasClass('view-on');

                    //convert object to json string
                    var string = JSON.stringify(obj);
                    
                    status.push(string);
                })
                
                $('#content').replaceWith(res);

                for(val in status){
                    str=JSON.parse(status[val])['id'];
                    if(JSON.parse(status[val])['show']){
                        if(str=='temp_view')
                            $(".temperature").css("display","block");
                        if(str=='humidity_view')
                            $(".humidity").css("display","block");
                        else
                            $("."+str).css('display','block');
                    }
                    else{
                        if(str=='temp_view')
                            $(".temperature").css("display","none");
                        if(str=='humidity_view')
                            $(".humidity").css("display","none");
                        else
                            $("."+str).css('display','none');
                    }
                    
                }
                var check=$('#option option:selected').val();
                showWay(check);
                        
               
            }
        );
    },60000);
  
 
    
    function newSensor(groupId){
        $("#newGroup").modal("show");
        // $("#sensorGroup").val(num);
        $("#register").on("click",function(e){
            // e.preventDefault();
            var IMEI=$("#IMEI").val();
            var typeId=$("#TypeID").val();
            var name=$("#name").val();
            if(typeId==0){
                alert('<?=$this->lang->line('old_sensor_str');?>');
                return;
            }
            var that = $(this);
            that.off('click'); // remove handler
            

            $.ajax({
                url: 'sensorMonitoring/registerSensor',
                data: {
                    IMEI:IMEI,
                    groupId:groupId,
                    typeId:typeId,
                    name,name
                },
                dataType: 'json',
                success: function(responce) {
                    $("#newGroup").modal('hide');
                    res=JSON.parse(responce);
                    if(res){
                        alert("<?=$this->lang->line('success');?>");
                        $.get(
                            "SensorMonitoring/getTerminalInfo",
                            function(responce){
                                res=JSON.parse(responce);
                                var obj=new Object();
                                var status=[];
                                $(".srh-block ul li").each(function(value){
                                    obj={};
                                    obj.id = $(this).prop('id');
                                    obj.show = $(this).hasClass('view-on');

                                    //convert object to json string
                                    var string = JSON.stringify(obj);
                                    
                                    status.push(string);
                                })
                                
                                $('#content').replaceWith(res);

                                for(val in status){
                                    str=JSON.parse(status[val])['id'];
                                    if(JSON.parse(status[val])['show']){
                                        if(str=='temp_view')
                                            $(".sensor-01-0").css("display","block");
                                        if(str=='humidity_view')
                                            $(".sensor-02-0").css("display","block");
                                        else
                                            $("."+str).css('display','block');
                                    }
                                    else{
                                        if(str=='temp_view')
                                            $(".sensor-01-0").css("display","none");
                                        if(str=='humidity_view')
                                            $(".sensor-02-0").css("display","none");
                                        else
                                            $("."+str).css('display','none');
                                    }
                                }
                                        
                            
                                }
                            );
                        }
                    // else alert("センソが追加されませんでした。");
                    else {alert("<?=$this->lang->line('double');?>")};
                    
                    that.on('click'); // add handler back after ajax
                },
                type: 'POST'
            });
        })
    }
    $('.srh-block li a').click(function () {
        // $(".trans-btn").removeClass('select-on');
        $(this).parent('li').toggleClass('view-on');
        var check=$(this).parent('li').hasClass('view-on');
        var str=$(this).parent('li')[0].id;
        if(check){
            if(str=='temp_view')
                $(".temperature").css("display","block");
            if(str=='humidity_view')
                $(".humidity").css("display","block");
            else
                $("."+str).css('display','block');
        }
        else{
            if(str=='temp_view')
                $(".temperature").css("display","none");
            if(str=='humidity_view')
                $(".humidity").css("display","none");
            else
                $("."+str).css('display','none');
        }
    });
    // page2 display trans    
    $('.trans-btn').click(function () {
        $(".trans-btn").removeClass('select-on');
        $('.sensor-03-0').css('display','none');
        $('.sensor-04-0').css('display','none');
        $(this).addClass('select-on');
        var heatindex=$(this).hasClass('btn-view-01');
        var saturation=$(this).hasClass('btn-view-02');
        if(heatindex){
            $('.sensor-03-0').css('display','block');
        }else if(saturation){
            $('.sensor-04-0').css('display','block');
        }else{
           showAll();
           
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
            allshow();
            $(".block-title").css('display','none');
            $(".block-sensor-new").css('display','none');
        }else if(check==2){
            allhidden();
            $(".battery").css('display','block');
            $(".battery").parent().siblings().css({"display":""});
        }else if(check==3){
            allhidden();
            $(".warning").css("display",'block');
            $(".warning").parent().siblings().css({"display":""});
        }else if(check==4){
            allhidden();
            $(".offline").css('display','block');
            $(".offline").parent().siblings().css({"display":""});
        }
    }
    function allhidden(){
        $(".block-sensor").css('display','none');
        $(".block-title").css('display','none');
        // $(".block-sensor-new").css('display','none');
        // $(".battery").css('display','none');
        // $(".warning").css("display",'none');
        // $(".offline").css('display','none');
    }
    function allshow(){
        $(".block-sensor").css('display','block');
        $(".block-title").css('display','block');
       
    }
    function showAll(){
        $(".block-sensor").css('display','block');
        $(".block-title").css('display','block');
         $(".group").css('display','block');
        $(".battery").css('display','block');
        $(".warning").css("display",'block');
        $(".offline").css('display','block');
        $("#temp_view").addClass('view-on');
        $("#humidity_view").addClass('view-on');
        $(".filter-type").children().each(function( index ) {
            $(this).addClass('view-on');
        })
    }
      // page2 display trans    
      $('.trans-btn').click(function () {
        $(".trans-btn").removeClass('select-on');
        $(this).addClass('select-on');
    });
 </script>  
</body>

</html>
