<?php

$userid = $this->session->userdata('user_id');
$user_name = $this->session->userdata('user_name');
$tname = "receiver";
$rtname = "receivertype";
$fieldname = "ID";

$like = "=";
$dlogdb = new Dbclass();
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
// dataupdate
if (isset($_GET['M'])) {
    switch ($_GET['M']) {
        case 'Edit':
            $edit_id = (int)$_GET['ids'];
            $f_action = "GatewayManagement?M=Edit&ids=" . $edit_id;
            $sensor = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, $edit_id);
            $res = $sensor->fetchAll(\PDO::FETCH_ASSOC);
            break;
        case 'Add':
            $f_action = "GatewayManagement?M=Add";
            break;
    }
}
$stype = $dlogdb->dbAllSelect($dbpdo, $rtname);
$sres = $stype->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($sres);

$dlogdb = null;

?>

    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
     integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

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
</head>

<body id="pg_index" class="pg_index gateway_setting">
    
   
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">ゲートウェイ管理</h1>
            <div class="content-grid">
                <form id="form1" method="post" action="<?php echo base_url().'setting/'.$f_action;?>" onsubmit="return dataCheck();">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg">ゲートウェイ名</p>
                        <p class=" confirm-input">
                        <?php
                        echo '<input id="ReceiverName" name="ReceiverName" type="text" ';
                        if (isset($res)) {
                            echo 'value="' . $res[0]['ReceiverName'] . '"';
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
                        echo ' required>';
                        ?>
                        </p>
                        <p class=" confirm-msg">ゲートウェイの種類</p>
                        <p class=" confirm-input">
                            <select name="TypeID" id="TypeID">
                                <?php
                                if (isset($sres)) {
                                    if (!isset($res)) {
                                        echo '<option value="0">選択</option>';
                                        $sel = "";
                                    }
                                    foreach ($sres as $key => $val) {
                                        if (isset($res)) {
                                            if ($res[0]['TypeID'] == $val['ID']) {
                                                $sel = "selected";
                                            } else {
                                                $sel = "";
                                            }
                                        }
                                        echo '<option value="' . $val['ID'] . '" ';
                                        echo $sel . '>' . $val['TypeName'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
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
                    <button type="submit" class="confirm-btn">ゲートウェイを登録する</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>