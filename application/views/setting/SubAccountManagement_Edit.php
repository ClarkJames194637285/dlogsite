<?php

$subuser_name = "";
$subuser_pass = "";
$roleid = array();
if (isset($_GET['M'])) {
    switch ($_GET['M']) {
        case 'Edit':
            $edit_id = (int)$_GET['ids'];
            $f_action = "SubAccountManagement?M=Edit&ids=" .$edit_id;
            $tname = "users";
            $fieldname = "UserName";
            $user_name = $this->session->userdata('user_name');
            $dlogdb = new Dbclass();
            $dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
            // userdata取得
            $like = ' = ';
            $userlist = $dlogdb->dbSelect($dbpdo, $tname, $like, 'ID', $edit_id);
            $dlogdb = null;
            // List読み込み
            $res = $userlist->fetchAll(\PDO::FETCH_ASSOC);
            $row = $res;
            $subuser_name = $row[0]['UserName'];
            $subuser_pass = openssl_decrypt($row[0]["Password"], $cipher, $key);
            
            for ($i = 5; $i > 0; $i --) {
                $strval = '00000' . (string)decbin($row[0]['RoleID']);
                $toi = $i * -1;
                if ((int)substr($strval, $toi, 1) == 1) {
                    $roleid[$i] = "checked";
                } else {
                    $roleid[$i] = "";
                }
            }
            break;
        case 'Add':
            $f_action = "SubAccountManagement?M=Add";
            for ($i = 5; $i > 0; $i --) {
                $roleid[$i] = "";
            }
            break;
    }
}
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
    <!-- yamaguchi -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/sitejs/page1_3-edit.js"></script>
</head>

<body id="pg_index" class="pg_index sub-account-list">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">サブアカウント管理</h1>
            <div class="content-grid">
                <form id="form1" method="post" action="<?php echo base_url().'setting/'.$f_action;?>">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg">ユーザー名</p>
                        <p class=" confirm-input">
                            <input id="UserName" name="UserName" type="text" value="<?php echo $subuser_name;?>"
                             required>
                        </p>
                        <p class=" confirm-msg">パスワード</p>
                        <p class=" confirm-input">
                            <input id="Password" name="Password" type="text" value="<?php echo $subuser_pass;?>"
                            required>
                        </p>
                        <!-- ダミー -->
                        <input style="display: none" type="checkbox" class="checkboxes">
                        <p class=" confirm-msg">システム設定</p>
                        <p class=" confirm-input">
                            <label class="container1">
                                <input type="checkbox" class="checkboxes" <?php echo $roleid[5];?>>
                                <span class="checkmark"></span>
                            </label>
                        </p>
                        <p class=" confirm-msg">グループ設定</p>
                        <p class=" confirm-input">
                            <label class="container1">
                                <input type="checkbox" class="checkboxes" <?php echo $roleid[4];?>>
                                <span class="checkmark"></span>
                            </label>
                        </p>
                        <p class=" confirm-msg">センサー管理</p>
                        <p class=" confirm-input">
                            <label class="container1">
                                <input type="checkbox" class="checkboxes" <?php echo $roleid[3];?>>
                                <span class="checkmark"></span>
                            </label>
                        </p>
                        <p class=" confirm-msg">マッピング管理</p>
                        <p class=" confirm-input">
                            <label class="container1">
                                <input type="checkbox" class="checkboxes" <?php echo $roleid[2];?>>
                                <span class="checkmark"></span>
                            </label>
                        </p>
                        <p class=" confirm-msg">リスト管理</p>
                        <p class=" confirm-input">
                            <label class="container1">
                                <input type="checkbox" class="checkboxes" <?php echo $roleid[1];?>>
                                <span class="checkmark"></span>
                            </label>
                        </p>
                    </div>
                    <input id="RoleID" name="RoleID" type="hidden">
                    <button class="confirm-btn" onclick="form_submit();" type="submit">サブアカウントを登録する</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>










