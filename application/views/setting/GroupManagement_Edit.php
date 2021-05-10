<?php

$subuser_name = "";
$subuser_pass = "";
$roleid = array();
if (isset($_GET['M'])) {
    switch ($_GET['M']) {
        case 'Edit':
            $edit_id = (int)$_GET['ids'];
            $f_action = "GroupManagement?M=Edit&ids=" .$edit_id;
            $tname = "productgroup";
            $fieldname = "ID";
            $dstr = $this->session->userdata('user_name');
            $dlogdb = new Dbclass();
            $dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
            $like = '=';
            $userlist = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, trim($edit_id));
            $res = $userlist->fetchAll(\PDO::FETCH_ASSOC);
            $row = $res;
            $group_name = $row[0]['GroupName'];
            break;
        case 'Add':
            $f_action = "GroupManagement?M=Add";
            break;
    }
}
$dlogdb = null;
?>


    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity=
    "sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

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

<body id="pg_index" class="pg_index group-setting">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('group_title');?></h1>
            <div class="content-grid">
                <form id="form1" method="post" action="<?php echo base_url().'setting/'.$f_action;?>">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg"><?=$this->lang->line('group_name');?></p>
                        <p class=" confirm-input">
                            <?php
                            echo '<input type="text" id="GroupName" name="GroupName" value="';
                            if (isset($group_name)) {
                                echo $group_name;
                            }
                            echo '" required>';
                            ?>
                        </p>
                    </div>
                    <button type="submit" class="confirm-btn"><?=$this->lang->line('group_submit');?></button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>










