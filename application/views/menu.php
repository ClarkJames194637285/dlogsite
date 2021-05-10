<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="hambugar-icon">
            <img src="<?php echo base_url()?>assets/img/asset_09.png" alt="" id="sidebarCollapse">
        </div>
        <div class="search-box">
            <input type="text" placeholder="<?=$this->lang->line('search');?>" class="searchTxt">
            <img src="<?php echo base_url()?>assets/img/asset_10_1.png" alt="" id="searchIcon">
        </div>
    </div>

    <ul class="list-unstyled components">
        <li class="f-layer menu0">
            <a href="<?php echo base_url()?>home">
                <img src="<?php echo base_url()?>assets/img/asset_03.png" alt=""><p><?=$this->lang->line('home');?></p>
            </a>
        </li>
        <li class="f-layer menu1 collapseItem">
            <!-- <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Home</a> -->
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
                <img src="<?php echo base_url()?>assets/img/asset_04.png" alt=""><p><?=$this->lang->line('setting');?></p>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <?php if($this->role=="admin"){?>
                    <li class="menu1_1">
                        <a href="<?php echo base_url()?>setting/admin/userdelete"><?=$this->lang->line('userdelete');?></a>
                    </li>
                    <li class="menu1_3">
                        <a href="<?php echo base_url()?>setting/admin/notification"><?=$this->lang->line('notification');?></a>
                    </li>
                    <li class="menu1_9">
                        <a href="<?php echo base_url()?>setting/admin/advertisement"><?=$this->lang->line('advertisement');?></a>
                    </li>
                   
                   
                <?php } else{ ?>
                    <?php if($this->user_check==31){?>
                        <li class="menu1_1">
                            <a href="<?php echo base_url()?>setting/userSetting"><?=$this->lang->line('userSetting');?></a>
                        </li>
                    <?php }?>

                    <li class="menu1_2">
                        <a href="<?php echo base_url()?>setting/systemSetting"><?=$this->lang->line('systemSetting');?></a>
                    </li>

                    <?php if($this->user_check==31){?>
                        <li class="menu1_3">
                            <a href="<?php echo base_url()?>setting/subAccountManagement"><?=$this->lang->line('accountManagement');?></a>
                        </li>
                    <?php }?>

                    <li class="menu1_4">
                        <a href="<?php echo base_url()?>setting/groupManagement"><?=$this->lang->line('graphManagement');?></a>
                    </li>

                    <li class="menu1_5">
                        <a href="<?php echo base_url()?>setting/sensorManagement"><?=$this->lang->line('sensorManagement');?></a>
                    </li>

                    <?php if($this->user_check==31){?>
                        <li class="menu1_6">
                            <a href="<?php echo base_url()?>setting/gatewayManagement"><?=$this->lang->line('gatewayManagement');?></a>
                        </li>
                    <?php }?>

                    <li class="menu1_7">
                        <a href="<?php echo base_url()?>setting/mappingManagement"><?=$this->lang->line('mapManagement');?></a>
                    </li>
                    
                    <li class="menu1_8">
                        <a href="<?php echo base_url()?>setting/listManagement"><?=$this->lang->line('listManagement');?></a>
                    </li>
              
                <?php }?>
            </ul>
        </li>
        <?php if($this->role=="admin"){?>
        <li class="f-layer menu5 collapseItem">
            <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
                <img src="<?php echo base_url()?>assets/img/asset_08.png" alt=""><p><?=$this->lang->line('message');?></p>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu2">
                <li class="menu5_1">
                    <a href="<?php echo base_url()?>message/outgoing"><?=$this->lang->line('outgoing');?></a>
                    <!-- <a href="page5_1.php">送信メッセージ（管理者に）</a> -->
                </li>
               
                <li class="menu5_2">
                    <a href="<?php echo base_url()?>message/inbox"><?=$this->lang->line('inboxMessage');?></a>
                </li>
               
                <li class="menu5_3">
                    <a href="<?php echo base_url()?>message/outbox"><?=$this->lang->line('outboxMessage');?></a>
                </li>
                
        </li>
        <?php }?>
        <?php if($this->role!=="admin"){?>
        <li class="f-layer menu6 collapseItem">
            <!-- <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Home</a> -->
            <a href="#pageSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
                <img src="<?php echo base_url()?>assets/img/asset_36.png" alt=""><p><?=$this->lang->line('analysis');?></p>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu1">
                <li class="menu6_1">
                    <a href="<?php echo base_url()?>analysis/report"><?=$this->lang->line('report');?></a>
                </li>
                <li class="menu6_2">
                    <a href="<?php echo base_url()?>analysis/graphAnalysis"><?=$this->lang->line('graphAnalysis');?></a>
                </li>
                <li class="menu6_3">
                    <a href="<?php echo base_url()?>analysis/graphComparison"><?=$this->lang->line('graphComparison');?></a>
                </li>
            </ul>
        </li>
        <li class="f-layer menu2">
            <a href="<?php echo base_url()?>sensorMonitoring">
                <img src="<?php echo base_url()?>assets/img/asset_05.png" alt=""><p><?=$this->lang->line('sensorMonitoring');?></p>
            </a>
        </li>
        <li class="f-layer menu3">
            <a href="<?php echo base_url()?>mappingMonitoring">
                <img src="<?php echo base_url()?>assets/img/asset_06.png" alt=""><p><?=$this->lang->line('mappingMonitoring');?></p>
            </a>
        </li>
        <li class="f-layer menu4">
            <a href="<?php echo base_url()?>alarmHistory">
                <img src="<?php echo base_url()?>assets/img/asset_07.png" alt=""><p><?=$this->lang->line('alarmHistory');?></p>
            </a>
        </li>
        <li class="f-layer menu5 collapseItem">
            <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
                <img src="<?php echo base_url()?>assets/img/asset_08.png" alt=""><p><?=$this->lang->line('message');?></p>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu2">
                <li class="menu5_1">
                    <a href="<?php echo base_url()?>message/outgoing"><?=$this->lang->line('outgoing');?></a>
                    <!-- <a href="page5_1.php">送信メッセージ（管理者に）</a> -->
                </li>
                <!-- <li class="menu5_2">
                    <a href="<?php echo base_url()?>message/inbox"><?=$this->lang->line('inboxMessage');?></a>
                </li> -->
                <li class="menu5_3">
                    <a href="<?php echo base_url()?>message/outbox"><?=$this->lang->line('outboxMessage');?></a>
                </li>
                <!-- <li class="menu5_4">
                    <a href="<?php echo base_url()?>message/transmissionType"><?=$this->lang->line('config');?></a>
                </li> -->
            </ul>
        </li>
        <?php }?>
    </ul>

</nav>