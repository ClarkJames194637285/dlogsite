<?php

$method = new Methodclass();
switch ($method->chDevice()) {
    case "mobile":
        /* スマホ用の処理 */
        $device = "mobile";
        $modal_width = "95%";
        $info_class = "cell1";
        break;
    case "tablet":
        /* タブレット用の処理 */
        $device = "tablet";
        $modal_width = "80%";
        $info_class = "cell1";
        break;
    case "pc":
        /* パソコン用の処理 */
        $device = "pc";
        $modal_width = "50%";
        $info_class = "cell3";
        break;
}

$file_dir = base_url()."assets/res_data/report/";
$user_id = $this->session->userdata('user_id');
$user_name = $this->session->userdata('user_name');
$tname = "product";
$fieldname = "ID";
$dlogdb = new Dbclass();
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
$t_n = 'report';
$path = FCPATH.'assets/res_data/report/';
$gpath = FCPATH.'assets/res_data/images/';
$file_name = "";
$pdf_file = "";
$csv_file = "";
$rpcls = new Reportclass();
if (!empty($_POST) && isset($_GET['M'])) {
    /* echo $_POST['txtBeginTime'] . '<br>';
    echo $_POST['txtEndTime'] . '<br>'; */
    $t = explode('T', $_POST['txtBeginTime']);
    $b_time = $t[0] . ' ' . $t[1];
    $tf1 = explode('-', $t[0]);
    $tf2 = explode(':', $t[1]);
    $t = explode('T', $_POST['txtEndTime']);
    $e_time = $t[0] . ' ' . $t[1];
    $tf3 = explode('-', $t[0]);
    $tf4 = explode(':', $t[1]);
    $pid = $_GET['ids'];
    $csvhed_list = $dlogdb->reportCsvHedData($dbpdo, $b_time, $e_time, $pid);
    $csvdata_list = $dlogdb->reportCsvData($dbpdo, $b_time, $e_time, $pid);
    
    $insert_row = $dlogdb->nextRowNo($dbpdo, $t_n);
    $file_name = $csvhed_list[0]['IMEI'] . '-';
    $file_name .= $tf1[0] . $tf1[1] . $tf1[2] . $tf2[0] . $tf2[1] . '00-';
    $file_name .= $tf3[0] . $tf3[1] . $tf3[2] . $tf4[0] . $tf4[1] . '00-';
    $file_name .= $insert_row[0]['nextid'];
    $ids = $insert_row[0]['nextid'];
    $csvdata = $rpcls->setReportCsvdata($csvhed_list[0], $csvdata_list);
    if (is_writable($path)) {
        $file_handle = fopen($path . $file_name . '.csv', "w");
        foreach ($csvdata as $val) {
            fwrite($file_handle, mb_convert_encoding($val, 'SJIS', 'UTF-8') . "\n");
        }
        fclose($file_handle);
    } else {
        echo "ファイルを作成することができません。";
    }
    $rpcls->makeJsonDataFile($csvdata_list);
    $report_insert_data = $rpcls->reportMakeData($csvhed_list, $csvdata_list, $ids, $file_name);
    $dlogdb->insertData($dbpdo, $t_n, $report_insert_data);
} elseif (isset($_GET['Gu'])) {
    $orientation = 'Portrait'; // 用紙の向き縦
    $unit = 'mm'; // 単位
    $format = 'A4'; // 用紙フォーマット
    $unicode = true; // ドキュメントテキストがUnicodeの場合にTRUEとする
    $encoding = 'UTF-8'; // 文字コード
    $diskcache = false; // ディスクキャッシュを使うかどうか
    $tcpdf = new \TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache);
    $tcpdf->SetFont("kozgopromedium", "", 10);
    $tcpdf->SetAutoPageBreak(true, 5);
    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(true);
    $tcpdf->setFooterMargin(10);
    $tcpdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $url = $_GET['Gu'];
    $file_name = $_GET['F'];
    $img = file_get_contents($url);
    $imginfo = pathinfo($url);
    $img_name = $imginfo['basename'];
    file_put_contents($gpath . $img_name, $img);
    $gurl = $gpath . $img_name;
    $rpcls->makePdfFile($tcpdf, $gurl, $file_name);
    if (DIRECTORY_SEPARATOR == '\\') {
        $tcpdf->Output(FCPATH . 'assets\\res_data\\report\\' . $file_name . '.pdf', 'F'); // pdf表示設定
    } else {
        $tcpdf->Output(FCPATH . 'assets/res_data/report/' . $file_name . '.pdf', 'F'); // pdf表示設定
    }
    
    $pdf_file = $file_dir . $file_name . '.pdf';
    $csv_file = $file_dir . $file_name . '.csv';
}
$file_name = json_encode($file_name, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$reportlist = $dlogdb->getReport($dbpdo, $user_id);
//var_dump($reportlist);

$rpcls = null;
$tcpdf = null;
$dlogdb = null;

?>

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
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)"
    type="text/css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)"
    type="text/css">

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)"
    type="text/css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)"
    type="text/css">

