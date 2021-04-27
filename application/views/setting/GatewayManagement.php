<?php

$userid = $this->session->userdata('user_id');
$user_name = $this->session->userdata('user_name');
$tname = "receiver";
$fieldname = "UserId";
$sortid = "ID";
$like = "=";
$defoulttz = date_default_timezone_get();
$newTime = new \DateTime();
$ctime = new \DateTime($newTime->format('Y-m-d H:i:s'), new \DateTimeZone($defoulttz));

$dlogdb = new Dbclass();
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
// dataupdate
if (isset($_GET['M'])) {
    switch ($_GET['M']) {
        case 'Sort':
            //var_dump($_POST);
            foreach ($_POST as $key => $val) {
                $f_name = explode('_', $key);
                $up_data = array(
                    $f_name[0] => $val
                );
                $update_stmt = $dlogdb->dbUpdate($dbpdo, $tname, $up_data, $sortid, $f_name[1]);
            }
            break;
        case 'Edit':
            $up_data = array(
                'IMEI' => $_POST['IMEI'],
                'ReceiverName' => $_POST['ReceiverName'],
                'TypeID' => $_POST['TypeID'],
                'UserID' => $userid,
                'Description' => $_POST['Description'],
                'CreateTime' => $ctime->format('Y-m-d H:i:s')
            );
            $update_stmt = $dlogdb->dbUpdate($dbpdo, $tname, $up_data, $sortid, $_GET['ids']);
            break;
        case 'Add':
            $insert_data = array(
                'IMEI' => $_POST['IMEI'],
                'ReceiverName' => $_POST['ReceiverName'],
                'TypeID' => $_POST['TypeID'],
                'UserID' => $userid,
                'SortID' => 0,
                'TerminalPassword' => '000000',
                'Description' => $_POST['Description'],
                'CreateTime' => $ctime->format('Y-m-d H:i:s'),
                'isdelete' => 0
            );
            $insertsen = $dlogdb->insertData($dbpdo, $tname, $insert_data);
            break;
        case 'Delete':
            $delete_id = explode(',', $_GET['ids']);
            $up_data = array('isdelete' => 1);
            foreach ($delete_id as $key => $val) {
                $update_stmt = $dlogdb->dbUpdate($dbpdo, $tname, $up_data, $sortid, $val);
            }
            break;
    }
}
$order = "ORDER BY SortID ASC;";
// get receiver
// receiver 取得
$receiverlist = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, $userid, $order);
// List読み込み
$res = $receiverlist->fetchAll(\PDO::FETCH_ASSOC);

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
    <!-- yama insert -->
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>


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

    <link rel="stylesheet" href="<?php echo base_url()?>assets/sitecss/page1_6.css" type="text/css">
    <script type="text/javascript" src="<?php echo base_url()?>assets/sitejs/page1_6.js"></script>

</head>

