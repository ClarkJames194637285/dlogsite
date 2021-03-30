<?php
$ragionid = array(
    '初期表示' => 0,
    '暑さ指数' => 1,
    '飽差値' => 2
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


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
     integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
     crossorigin="anonymous"></script>

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
    <!-- yamaguchi -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/sitejs/page1_5-edit.js"></script>
    <script>
    var jdata = <?php echo json_encode($jdata);?>;
    </script>
</head>

<body id="pg_index" class="pg_index sensor_setting">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">センサー管理</h1>
            <div class="content-grid">
                <form id="form1" method="post" action="<?php echo base_url().'setting/'.$f_action;?>" onsubmit="return dataCheck();">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg">センサー名</p>
                        <p class=" confirm-input">
                        <?php
                        echo '<input id="ProductName" name="ProductName" type="text" ';
                        if (isset($res)) {
                            echo 'value="' . $res[0]['ProductName'] . '"';
                        }
                        echo ' required>';
                        ?>
                        </p>
                        <p class=" confirm-msg">シリアル番号</p>
                        <p class=" confirm-input">
                        <?php
                        echo '<input id="IMEI" name="IMEI" type="text" ';
                        if (isset($res)) {
                            echo 'value="' . $res[0]['IMEI'] . '"';
                        }
                        echo ' onfocus="dbackup(this);" onBlur="imeiChenge();" required>';
                        ?>
                        </p>
                        <p class=" confirm-msg">センサーの種類</p>
                        <?php
                        echo '<p id="TypeName" class=" confirm-input">';
                        if (isset($res)) {
                            echo $res[0]['TypeName'];
                        }
                        echo '</p>';
                        echo '<input type="hidden" id="TypeID" name="TypeID"';
                        if (isset($res)) {
                            echo 'value="' . $res[0]['TypeID'] . '"';
                        }
                        echo '>';
                        ?>

                        
                        <p class=" confirm-msg">グループ名</p>
                        <p class=" confirm-input">
                            <select name="GroupID" id="GroupID">
                            <?php
                            if (isset($pgres)) {
                                if (!isset($res)) {
                                    echo '<option value="0">選択</option>';
                                    $sel = "";
                                }
                                foreach ($pgres as $key => $val) {
                                    if (isset($res)) {
                                        if ((int)$res[0]['GroupID'] == (int)$val['ID']) {
                                            $sel = "selected";
                                        } else {
                                            $sel = "";
                                        }
                                    }
                                    echo '<option value="' . $val['ID'] . '" ' . $sel . '>';
                                    echo  $val['GroupName'] . '</option>';
                                }
                            }
                            ?>
                            </select>
                        </p>
                        <p class=" confirm-msg">初期表示</p>
                        <p class=" confirm-input">
                            <select name="RegionID" id="RegionID">
                            <?php
                            foreach ($ragionid as $key => $val) {
                                if ((int)$res[0]['RegionID'] == (int)$val) {
                                    $sel = "selected";
                                } else {
                                    $sel = "";
                                }
                                echo '<option value="' . $val . '" ' . $sel . '>';
                                echo $key . '</option>';
                            }
                            ?>
                                
                            </select>
                        </p>
                        <p class=" confirm-msg">データ間隔</p>
                        <p class=" confirm-input">
                            <?php
                            echo '<input id="TerminalDataInterval" name="TerminalDataInterval" type="text" ';
                            if (isset($res)) {
                                echo 'value="' . $res[0]['TerminalDataInterval'] . '"';
                            }
                            echo ' required>';
                            ?>
                        </p>
                        <p class=" confirm-msg">メモ</p>
                        <p class=" confirm-input">
                            <?php
                            echo '<input id="Description" name="Description" type="text" ';
                            if (isset($res)) {
                                echo 'value="' . $res[0]['Description'] . '"';
                            }
                            echo '>';
                            ?>
                        </p>
                    </div>
                    <button type="submit" class="confirm-btn">センサーを登録する</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