<!-- jquery dragable -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- img object fit -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>

<!-- canvasjs -->
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

<!-- custom jscript -->
<script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>

<style>
    @keyframes post_process {
        0% {
            top: -100%
        }

        100% {
            top: 0%
        }
    }

    @keyframes modalClose {
        0% {
            top: 50%
        }

        100% {
            top: 100%
        }
    }

    .post_process {
        display: none;
        position: fixed;
        z-index: 15;
        top: 30%;
        left: 50%;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        -webkit-transform: translate(-50%, -10%);
        transform: translate(-50%, -10%);
        color: black;
        font-size: 1.3rem;
        border: 0.3rem solid #fff;
    }

    .post_conform {
        display: none;
        position: fixed;
        z-index: 15;
        top: 30%;
        left: 50%;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        -webkit-transform: translate(-50%, -10%);
        transform: translate(-50%, -10%);
        color: black;
        font-size: 1.3rem;
        border: 0.3rem solid #fff;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(152, 152, 152, 0.7);
        width: 100%;
        height: 100%;
        z-index: 10;
    }

    .close {
        margin-right: 3px;
        width: 24px;
        height: 24px;
        background: url("<?php echo base_url()?>assets/img/asset_09_1.png");
    }

    h3 {
        font-size: 24px;
    }

    h5 {
        font-size: 16px;
    }

    .controls {
        font-size: 16px;
    }

    .btn {
        font-size: 14px;
    }

    input[type="datetime-local"] {
        width: 250px;
        height: 32px;
    }
</style>
</head>
<script>
    objectFitImages();
    $(function () {
        $("#map-layer").draggable();
        $(".senseor-icon").draggable().css("position", "absolute");
    });
    function childShow(id) {
        child_id = document.getElementById('child_' + id);
        if (child_id.style.display == "none") {
            child_id.style.display = "";
        } else {
            child_id.style.display = "none"
        }

    };
    function tsyow() {
        if ($('.t-btn').hasClass('view-on')) {
            // $('.btn-gtype-1').removeClass('view-on');
            $('.gtype-1').css('display', "none");
        } else {
            // $('.btn-gtype-1').addClass('view-on');
            $('.gtype-1').css('display', "");
        }
    }
    function thsyow() {
        if ($('.th-btn').hasClass('view-on')) {
            // $('.btn-gtype-2').removeClass('view-on');
            $('.gtype-2').css('display', "none");
        } else {
            // $('.btn-gtype-2').addClass('view-on');
            $('.gtype-2').css('display', "");
        }
    }
    function allsyow() {
        $('.t-btn').addClass('view-on');
        $('.th-btn').addClass('view-on');
        $('.btn-gtype-1').addClass('view-on');
        $('.btn-gtype-2').addClass('view-on');
        $(".filter-type").children().each(function( index ) {
            $(this).addClass('view-on');
        })
        $(".report_grid").children().each(function( index ) {
            var check=$(this).hasClass('grid-content');
            if(check){
                $(this).addClass('view-on');
                $(this).css('display', "");
            }
        })
        $(".content-grid").children().each(function( index ) {
            var check=$(this).hasClass('table');
            if(!check){
                $(this).addClass('view-on');
            }
        });
    }
    function groupsyow(no) {
        $(".report_grid").children().each(function( index ) {
            var check=$(this).hasClass('group-' + no);
            if(check){
                var checkbtn=$(this).css('display');
                if(checkbtn=='flex'){
                    $(this).css('display','none');
                }
                else{
                    $(this).css('display','flex');
                }
            }
        });
  
    }
    // insert js
    $(document).on('click', '.post_window', function () {
        //背景をスクロールできないように　&　スクロール場所を維持
        scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });
        // モーダルウィンドウを開く
        $('.post_process').fadeIn();
        $('.modal').fadeIn();
    });

    $(document).on('click', '.close', function () {
        //背景をスクロールできないように　&　スクロール場所を維持
        scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });
        // モーダルウィンドウを開く
        $('.post_conform').fadeOut();
        $('.modal').fadeOut();
        location.href = "<?php echo base_url()?>analysis/report";
    });

    var $actionUrl = "";
    function Generate(id, name) {
        $actionUrl = "<?php echo base_url()?>analysis/report?M=Make_report&ids=" + id;
    };
    function Datacheck() {
        var begintime = document.getElementById('txtBeginTime');
        var endtime = document.getElementById('txtEndTime');
        var form = document.getElementById('make_report');
        form.action = "";
        if (begintime.value == null || begintime.value == "") {
            alert("From 日付時間を選択してください。");
            return false;
        } else if (endtime.value == null || endtime.value == "") {
            alert("To 日付時間を選択してください。");
            return false;
        }
        form.action = $actionUrl;
        form.submit();
        return true;
    };
