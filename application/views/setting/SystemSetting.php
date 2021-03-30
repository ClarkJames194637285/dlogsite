<?php

$user_name = $this->session->userdata('user_name');
$dlogdb = new Dbclass;
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
// ユーザー情報取得
$tname = "users";
$fieldname = "UserName";
$like = " LIKE ";
$dstr = $user_name;
$userlist = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, $dstr);
// List読み込み
$res = $userlist->fetchAll(\PDO::FETCH_ASSOC);
$urow = $res[0];
$defoulttz = $urow['TimeZone'];
$login_id = (int)$urow["ID"];
$usersetlist = $dlogdb->getUserSetting($dbpdo, $login_id);
$res = $usersetlist->fetchAll(\PDO::FETCH_ASSOC);
$userset_res = $res[0];
$dlogdb = null;
// timezone 連想配列
$timezone = array(
    '+12'   => 12,
    '+11'   => 11,
    '+10.5' => 10.5,
    '+10'   => 10,
    '+9.5'  => 9.5,
    'タイムゾーン +9（日本)'    => 9,
    '+8'    => 8,
    '+7'    => 7,
    '+6.5'  => 6.5,
    '+6'    => 6,
    '+5.5'  => 5.5,
    '+5'    => 5,
    '+4'    => 4,
    '+3'    => 3,
    '+2'    => 2,
    '+1'    => 1,
    '0'     => 0,
    '-1'    => -1,
    '-2'    => -2,
    '-3'    => -3,
    '-4'    => -4,
    '-5'    => -5,
    '-6'    => -6,
    '-7'    => -7,
    '-8'    => -8,
    '-9'    => -9,
    '-10'   => -10,
    '-11'   => -11,
    '-12'   => -12
);


?>

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

<body id="pg_index" class="pg_index system_setting">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <form id="form1" method="post" action="SystemSetting/confirm">
                <h1 class="page-title">システム設定</h1>
                <div class="content-grid">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg">システム時間</p>
                        <p class=" confirm-input">
                            <select name="systime" id="systime">
                                <!-- サーバー時間orPC時間選択 DB 不明 yama-->
                                <?php
                                if ($userset_res['IsSearchRTC'] == 0) {
                                    $val[0] = "selected";
                                    $val[1] = "";
                                } else {
                                    $val[0] = "";
                                    $val[1] = "selected";
                                }
                                echo '<option value="0" ' . $val[0] . '>サーバー時間</option>';
                                echo '<option value="1" ' . $val[1] . '>PC時間</option>';
                                ?>
                            </select>
                        </p>
                        <p class=" confirm-msg">温度単位</p>
                        <p class=" confirm-input">
                            <select name="corf" id="corf">
                            <?php
                            if ($userset_res['TemperatureUnit'] == 0) {
                                $val[0] = "selected";
                                $val[1] = "";
                            } else {
                                $val[0] = "";
                                $val[1] = "selected";
                            }
                            echo '<option value="0" ' . $val[0] . '>℃（摂氏）</option>';
                                // 華氏は必要有無確認 オリジナルでは摂氏のみ yama
                            echo '<option value="1" ' . $val[1] . '>℉（華氏）</option>'
                            ?>
                            </select>
                        </p>
                        <p class=" confirm-msg">タイムゾーン</p>
                        <p class=" confirm-input">
                            <select name="timezone" id="timezone">
                                <?php
                                
                                foreach ($timezone as $key => $val) {
                                    if ((int)$defoulttz == (int)$val) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo '<option value="' . $val . '" '. $selected .'>' . $key . '</option>';
                                }
                                
                                ?>
                            </select>
                        </p>
                        <p class=" confirm-input">   
                            <input type="hidden" id="login_id" name="login_id" value="<?php echo (int)$login_id;?>"/>
                            <input type="hidden" id="setting_id" name="setting_id" 
                            value="<?php echo (int)$userset_res['ID'];?>"/>
                        </p>
                    </div>
                    <button class="confirm-btn">設定を保存する</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>




