<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BSCM</title>
    <!-- viewport setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/base.css" type="text/css">
    <?php if($this->session->userdata('lang')=='english'){?>
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile_en.css" media="screen and (max-width: 768px)" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet_en.css" media="screen and (min-width: 769px)" type="text/css">
    <?php }else{?>
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    <?php }?>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    
<div class="pg-header flexlyr">
    <a href="<?php echo base_url()?>home" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>
    <div class="user-infor flexlyr">
        <?php if($this->role!=="admin"){?>
        <a href="<?php echo base_url()?>alarmhistory" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
            <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
        </a>
        <?php }else{?>
        <a href="<?php echo base_url()?>message/inbox" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
            <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
        </a>
        <?php }?>
        <a href="<?php echo base_url()?>User/logout" class="user-name"><img src="<?php echo base_url()?>assets/img/asset_02.png" alt=""><span><?php echo $user_name;?></span></a>
    </div>
</div>