</script>
<body id="pg_index" class="pg_index report">
    <div class="wrapper">


        <!-- Sidebar  -->

        <?php $this->load->view('menu'); ?>
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('report_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    <div class="report_grid">
                        <div class="grid-header flexlyr">
                            <div class="hd-cell cell1"><?=$this->lang->line('sensor_name');?></div>
                            <div class="hd-cell cell2"><?=$this->lang->line('serial_number');?></div>
                            <div class="hd-cell cell3"><?=$this->lang->line('sensor_type');?></div>
                            <div class="hd-cell cell4"><?=$this->lang->line('temper_str');?></div>
                            <div class="hd-cell cell5"><?=$this->lang->line('humi_str');?></div>
                            <div class="hd-cell cell6"><?=$this->lang->line('status_str');?></div>
                            <div class="hd-cell cell7"><?=$this->lang->line('update_str');?></div>
                            <div class="hd-cell cell8"><?=$this->lang->line('operation_str');?></div>
                        </div>
                        <?php
                            $tlist = "";
                            $thlist = "";
                            foreach ($reportlist as $key => $val) {
                                $list = $val;
                                if(empty($list))continue;
                                foreach ($list as $dkey => $dval) {
                                    //var_dump($dval);
                                    if ($dkey == 0) {
                                        $type = 1;
                                        if (floatval($dval['T_Average']) == -1000) {
                                            $T_Averege = '--';
                                        } else {
                                            $T_Averege = $dval['T_Average'];
                                        }
                                        if (floatval($dval['H_Average']) == -1000) {
                                            $H_Averege = '--';
                                            $th[$key] = "group-" . $dval['ProductID'];
                                            $tlist .= $th[$key] . ",";
                                        } else {
                                            $H_Averege = $dval['H_Average'] * 100;
                                            $th[$key] = "group-" . $dval['ProductID'];
                                            $thlist .= $th[$key] . ",";
                                            $type = 2;
                                        }
                                        if (empty($type_lis)) {
                                            $temp_arr = array('Type' => $type);
                                            $type_lis = array($temp_arr);
                                        } else {
                                            $temp_arr = array('Type' => $type);
                                            array_push($type_lis, $temp_arr);
                                        }
                                        if ($dval['Status'] == 2) {
                                            $status = 'normal';
                                            $s_text = $this->lang->line('normal_str');
                                        } else {
                                            $status = 'danger';
                                            $s_text = $this->lang->line('offline_str');
                                        }
                                        echo '<div class="grid-content flexlyr view-on gtype-' . $dval['GroupID'];
                                        echo ' group-' . $dval['GroupID'] . '">';
                                        echo '<div class="ct-cell cell1">' . $dval['ProductName'] . '</div>';
                                        echo '<div class="ct-cell cell2">' . $dval['IMEI'] . '</div>';
                                        echo '<div class="ct-cell cell3">' . $dval['TypeName'] . '</div>';
                                        echo '<div class="ct-cell cell4">';
                                        echo $T_Averege . '℃</div>';
                                        echo '<div class="ct-cell cell5">';
                                        echo $H_Averege . '%</div>';
                                        echo '<div class="ct-cell cell6"><span class="';
                                        echo $status . '">' . $s_text . '</span></div>';
                                        echo '<div class="ct-cell cell7">';
                                        echo $dval['CreateTime'] . '</div>';
                                        echo '<div class="ct-cell cell8">';
                                        echo '<a class="post_window" onclick="Generate(' . $dval['ProductID'] . ', ';
                                        echo '`' . $dval['ProductName'] . '`);">';
                                        echo '<img src="'.base_url().'assets/img/asset_37.png" alt="" title="'.$this->lang->line('makepdf_str').'">';
                                        echo '</a>';
                                        echo '<a onclick="childShow(' . $dval['ProductID'] . ');">';
                                        echo '<img src="'.base_url().'assets/img/asset_24.png" alt="" title="'.$this->lang->line('viewreport_str').'"></a>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div id="child_' . $dval['ProductID'];
                                        echo '" name="child_' . $dval['ProductID'];
                                        echo '" class="" style="display:none">';
                                        // レポート表示用内包処理
                                        echo '<div class="grid-content flexlyr">';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo ($dkey + 1) . ','.$this->lang->line('datetime_str').':' . $dval['CreateTime'];
                                        echo '</div>';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo '<a class="bg-red" href="' . $file_dir . $dval['FileName'] . '.pdf" ';
                                        echo 'target="_blank">'.$this->lang->line('pdf_dl_str').'</a>';
                                        echo '</div>';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo '<a class="bg-green" href="' . $file_dir . $dval['FileName'] . '.csv" ';
                                        echo 'target="_blank">'.$this->lang->line('csv_dl_str').'</a>';
                                        echo '</div></div>';
                                    } else {
                                        // レポート表示用内包処理
                                        echo '<div class="grid-content flexlyr">';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo ($dkey + 1) . ','.$this->lang->line('datetime_str').':' . $dval['CreateTime'];
                                        echo '</div>';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo '<a class="bg-red" href="' . $file_dir . $dval['FileName'] . '.pdf" ';
                                        echo 'target="_blank">'.$this->lang->line('pdf_dl_str').'</a>';
                                        echo '</div>';
                                        echo '<div class="ct-cell ' . $info_class . '">';
                                        echo '<a class="bg-green" href="' . $file_dir . $dval['FileName'] . '.csv" ';
                                        echo 'target="_blank">'.$this->lang->line('csv_dl_str').'</a>';
                                        echo '</div></div>';
                                    }
                                }
                                echo '</div>';
                            }
                            ?>
                    </div>
                </div>
                <div class="side-bar flexlyr">
                    <ul class="view-type-btn-grid">
                        <li class="view-type-btn"><a href="<?php echo base_url()?>sensorMonitoring"
                                class="type1 active"></a></li>
                        <li class="view-type-btn"><a href="<?php echo base_url()?>alarmHistory" class="type2 "></a></li>
                    </ul>

                    <!-- search filter type - フィルター -->
                    <div class="side-bar-block srh-filter-block flexlyr">
                        <p class="side-block-header srh-filter"><?=$this->lang->line('filter_str');?></p>

                        <div class="srh-block">
                            <select name="" id="">
                                <option value="" disabled selected><?=$this->lang->line('grupu_str');?></option>
                            </select>
                            <ul class="filter-type">
                                <?php
                                $checklist=[];
                                    foreach ($reportlist as $key => $val) {
                                        $list = $val;
                                        foreach ($list as $dkey => $dval) {
                                            $check=true;
                                            if(empty($checklist)){
                                                $check=false;
                                                $data=array(
                                                    'GroupName'=>$dval['GroupName'],
                                                    'GroupID'=>$dval['GroupID']
                                                );
                                                array_push($checklist,$data);
                                            }
                                            foreach($checklist as $checkkey => $checkval){
                                                if($dval['GroupName']==$checkval['GroupName'])$check=false;
                                            }
                                            if ($check) {
                                                $data=array(
                                                    'GroupName'=>$dval['GroupName'],
                                                    'GroupID'=>$dval['GroupID']
                                                );
                                                array_push($checklist,$data);
                                            }
                                           
                                        }
                                    }
                                    foreach($checklist as $val){
                                        echo '<li class="view-on btn-gtype-' . $val['GroupID'] . '">';
                                        echo '<a onclick="groupsyow(`' . $val['GroupID'] . '`);">';
                                        echo $val['GroupName'] . '</a></li>';
                                    }
                                    ?>
                            </ul>
                            <p class="set-view"><a onclick="allsyow();"><?=$this->lang->line('show_all_str');?></a></p>
                            <p class="set-view"><a href="<?php echo base_url()?>setting/listmanagement"><?=$this->lang->line('sort_str');?></a></p>
                        </div>

                        <div class="srh-block">
                            <p class="srh-title"><?=$this->lang->line('sensortype_str');?></p>
                            <ul>
                                <li class="t-btn view-on">
                                    <?php
                                        echo '<a';
                                        echo ' onclick="tsyow();"';
                                        echo '>'.$this->lang->line('thermometer_str').'</a>';
                                        ?>
                                </li>
                                <li class="th-btn view-on">
                                    <?php
                                        echo '<a';
                                        echo ' onclick="thsyow();"';
                                        echo '>'.$this->lang->line('thermo_hygrometer_str').'</a>';
                                    ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </section>
            <div class="modal"></div>
            <?php
                    echo '<div id="" class="post_process" role="dialog" style="width: ' . $modal_width . ';">';
                    ?>
            <form id="make_report" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 id="dialogGenerateName"><?=$this->lang->line('makepdf_str');?> 
                    <!-- <font style="font-size:16px">[長距離温度センサー]</font> -->
                    </h3>
                    <button class="close"></button>
                </div>
                <div class="modal-body">
                    <div class="sys-info-block flexlyr">
                        <h5 class="form-section"><?=$this->lang->line('datetime_set_str');?></h5>
                    </div>
                    <div class="sys-info-block flexlyr">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="controls"><?=$this->lang->line('datetime_from_str');?></div>
                                <div class="controls">
                                    <div class="confirm-input">
                                        <input id="txtBeginTime" name="txtBeginTime" class="form_datetime"
                                            type="datetime-local" size="24">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="controls"><?=$this->lang->line('datetime_until_str');?></div>
                                <div class="controls">
                                    <div class="confirm-input">
                                        <input id="txtEndTime" name="txtEndTime" class="form_datetime"
                                            type="datetime-local" size="16">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnSubmit" type="button" onclick="Datacheck();"
                        class="btn btn-primary bg-green"><?=$this->lang->line('verification_str');?></button>
                    <button id="btnCancel" class="btn btn-primary modal_close"><?=$this->lang->line('cancel_str');?></button>
                </div>
            </form>
        </div>
        <!-- post後pdf作成　完了したらダウンロード用のmodal表示phpで作成する。 -->
        <div id="pconf" class="post_conform" role="dialog">
            <div class="modal-header">
                <h3 id="dialogGeneratingName"><?=$this->lang->line('makepdf_str');?></h3>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="Layout_Generating" class="row-fluid" style="display: none;">
                    <img src="<?php echo base_url()?>assets/img/ajax-loading.gif"><br><br>
                    <label class="Tips"><?=$this->lang->line('waiting_str');?></label><br><br>
                    <label id="labTime"></label>
                </div>
                <!-- 資料作成後に表示させる。 -->
                <div id="Layout_Download" class="row-fluid" style="display: none;">
                    <img src="<?php echo base_url()?>assets/img/success.gif"><br><br>
                    <p class="Tips"><?=$this->lang->line('successfully_str');?></p><br>
                    <a id="btnPDF" href="<?php echo $pdf_file;?>" class="bg-red" target="_blank"><?=$this->lang->line('pdf_dl_str');?></a>&nbsp;
                    <a id="btnCSV" href="<?php echo $csv_file;?>" class="bg-green" target="_blank"><?=$this->lang->line('csv_dl_str');?></a>
                </div>
            </div>
        </div>
        <div class="pg-footer">
            <p class="footer-label">©︎2020 - CUSTOM corporation</p>
        </div>
    </div>
</body>
<script>
    var filename = JSON.parse('<?php echo $file_name; ?>');
    var fp = "<?php echo base_url()?>assets/json/temphumi.json";
    // insert new

    var data = [];
    var data1 = [];

    var serch_str = location.search;
    if (serch_str.indexOf('Gu=') == -1 && serch_str.indexOf('M=') != -1) {
        var c_id = document.getElementById('pconf');
        var g_id = document.getElementById('Layout_Generating');
        c_id.style.display = '';
        g_id.style.display = '';
        //背景をスクロールできないように　&　スクロール場所を維持
        scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });
        // モーダルウィンドウを開く
        $('.post_conform').fadeIn();
        $('.modal').fadeIn();
        $.getJSON(fp, function (tempdata) {
            for (var i = 0; i < tempdata.length; i++) {
                var d = [];
                d.push(tempdata[i][0]);
                d.push(tempdata[i][1]);
                data.push(d);
                var d = [];
                d.push(tempdata[i][0]);
                d.push(tempdata[i][2]);
                data1.push(d);
            }
            var options = {
                exporting: {
                    url: 'http://export.highcharts.com/'
                },
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: [{
                    labels: {
                        format: '{value: %Y/%m/%d<br>%H:%m:%d}',
                        align: 'center'
                        //rotation: -30
                    },
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}°C',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: '温度（℃）',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }, { // Secondary yAxis
                    title: {
                        text: '湿度（%）',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value}%',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 120,
                    verticalAlign: 'top',
                    y: 100,
                    floating: true,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || // theme
                        'rgba(255,255,255,0.25)'
                },
                series: [{
                    name: '湿度',
                    type: 'spline',
                    dashStyle: 'shortdot',
                    yAxis: 1,
                    data: data1,
                    tooltip: {
                        valueSuffix: ' %'
                    }

                }, {
                    name: '温度',
                    type: 'spline',
                    data: data,
                    tooltip: {
                        valueSuffix: '°C'
                    }
                }],
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    borderWidth: 0
                }
            }
            var obj = {},
                exportUrl = options.exporting.url;
            obj.options = JSON.stringify(options);
            obj.type = 'image/png';
            obj.async = true;

            /* dataString = encodeURI('async=true&type=jpeg&width=400&options=' + options);
            if (window.XDomeinRequest) {
                var xdr = new XDomainRequest();
                xdr.open("post", exportUrl + '?' + dataString);
                xdr.onload = function () {
                    console.log(xdr.responseText);
                    $('#container').html('<img src="' + exporturl + xdr.responseText + '"/>');
                };
                xdr.send();
            } else { */
            $.ajax({
                type: 'post',
                url: exportUrl,
                /* data: dataString, */
                data: obj,
                success: function (data) {
                    var imgContainer = $("#container");
                    console.log('get the file from relative url: ', data);
                    // console.log(exportUrl + data);
                    /* $('#container').html('<img src="' + exportUrl + data + '"/>'); */
                    //$('<img>').attr('src', exportUrl + data).attr('width', '600px').appendTo(imgContainer);
                    search_str = location.search;
                    /* if (search_str.indexOf('Gu=') == -1) { */
                    location.href = '<?php echo base_url()?>analysis/report?Gu=' + exportUrl + data + '&F=' + filename;
                    /* } */
                },
                error: function (err) {
                    debugger;
                    console.log('error', err.statusText)
                }
            })
                .then(
                    function (param) {
                        console.log('param', param);
                    },
                    function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log('errorThrown', errorThrown);
                    }
                );
            /* } */
        });
    } else if (serch_str.indexOf('F=') != -1) {
        var c_id = document.getElementById('pconf');
        var g_id = document.getElementById('Layout_Download');
        g_id.style.display = '';
        c_id.style.display = '';
        //背景をスクロールできないように　&　スクロール場所を維持
        scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });
        // モーダルウィンドウを開く
        $('.post_conform').fadeIn();
        $('.modal').fadeIn();
    }
    $('.srh-block li a').click(function () {
        // $(".trans-btn").removeClass('select-on');
        $(this).parent('li').toggleClass('view-on');

    });
</script>

</html>