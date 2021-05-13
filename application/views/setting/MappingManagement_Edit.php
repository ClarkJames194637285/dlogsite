

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
    <style>
        .alert{
            font-size:20px;
        }
    </style>
</head>

<body id="pg_index" class="pg_index mapping-setting">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('map_title');?></h1>
            <?= ($error = $this->session->flashdata('error')) ? "<div class='alert alert-danger'>{$error}</div>" : "" ?>
            <div class="content-grid">
                <form id="form1" method="post" action="<?php echo base_url()?>setting/mappingManagement/update">
                    <div class="sys-info-block flexlyr">
                        <p class=" confirm-msg"><?=$this->lang->line('map_name');?></p>
                        <p class=" confirm-input">
                            <?php
                            echo '<input type="text" id="mapId" name="mapId" style="display:none;" value="';
                            if (isset($mapId)) {
                                echo $mapId;
                            }
                            echo '" >';
                            echo '<input type="text" id="mapName" name="mapName" value="';
                            if (isset($mapName)) {
                                echo $mapName;
                            }
                            echo '" required>';
                            ?>
                        </p>
                    </div>
                    <button type="submit" class="confirm-btn"><?=$this->lang->line('map_register');?></button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>










