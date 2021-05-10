<?php
    /**
     * アラーム履歴
     *
     * page4
     * PHP Version >= 7.3.12
     *
     * @category   Components
     * @package    Dlog Cloud
     * @subpackage Dlog Cloud
     * @author     masa <masa@masa777.mydns.jp>
     * @license    MIT License
     * @link       https://masa777.mydns.jp
     * @since      1.0.0
     */

    $wbgtcheck = array(25, 31);
    $voltcheck = array(3.61, 3.64);
    
    $method = new Methodclass();
    switch ($method->chDevice()) {
        case "mobile":
            /* スマホ用の処理 */
            $device = "mobile";
            break;
        case "tablet":
            /* タブレット用の処理 */
            $device = "tablet";
            break;
        case "pc":
            /* パソコン用の処理 */
            $device = "pc";
            break;
    }
    
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $tname = "product";
    $fieldname = "ID";
    $dlogdb = new Dbclass();
    $dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));

    // foreach($pid as $id){
    //     $list = $dlogdb->getHistoryData($dbpdo, $user_id,$id);
    // }
    // $his_list = $dlogdb->getHistoryData($dbpdo, $user_id);
    //var_dump($his_list);
    $aconf_tn = 'alarmconfig';
    $like = '=';
    $wfname = 'UserID';
    $alarmconfig_res = $dlogdb->dbSelect($dbpdo, $aconf_tn, $like, $wfname, $user_id);
    $alarmconfig_list = $alarmconfig_res->fetchAll(\PDO::FETCH_ASSOC);
    //var_dump($alarmconfig_list);
    $dlogdb = null;