<body id="pg_index" class="pg_index gateway_setting">
   
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">ゲートウェイ管理</h1>
            <div class="gateway-grid setting-grid">
                <!-- <p class="group-label">グループ</p> -->
                <form id="sort_form" name="sort_form" action="gatewayManagement?M=Sort" method="post">
                    <!-- <div id="columns"> -->
                        <!-- ダミー -->
                        <input style="display: none;" value="" type="checkbox" class="checkboxes">
                        <?php
                        if (isset($res)) {
                            foreach ($res as $key => $val) {
                                echo '<div class="gateway-block flexlyr" '; // style="background-color: #F0F0E0"
                                echo ' id="' . ((int)$key + 1) . '" draggable="true">';
                                echo '<label class="container1">';
                                echo '<input type="checkbox" class="checkboxes" ';
                                echo 'value="' . $val['ID'] . '">';
                                echo '<span class="checkmark"></span>';
                                echo '</label>';
                                echo '<a href="GatewayManagement/edit?M=Edit&ids=';
                                echo $val['ID'] . '" class="edit-btn"></a>';
                                echo '<p class="gateway-name">' . $val['ReceiverName'] . '</p>';
                                echo '<p class="gateway-param1">' . $val['IMEI'] . '</p>';
                                echo '<p class="gateway-param2">' . $val['Description'] . '</p>';
                                echo '<p class="drop-btn"></p>';
                                echo '<input id="SortID_' . $val['ID'] . '" name="SortID_' . $val['ID'];
                                echo '" type="hidden" value="" class="item-order">';
                                echo '</div>';
                            }
                        }
                        ?>
                        <!-- 最終行ドロップ用ダミー -->
                        <div class="gateway-block dummy" style="height:56px"></div>    
                    <!-- </div> -->
                </form>
                <form id="del_form" name="del_form"></form>
                <div class="operation-block flexlyr">
                    <a href="GatewayManagement/edit?M=Add" class="add-btn"></a>
                    <a class="del-btn" onclick="DeleteMulti();"></a>
                </div>
            </div>
            <a href="<?php echo base_url();?>home" class="confirm-btn">ホームに戻る</a>
        </div>
    </div>
    <script type="text/javascript">
    function touchStartEvent(event) {
        var cn = event.target.className;
        if (cn != 'gateway-block') {    
            if (cn == 'checkmark') {
                var id = event.target.previousSibling;
                if (id.checked == true) {
                    id.checked = false;
                } else {
                    id.checked = true;
                }   
            } else if (cn == 'edit-btn') {
                var id = event.target;
                var href = id.getAttribute('href');
                location.href = href;
            }
            return;
        }
        event.preventDefault();
    }

    function touchMoveEvent(event) {
        event.preventDefault();
        
        if (event.target.matches(".gateway-block:not(.dummy)")) {
            
            if (event.target.className == "drop-btn") {
                var draggedElem = event.target.parentNode;
            } else {
                var draggedElem = event.target;
            }
            var touch = event.changedTouches[0];
            draggedElem.style.position = "fixed";
            draggedElem.style.backgroundAttachment = "fixed";
            draggedElem.style.backgroundColor = "skyblue";
            draggedElem.classList.add("dragging");
            draggedElem.style.opacity = 0.5;
            draggedElem.style.MozOpacity = 0.5;
            draggedElem.style.filter = 'alpha(opacity = 50)';
            draggedElem.style.zIndex ++;
            draggedElem.style.top = (touch.pageY - window.pageYOffset - draggedElem.offsetHeight / 2) + "px";
            draggedElem.style.left = (touch.pageX - window.pageXOffset - draggedElem.offsetWidth / 2) + "px";
            var pageY = touch.pageY;
            var pageX = touch.pageX;
            var newParentElem = document.elementFromPoint(touch.pageX - window.pageXOffset,
                 touch.pageY - window.pageYOffset);

            var formid = document.getElementById('sort_form');
            var targetElem = formid.children;
            for (var i = 0; i < targetElem.length; i++) {
                if (targetElem[i].matches(".gateway-block")) {
                    var rect = targetElem[i].getBoundingClientRect();
                    var target_top = rect.top;
                    var target_bottom = rect.bottom;
                    var target_left = rect.left;
                    var target_right = rect.right;
                    if (target_top < pageY && target_bottom > pageY && target_left < pageX && target_right > pageX) {
                        targetElem[i].classList.add("drag-over");
                    } else {
                        targetElem[i].classList.remove("drag-over");
                    }
                }
            }
            for (var i = 0; i < targetElem.length; i++) {
                if (targetElem[i].matches( ".drag-over")) {
                    var rect = targetElem[i].getBoundingClientRect();
                    var target_top = rect.top;
                    var target_bottom = rect.bottom;
                    var target_left = rect.left;
                    var target_right = rect.right;
                    if (target_top > pageY || target_bottom < pageY || target_left > pageX || target_right < pageX) {
                        targetElem[i].classList.remove("drag-over");
                    }
                }
            }
        }
    }

    function touchEndEvent(event) {
        event.preventDefault();
        var droppedElem = event.target;
        droppedElem.style.position = "";
        event.target.style.backgroundAttachment = "";
        event.target.style.backgroundColor = "";
        event.target.style.opacity = 1;
        event.target.style.MozOpacity = 1;
        event.target.classList.remove("drag-over");
        event.target.style.top = "";
        event.target.style.left = "";
        var touch = event.changedTouches[0];
        var newParentElem = document.elementFromPoint(touch.pageX - window.pageXOffset,
         touch.pageY - window.pageYOffset);

        var sel_id = droppedElem.id;
        var to_id = newParentElem.id;
        var toint = parseInt(to_id, 10);
        var selint = parseInt(sel_id, 10);
        var itemorder = document.getElementsByClassName('item-order');
        itemorder.item(selint - 1).value = toint;
        if (to_id != "" && sel_id != "") {
            event.target.style.zIndex --;
            if (toint > selint) {
                for (var i = itemorder.length; i > 0; i--) {
                    if (selint != i) {
                        if (toint >= i && selint < i) {
                            itemorder.item(i - 1).value = i - 1;
                        } else {
                            itemorder.item(i - 1).value = i ;  
                        }
                    }
                }
            } else {
                for (var i = itemorder.length; i > 0; i--) {
                    if (selint != i) {
                        if (toint <= i && selint >= i) {
                            itemorder.item(i - 1).value = i + 1;
                        } else {
                            itemorder.item(i - 1).value = i;
                        }
                    }
                }
            }
            var formid = document.getElementById('sort_form');
            formid.submit();
        }
        var targetelem = droppedElem.parentNode;
        for (var i = 0; i < targetelem.length; i++) {
            targetelem[i].classList.remove("drag-over"); 
            targetelem[i].classList.remove("dragging"); 
        }
    }

    {
        var draggableItems = $(".gateway-block");
        for (var i = 0; i < draggableItems.length; ++i) {
            var item = draggableItems[i];
            item.addEventListener('touchstart', touchStartEvent, false);
            item.addEventListener('touchmove', touchMoveEvent, false);
            item.addEventListener('touchend', touchEndEvent, false); 
        }
    }    
    </script>
</body>

</html>




