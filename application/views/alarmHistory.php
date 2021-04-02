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

    session_start();
    if (!isset($_SESSION['user_name'])) {
        header("Location: index.php");
    }
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
    
    $his_list = $dlogdb->getHistoryData($dbpdo, $user_id);
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
            $('.btn-gtype-1').removeClass('view-on');
            $('.gtype-1').css('display', "none");
        } else {
            $('.btn-gtype-1').addClass('view-on');
            $('.gtype-1').css('display', "");
        }
    }
    function thsyow() {
        if ($('.th-btn').hasClass('view-on')) {
            $('.btn-gtype-2').removeClass('view-on');
            $('.gtype-2').css('display', "none");
        } else {
            $('.btn-gtype-2').addClass('view-on');
            $('.gtype-2').css('display', "");
        }
    }
    function allsyow() {
        var i = 0
        while (document.getElementById('group-' + i)) {
            var target_id = document.getElementById('group-' + i);
            $('.t-btn').addClass('view-on');
            $('.th-btn').addClass('view-on');
            $('.btn-gtype-1').addClass('view-on');
            $('.btn-gtype-2').addClass('view-on');
            $(target_id). addClass('view-on');
            target_id.style = '';
            i ++;
        }
    }
    function groupsyow(no) {
        var id = document.getElementById('group-' + no);
        if (id.style.display == 'none') {
            id.style = '';
            $(id).addClass('view-on');
        } else {
            id.style = 'display: none';
            $(id).removeClass('view-on');
        }
        var i = 0;
        $('.t-btn').removeClass('view-on');
        while (document.getElementById('group-' + i)) {
            var target_id = document.getElementById('group-' + i);
            if ($(target_id).hasClass('gtype-1') && $(target_id).hasClass('view-on')) {
                $('.t-btn').addClass('view-on');
                break;
            }
            i ++;
        }
        var i = 0;
        $('.th-btn').removeClass('view-on');
        while (document.getElementById('group-' + i)) {
            var target_id = document.getElementById('group-' + i);
            if ($(target_id).hasClass('gtype-2') && $(target_id).hasClass('view-on')) {
                $('.th-btn').addClass('view-on');
                break;
            }
            i ++;
        }
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
            <h1 class="page-title">アラーム履歴</h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div class="alarm-header flexlyr">
                        <p>グループ名<br>センサー名</p>
                        <p>測定値 / アラート</p>
                        <p>電池/電波</p>
                        <p>更新日時</p>
                    </div>
                    <?php
                    if (!empty($his_list)) {
                        foreach ($his_list as $key => $val) {
                            if (empty($gloup_lis)) {
                                $temp_arr = array('GroupName' => $val['GroupName']);
                                $gloup_lis = array($temp_arr);
                                if (intval($val['Humidity']) == -1000) {
                                    $type = 1;
                                } else {
                                    $type = 2;
                                }
                                $temp_arr = array('Type' => $type);
                                $type_lis = array($temp_arr);
                            } else {
                                for ($i = 0; $i < count($gloup_lis); $i++) {
                                    if (!array_search($val['GroupName'], array_column($gloup_lis, 'GroupName'))) {
                                        $temp_arr = array('GroupName' => $val['GroupName']);
                                        array_push($gloup_lis, $temp_arr);
                                        if (intval($val['Humidity']) == -1000) {
                                            $type = 1;
                                        } else {
                                            $type = 2;
                                        }
                                        $temp_arr = array('Type' => $type);
                                        array_push($type_lis, $temp_arr);
                                        break;
                                    }
                                }
                            }
                        }
                        foreach ($his_list as $key => $val) {
                            foreach ($his_list as $ckey => $cval) {
                                if ($val['GroupName'] == $cval['GroupName']) {
                                    if (intval($cval['Humidity']) == -1000) {
                                        $humi = 0;
                                    } else {
                                        $humi = floatval($cval['Humidity']);
                                    }
                                    $hd = $method->getHD(floatval($cval['Temperature']), $humi);
                                    $wbgt = $method->getHeatIndex(floatval($cval['Temperature']), $humi);
                                    $time_str = explode(' ', $val['RTC']);
                                    //$wbgt = 32;
                                    switch ($wbgt) {
                                        case ($wbgt < $wbgtcheck[0]):
                                            $bgcol = 'green';
                                            $iconcol = "";
                                            break;
                                        case ($wbgt >= $wbgtcheck[0] && $wbgt <= $wbgtcheck[1]):
                                            $bgcol = 'amber';
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
                                            //$bgcol = 'amber';
                                            $icon = 'low';
                                            break;
                                        case ($bt < $voltcheck[0]):
                                            //$bgcol = 'red';
                                            $icon = 'empty';
                                            break;
                                        default:
                                            break;
                                    }
                                    echo '<div class="alarm-block flexlyr view-on gtype-' . $type_lis[$key]['Type'];
                                    echo '" id="group-' . $key . '">';
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
                                    echo '<div class="alarm-block flexlyr cgroup-' . $key . '" style="display: none">';
                                    if (isset($alarmconfig_list)) {
                                        echo '<table border=1><tbody>';
                                            echo '<tr><td>センサー名:</td><td>' . $val['ProductName'] . '</td></tr>';
                                            echo '<tr><td>センサーID:</td><td>' . $val['IMEI'] . '</td></tr>';
                                            echo '<tr><td>製品名:</td><td>' . $val['Model'] . '</td></tr>';
                                            echo '<tr><td>グループ名:</td><td>' . $val['GroupName'] . '</td></tr>';
                                            echo '<tr><td>メモ:</td><td>' . $val['Description'] . '</td></tr>';
                                        foreach ($alarmconfig_list as $akey => $aval) {
                                            $aobject = explode('|', $aval['AObject']);
                                            $av_tmp = explode('|', $aval['AEvent']);
                                            $aevent1 = explode(',', $av_tmp[0]);
                                            $aevent2 = explode(',', $av_tmp[1]);
                                            if ($aval['AlarmType'] == '1' && $aobject[1] == $val['GroupID']) {
                                                echo '<tr><td>アラーム１（低温）:</td><td>' . $aevent1[0] . '</td></tr>';
                                                echo '<tr><td>アラーム１（高温）:</td><td>' . $aevent1[1] . '</td></tr>';
                                                echo '<tr><td>アラーム２（低温）:</td><td>' . $aevent2[0] . '</td></tr>';
                                                echo '<tr><td>アラーム２（高温）:</td><td>' . $aevent2[1] . '</td></tr>';
                                                break;
                                            }
                                        }
                                        echo '</tbody></table>';
                                    }
                                    echo '</div>';
                                }
                            }
                        }
                    }
                    ?>
                    <!-- <a href="" class="compare-link">比較する</a> -->
                </div>

                <div class="side-bar flexlyr">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="page2.php" class="type1 "></a></li>
                        <li class="view-type-btn"><a href="page4.php" class="type2 active"></a></li>
                    </ul>
                    
                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter">フィルター</p>

                        <div class="srh-block">
                            <select name="" id="">
                                <option value="" disabled selected>グループ</option>
                            </select>
                            <ul class="filter-type">
                                <?php
                                foreach ($gloup_lis as $key => $val) {
                                    echo '<li class="view-on btn-gtype-' . $type_lis[$key]['Type'];
                                    echo '"><a onclick="groupsyow(' . $key . ');">';
                                    echo  $val['GroupName'] . '</a></li>';
                                }
                                ?>
                            </ul>
                            <p class="set-view"><a onclick="allsyow();">全て表示する</a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listmanagement">並び替える</a></p>
                        </div>

                        <div class="srh-block">
                            <p class="srh-title">センサータイプ</p>
                            <ul>
                                <li class="t-btn view-on"><a onclick="tsyow();">温度計</a></li>
                                <li class="th-btn view-on"><a onclick="thsyow();">温湿度計</a></li>
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