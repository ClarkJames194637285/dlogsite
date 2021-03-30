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
<style>
    .confirm-btn{
        outline: none !important;
        height:3rem;
    }
    .content-grid .msg-type-block{
        margin-top:2rem;
    }
</style>
<body id="pg_index" class="pg_index message-type">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php   $this->load->view('menu');?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('transmission_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div class="msg-type-block flexlyr">
                        <p class="confirm-label"><?=$this->lang->line('invalid');?></p>
                        <p class=" confirm-msg"><?=$this->lang->line('functional_status');?></p>
                        <p class=" confirm-input">
                            <select name="isOpen" id="isOpen">
                                <option value="0" ><?=$this->lang->line('invalid');?></option>
                                <option value="1" selected><?=$this->lang->line('valid');?></option>
                            </select>
                        </p>

                        <p class="confirm-label"><?=$this->lang->line('temperature_alarm');?></p>
                        <p class=" confirm-msg"><?=$this->lang->line('functional_status');?></p>
                        <p class=" confirm-input">
                            <select name="" id="temperature">
                                <option value="0" ><?=$this->lang->line('invalid');?></option>
                                <option value="1" selected><?=$this->lang->line('valid');?></option>
                            </select>
                        </p>

                        <p class="confirm-label"><?=$this->lang->line('humidity_alarm');?></p>
                        <p class=" confirm-msg"><?=$this->lang->line('functional_status');?></p>
                        <p class=" confirm-input">
                            <select name="" id="humidity">
                                <option value="0" ><?=$this->lang->line('invalid');?></option>
                                <option value="1" selected><?=$this->lang->line('valid');?></option>
                            </select>
                        </p>

                        <p class="confirm-label"><?=$this->lang->line('offline_alarm');?></p>
                        <p class=" confirm-msg"><?=$this->lang->line('functional_status');?></p>
                        <p class=" confirm-input">
                            <select name="" id="security">
                                <option value="0" ><?=$this->lang->line('invalid');?></option>
                                <option value="1" selected><?=$this->lang->line('valid');?></option>
                            </select>
                        </p>
                        <button class="confirm-btn" onclick="messageConfig()"><?=$this->lang->line('save');?></button>
                    </div>

                </div>

            </section>

            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>

    <script>
    function messageConfig(){
        var isOpen=$("#isOpen :selected").val();
        var temperature=$('#temperature option:selected').val();
        var humidity=$('#humidity option:selected').val();
        var security=$('#security option:selected').val();
        var data={
            isOpen:isOpen,
            temperature:temperature,
            humidity:humidity,
            security:security
        }
        $.ajax({
                    url: "<?php echo site_url('message/transmissionType/setConfig')?>",
                    type: "post",
                    data:data,
                    success: function (data) {
                        console.log(data);
                        if(data==1){
                            alert('更新に成功しました。');
                            
                        }
                        else alert('更新に失敗しました。');
                    },
                    error: function (error) {
                        console.log(`Error ${error}`);
                    }
                });
    }
    </script>
</body>

</html>