?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .show{
            display:flex !important;
        }
        .showway{
           display: none;
        }
        .alarm-block .label{
            text-align:left;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
    <!-- insert yamaguchi -->
    <script type="text/javascript">

    $(document).on('click','.srh-block li a',function () {
        // $(".trans-btn").removeClass('select-on');
        $(this).parent('li').toggleClass('view-on');
    });
    function cgroup_syow(no) {
        if ($('.cgroup-' + no).hasClass('view-on')) {
            $('.cgroup-' + no).removeClass('view-on');
            $('.cgroup-' + no).css('display', "none");
        } else {
            $('.cgroup-' + no).addClass('view-on');
            $('.cgroup-' + no).css('display', "");
        } 
    }
    function tsyow() {
        if ($('.t-btn').hasClass('view-on')) {
            // $('.btn-gtype-1').removeClass('view-on');
            $('.gtype-1').css('display', "none");
        } else {
            // $('.btn-gtype-1').addClass('view-on');
            $('.gtype-1').css('display', "");
        }
    }
    function thsyow() {
        if ($('.th-btn').hasClass('view-on')) {
            // $('.btn-gtype-2').removeClass('view-on');
            $('.gtype-2').css('display', "none");
        } else {
            // $('.btn-gtype-2').addClass('view-on');
            $('.gtype-2').css('display', "");
        }
    }
    function allsyow() {
        $('.gtype-1').removeClass('showway');
        $('.gtype-2').removeClass('showway');
        $('.gtype-1').css('display', "");
        $('.gtype-2').css('display', "");
        $('.t-btn').addClass('view-on');
        $('.th-btn').addClass('view-on');
        $(".filter-type").children().each(function( index ) {
            $(this).addClass('view-on');
        });
        $(".content-grid").children().each(function( index ) {
            var check=$(this).hasClass('table');
            if(!check){
                $(this).css('display', "");
            }
        });
    }
    function groupsyow(no) {
        var id = document.getElementsByClassName('group-' + no);
        $('.group-' + no).toggleClass('showway');
    }
    var count = 0;
    var countup = setInterval(function (){
        count++;
        document.location.reload();
    },60000);
    </script>
    <style>
        p{
            color:black;
        }
    </style>
</head>

<body id="pg_index" class="pg_index alarm-history">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php include("menu.php"); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('alarmHistory_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div class="alarm-header flexlyr">
                        <p><?=$this->lang->line('group_name');?><br><?=$this->lang->line('sensor_name');?></p>
                        <p><?=$this->lang->line('measured_value');?> / <?=$this->lang->line('alert');?></p>
                        <p><?=$this->lang->line('battery');?>/<?=$this->lang->line('Radio_waves');?></p>
                        <p><?=$this->lang->line('update_time');?></p>
                    </div>
                    <?php
                    if (!empty($his_list)) {
                        foreach ($his_list as $key => $val) {
                            if (empty($gloup_lis)) {
                                $temp_arr = array('GroupName' => $val['GroupName'],'GroupID' => $val['GroupID']);
                                $gloup_lis = array($temp_arr);
                                if (intval($val['Humidity']) == -1000) {
                                    $type = 1;
                                } else {
                                    $type = 2;
                                }
                                $temp_arr = array('Type' => $type);
                                $type_lis = array($temp_arr);
                            } else {
                                $bool=true;
                                foreach($gloup_lis as $cval){
                                    if($val['GroupName']!==$cval['GroupName'])continue;
                                    else{
                                        $bool=false;break;
                                    }
                                }
                               if($bool){
                                    $temp_arr = array('GroupName' => $val['GroupName'],'GroupID' => $val['GroupID']);
                                    array_push($gloup_lis, $temp_arr);
                                    
                               }
                            }
                        }
                            foreach ($his_list as $key => $val) {
                                    if (intval($val['Humidity']) == -1000) {
                                        $humi = 0;
                                    } else {
                                        $humi = floatval($val['Humidity']);
                                    }
                                    if (intval($val['Humidity']) == -1000) {
                                        $type = 1;
                                    } else {
                                        $type = 2;
                                    }
                                    $hd = $method->getHD(floatval($val['Temperature']), $humi);
                                    $wbgt = $method->getHeatIndex(floatval($val['Temperature']), $humi);
                                    $time_str = explode(' ', $val['RTC']);
                                    //$wbgt = 32;
                                    switch ($wbgt) {
                                        case ($wbgt < $wbgtcheck[0]):
                                            $bgcol = 'green';
                                            $iconcol = "";
                                            break;
                                        case ($wbgt >= $wbgtcheck[0] && $wbgt <= $wbgtcheck[1]):
                                            $bgcol = 'amb';
                                            $iconcol = 'amb';
                                            break;
                                        case ($wbgt > $wbgtcheck[1]):
                                            $bgcol = 'red';
                                            $iconcol = 'red';
                                            break;
                                        default:
                                            break;
                                    }
                                    $bt = floatval($val['Voltage']);
                                    switch ($bt) {
                                        case ($bt > $voltcheck[1]):
                                            //$bgcol = 'green';
                                            $icon = "full";
                                            break;
                                        case ($bt >= $voltcheck[0] && $bt <= $voltcheck[1]):
                                            //$bgcol = 'amb';
                                            $icon = 'low';
                                            break;
                                        case ($bt < $voltcheck[0]):
                                            //$bgcol = 'red';
                                            $icon = 'empty';
                                            break;
                                        default:
                                            break;
                                    }
                                    echo '<div class="alarm-block flexlyr view-on gtype-' . $type;
                                    echo ' group-' . $val['GroupID'] . '">';
                                    echo '<div class="label">';
                                    echo '<label class="container1">';
                                    echo '<input type="checkbox">';
                                    echo '<span class="checkmark"></span>';
                                    echo '</label>';
                                    echo '<p>' . $val['GroupName'] . '<br>';
                                    echo $val['TypeName'] . '</p>';
                                    echo '</div>';
                                    echo '<div class="sensor-cell flexlyr">';
                                    echo '<p class="sensor-item temperature-' . $bgcol . '">';
                                    echo $val['Temperature'] . '<span>℃</span></p>';
                                    echo '<p class="sensor-item heat-' . $bgcol . '">';
                                    echo $wbgt . '<span>℃</span></p>';
                                    echo '<p class="sensor-item humidity-' . $bgcol . '">';
                                    echo ($humi * 100) . '<span>%</span></p>';
                                    echo '<p class="sensor-item saturation-' . $bgcol . '">';
                                    echo $hd . '<span>g/m3</span></p>';
                                    echo '</div>';
                                    echo '<div class="battery-cell">';
                                    echo '<p class="battery-box battery-' . $icon . '"></p>';
                                    //echo '<p class="battery-box battery-low"></p>';
                                    //echo '<p class="battery-box battery-empty"></p>';
                                    echo '</div>';
                                    echo '<div class="update-time">';
                                    echo '<p>' . date('Y/m/d', strtotime($val['RTC'])) . '</p>';
                                    echo '<p>' . date('A g:i s', strtotime($val['RTC'])) . '</p>';
                                    echo '</div>';
                                    echo '<img src="'.base_url().'assets/img/asset_24.png" onclick="cgroup_syow(' . $key . ');"';
                                    echo ' class="more-infor">';
                                    echo '</div>';
                                    echo '<div class="alarm-block flexlyr table cgroup-' . $key . '" style="display: none">';
                                    if (isset($alarmconfig_list)) {
                                        echo '<table border=1><tbody>';
                                            echo '<tr><td>'.$this->lang->line('sensor_name').':</td><td>' . $val['ProductName'] . '</td></tr>';
                                            echo '<tr><td>'.$this->lang->line('sensor_Id').':</td><td>' . $val['IMEI'] . '</td></tr>';
                                            echo '<tr><td>'.$this->lang->line('product_name').':</td><td>' . $val['Model'] . '</td></tr>';
                                            echo '<tr><td>'.$this->lang->line('group_name').':</td><td>' . $val['GroupName'] . '</td></tr>';
                                            echo '<tr><td>'.$this->lang->line('note').':</td><td>' . $val['Description'] . '</td></tr>';
                                        foreach ($alarmconfig_list as $akey => $aval) {
                                            $aobject = explode('|', $aval['AObject']);
                                            $av_tmp = explode('|', $aval['AEvent']);
                                            $aevent1 = explode(',', $av_tmp[0]);
                                            $aevent2 = explode(',', $av_tmp[1]);
                                            if ($aval['AlarmType'] == '1' && $aobject[1] == $val['GroupID']) {
                                                echo '<tr><td>'.$this->lang->line('alarm1_low').':</td><td>' . $aevent1[0] . '</td></tr>';
                                                echo '<tr><td>'.$this->lang->line('alarm1_high').':</td><td>' . $aevent1[1] . '</td></tr>';
                                                echo '<tr><td>'.$this->lang->line('alarm2_low').':</td><td>' . $aevent2[0] . '</td></tr>';
                                                echo '<tr><td>'.$this->lang->line('alarm2_high').':</td><td>' . $aevent2[1] . '</td></tr>';
                                                break;
                                            }
                                        }
                                        echo '</tbody></table>';
                                    }
                                    echo '</div>';
                            }
                        }
                    ?>
                    <!-- <a href="" class="compare-link">比較する</a> -->
                </div>

                <div class="side-bar flexlyr">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring" class="type1 "></a></li>
                        <li class="view-type-btn"><a href="<?php echo base_url()?>alarmHistory" class="type2 active"></a></li>
                    </ul>
                    
                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter"><?=$this->lang->line('filter');?></p>

                        <div class="srh-block">
                            <select name="" id="">
                                <option value="" disabled selected><?=$this->lang->line('group');?></option>
                            </select>
                            <ul class="filter-type">
                                <?php
                                foreach ($gloup_lis as $key => $val) {
                                    echo '<li class="view-on btn-gtype-' . $type_lis[$key]['Type'];
                                    echo '"><a onclick="groupsyow(' . $val['GroupID'] . ');">';
                                    echo  $val['GroupName'] . '</a></li>';
                                }
                                ?>
                            </ul>
                            <p class="set-view"><a onclick="allsyow();"><?=$this->lang->line('showAll');?></a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listmanagement"><?=$this->lang->line('rearrange');?></a></p>
                        </div>

                        <div class="srh-block">
                            <p class="srh-title"><?=$this->lang->line('sensortype');?></p>
                            <ul>
                                <li class="t-btn view-on"><a onclick="tsyow();"><?=$this->lang->line('temperature');?></a></li>
                                <li class="th-btn view-on"><a onclick="thsyow();"><?=$this->lang->line('humidity');?></a></li>
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
</body>

</html>