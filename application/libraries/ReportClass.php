<?php
/**
 * レポートデータ取得class
 *
 * reportclass
 * PHP Version >= 7.3.12
 *
 * @category   Components
 * @package    Dlog Cloud
 * @subpackage Dlog Cloud
 * @author     masa <masa@masa777.mydns.jp>
 * @license    MIT License
 * @link       https://masa777.mydns.jp
 * @since      1.0.0
 */

class Reportclass
{
    public function setReportCsvdata($hed_data_arr, $data_arr)
    {
        /**
         * $hed_data_arr = ヘッダー表示用連想配列データ
         * Language = $hed_data_arr[user.Language] (ja_JP or en_US)
         * $timezone = $hed_data_arr[user.TimeZone]
         * ProductName = $hed_data_arr[product.ProductName]
         * IMEI = $hed_data_arr[product.IMEI]
         * TerminalDataInterval = $hed_data_arr[product.TerminalDataInterval]
         * txtBeginTime = $hed_data_arr[post['txtBeginTime']]
         * txtEndTime = $hed_data_arr[post['txtEndTime']]
         * $T_Max = $hed_data_arr[terminalhistory.T_Max]=>queryで取得する。
         * $H_Max = $hed_data_arr[terminalhistory.H_Max]=>queryで取得する。
         * $T_Min = $hed_data_arr[terminalhistory.T_Min]=>queryで取得する。
         * $H_Min = $hed_data_arr[terminalhistory.H_Min]=>queryで取得する。
         * $T_Average = $hed_data_arr[terminalhistory.T_Average]=>queryで取得する。
         * $H_Average = $hed_data_arr[terminalhistory.H_Average]=>queryで取得する。
         * 注意：Hデータがない場合は表示は(--)にする。db取得時に代入する。
         * $data_arr = データ表示用連想配列データ
         * $data_arr = terminalhistory(PID(product(UserID))) テーブルより取得
         * $date_str[0]（日付）= explode(" ", $data_arr[terminalhistory.RTC])
         * $date_str[1]（時刻）= explode(" ", $data_arr[terminalhistory.RTC])
         * $temperature（温度）= $data_arr[terminalhistory.Temperature]
         * $humidity（温度）= $data_arr[terminalhistory.Humidity] * 100 : -1000 => (--)
         * データ追加になる可能性あり
         */
        
        $timezone = floatval($hed_data_arr['TimeZone']);
        $fugo = "";
        $ts1 = strtotime($hed_data_arr['txtEndTime']);
        $ts2 = strtotime($hed_data_arr['txtBeginTime']);
        $measurement_day = $ts2 - $ts1;
        $time_d = (int)($measurement_day / (60 * 60 * 24));
        $time_h = (int)(($measurement_day % (60 * 60 * 24)) / (60 * 60));
        $time_m = (int)(($measurement_day % (60 * 60 * 24)) % (60 * 60) / 60);
        $time_s = (int)(($measurement_day % (60 * 60 * 24)) % (60 * 60) % 60);
        $T_Max = $hed_data_arr['T_Max'];
        if ($hed_data_arr['H_Max'] != -1000) {
            $H_Max = $hed_data_arr['H_Max'] * 100;
        } else {
            $H_Max = "--";
        }
        $T_Min = $hed_data_arr['T_Min'];
        if ($hed_data_arr['H_Min'] != -1000) {
            $H_Min = $hed_data_arr['H_Min'] * 100;
        } else {
            $H_Min = "--";
        }
        $T_Average = round($hed_data_arr['T_Average'], 2);
        if ($hed_data_arr['H_Average'] != -1000) {
            $H_Average = round($hed_data_arr['H_Average'] * 100, 2);
        } else {
            $H_Average = "--";
        }

        $f_fl = 273.15;
        $aeh = -10000;
        $t = [];
        foreach ($data_arr as $key => $val) {
            $t[$key] = exp($aeh / ($val['Temperature'] + $f_fl));
        }
        $t_average = array_sum($t) / count($t);
        $MKT_Average = round($aeh / log($t_average) - $f_fl, 2);

        if ($timezone > 0) {
            $fugo = "+";
        }
        $csvdata[0] = "デバイス情報";
        $csvdata[1] = "******************************";
        $csvdata[2] = "デバイスタイプ: " . $hed_data_arr['ProductName'];
        $csvdata[3] = "ID: " . $hed_data_arr['IMEI'];
        $csvdata[4] = "測定間隔: " . $hed_data_arr['TerminalDataInterval'] . "min";
        if ($hed_data_arr['Language'] == "ja_JP") {
            $csvdata[5] = "注:";
            $csvdata[6] = "表示されるすべての時間は  UTC" . $fugo . $timezone;
            $csvdata[6] .= " および24時間制に基づいています。 [yyyy-MM-dd HH:mm:ss]";
        } else {
            $csvdata[5] = "Note:";
            $csvdata[6] = "All times shown are based on UTC" . $fugo . $timezone;
            $csvdata[6] .= " and 24-Hour clock [yyyy-MM-dd HH:mm:ss]";
        }
        $csvdata[7] = "";
        $csvdata[8] = "ロギング情報";
        $csvdata[9] = "******************************";
        $csvdata[10] = "開始時刻: " . $hed_data_arr['txtBeginTime'];
        $csvdata[11] = "終了時刻: " . $hed_data_arr['txtEndTime'];
        $csvdata[12] = "測定データ数: " . count($data_arr);
        $csvdata[13] = "測定時間: " . $time_d . 'd ' . $time_h . 'h ' . $time_m . 'm ' . $time_s . 's';
        $csvdata[14] = "最大値: " . $T_Max . '℃/' . $H_Max . '%RH';
        $csvdata[15] = "最小値: " . $T_Min . '℃/' . $H_Min . '%RH';
        $csvdata[16] = "平均値: " . $T_Average . '℃/' . $H_Average . '%RH';
        $csvdata[17] = "平均動態温度: " . $MKT_Average . '℃';
        $csvdata[18] = "";
        $csvdata[19] = "";
        // ここからヘッダー部
        $csvdata[20] = "日付,時刻,温度(℃),湿度(%RH)";
        $csvdata[21] = $csvdata[9] . ',' . $csvdata[9] . ',' . $csvdata[9] . ',' . $csvdata[9] . ',' . $csvdata[9];
        // ここからデータ部
        foreach ($data_arr as $key => $val) {
            $date_str = explode(" ", $val['RTC']);
            if ($val['Humidity'] == -1000) {
                $h_d = "--";
            } else {
                $h_d = round($val['Humidity'] * 100, 2);
            }
            $csvdata[$key + 22] = $date_str[0] . ',' . $date_str[1] . ',' . $val['Temperature'] . ',' . $h_d;
        }
        return $csvdata;
    }
    public function makeJsonDataFile($data_arr)
    {
        /**
         * json data file make pg
         * table = terminalhistory
         * RTC = terminalhistory.RTC
         * Temperature = terminalhistory.Temperature
         * Humidity = terminalhistory.Humidity
         */

        $jsondata = '[';
        foreach ($data_arr as $val) {
            if ($val['Humidity'] == -1000) {
                $humidity = 0;
            } else {
                $humidity = $val['Humidity'] * 100;
            }
            $date_time = strtotime($val['RTC']) * 1000;
            $jsondata .= '[';
            $jsondata .= $date_time . ',';
            $jsondata .= $val['Temperature'] . ',';
            $jsondata .= $humidity;
            $jsondata .= '],';
        }
        $jsondata = rtrim($jsondata, ',');
        $jsondata .= ']';
        $path = './json/';
        if (is_writable($path)) {
            $file_handle = fopen($path."temphumi.json", "w");
            fwrite($file_handle, $jsondata);
            fclose($file_handle);
        }
    }
    public function makePdfFile($tcpdf, $gurl, $file_name)
    {
        /**
         * tcpdf インスタンス
         * pdf file make pg
         * data_arr = jsondata
         */

        $row_count = 100;
        $folder = './res_data/report/';
        $print_row = 0;
        $csvfile_name = $file_name . '.csv';
        $row = 1;
        $i = 0;
        $data_arr = [];
        if (($handle = fopen($folder . $csvfile_name, 'r')) !== false) {
            while (($data = fgetcsv($handle))) {
                foreach ($data as $val) {
                    $val = mb_convert_encoding($val, "UTF-8", "SJIS");
                    switch ($row) {
                        case 3:
                            $tmp = explode(':', $val);
                            $d_type = trim($tmp[1]);
                            break;
                        case 4:
                            $tmp = explode(':', $val);
                            $d_id = trim($tmp[1]);
                            break;
                        case 5:
                            $tmp = explode(':', $val);
                            $interval = trim($tmp[1]);
                            break;
                        case 6:
                            $note = trim($val);
                            break;
                        case 7:
                            $note_str = trim($val);
                            break;
                        case 11:
                            $tmp = explode(':', $val);
                            $bigintime = trim($tmp[1]);
                            break;
                        case 12:
                            $tmp = explode(':', $val);
                            $enttime = trim($tmp[1]);
                            break;
                        case 13:
                            $tmp = explode(':', $val);
                            $d_cnt = trim($tmp[1]);
                            break;
                        case 14:
                            $tmp = explode(':', $val);
                            $dc_time = trim($tmp[1]);
                            break;
                        case 15:
                            $tmp = explode(':', $val);
                            $tmp_a = explode('/', $tmp[1]);
                            $tmp_maxt = $tmp_a[0];
                            $tmp_maxh = $tmp_a[1];
                            break;
                        case 16:
                            $tmp = explode(':', $val);
                            $tmp_a = explode('/', $tmp[1]);
                            $tmp_mint = $tmp_a[0];
                            $tmp_minh = $tmp_a[1];
                            break;
                        case 17:
                            $tmp = explode(':', $val);
                            $tmp_a = explode('/', $tmp[1]);
                            $tmp_avet = $tmp_a[0];
                            $tmp_aveh = $tmp_a[1];
                            break;
                        case 18:
                            $tmp = explode(':', $val);
                            $tmp_mkt = $tmp[1];
                            break;
                        default:
                    }
                }
                if ($row > 22) {
                    $data_arr[$i] = $data;
                    $i ++;
                }
                $row ++;
            }
        }
        $data_count = count($data_arr);
        $page = $data_count / ($row_count * 5);
        $data_end_count = $data_count % ($row_count * 5);
        $maketime = date("Y-m-d H:i:s");
        if ($data_end_count > 0) {
            $page ++;
        }
        $html[0] = <<< EOF
        <style>
        @page{
            size: A4;
            margin: 0;
        }
        @media print{
            body{
                width: 215mm;
            }
        }
        @media screen{
            body{
                background: #eee;
            }
            .print_pages{
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                margin: 5mm;
            }
        }
        h1{
            font-size: 24px;
        }
        table{
            width: 190mm;
            align-self:center;
        }
        th{
            height: 17px;
            border-bottom: 1px solid black;
        }
        td{
            font-size: 8px;
            font-weight: bold;
        }
        th.data{
            border-right: 1px solid black;
            margin-left: 0;
            font-size: 6px;
        }
        td.data{
            border-right: 1px solid black;
            font-size: 6px;
            font-weight: normal;
            line-height: 8px;
        }
        span.top{
            margin-left: 10px;
        }
        .top_data{
            font-weight: normal;
        }
        .print_pages{
            width: 210mm;
            height: 279mm;
            page-break-after: always;
            position: relative;
        }
        .data_graff{
            border: 1px solid black;
            width: 190mm;
            height: 130mm;
        }
        table.data_table{
            border: 1px solid black;
        }
        span{
            font-size: 8px;
        }
        </style>
        <article>
            <section class="print_pages">
                <table class="top_table">
                    <tbody>
                        <tr>
                            <td colspan="1">
                                <span><img src="./img/dlog_rogo.png" alt="dlog-cloud"></span>
                            </td>
                            <td colspan="3">
                                <span><h1 style="margin-left: 50px">データー レポート</h1></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">
                                <span style="font-size: 14px;">ID:</span>
        EOF;
        $html[0] .= '<span style="font-size: 12px;margin-right: 30px;">' . $d_id . '</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <th colspan="5" align="left">
                                <span style="font-size: 12px;">ファイル情報</span>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <span class="top">ファイル作成日:&emsp;&emsp;</span>
        EOF;
        $html[0] .= '<span class="top_data">' . $maketime . '</span><br>';
        $html[0] .= '<span class="top" style="color: red;">' . $note . '</span>';
        $html[0] .= '<span class="top_data" style="color: red;">' . $note_str . '</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" align="left">
                                <span style="font-size: 12px;">デバイス情報</span>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">デバイスタイプ:</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $d_type . '</span><br>';
        $html[0] .= <<< EOF
                            </td>
                            <td>
                                <span class="top">測定間隔：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $interval . '</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">ID：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $d_id . '</span>';
        $html[0] .= <<< EOF
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="4" align="left">
                                <span style="font-size: 12px;">ロギング情報</span>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">開始時刻：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $bigintime . '</span>';
        $html[0] .= <<< EOF
                            </td>
                            <td>
                                <span class="top">最大値：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $tmp_maxt . '(Temp)/'. $tmp_maxh . '(Humidity)</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">終了時刻：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $enttime . '</span>';
        $html[0] .= <<< EOF
                            </td>
                            <td>
                                <span class="top">最小値：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $tmp_mint . '(Temp)/' . $tmp_minh . '(Humidity)</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">測定データ数：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $d_cnt . '</span>';
        $html[0] .= <<< EOF
                            </td>
                            <td>
                                <span class="top_data">平均値：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $tmp_avet . '(Temp)/'. $tmp_aveh . '(Humidity)</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="top">測定時間：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $dc_time . '</span>';
        $html[0] .= <<< EOF
                            </td>
                            <td>
                                <span class="top">平均動態温度：</span>
                            </td>
                            <td>
        EOF;
        $html[0] .= '<span class="top_data">' . $tmp_mkt . '</span>';
        $html[0] .= <<< EOF
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><span></span></td>
                        </tr>
                        <tr>
                            <td class="data_graff" colspan="4" align="center">
                                <div>
        EOF;
        $html[0] .= '<img width="180mm" height="120mm" src="' . $gurl . '" alt="graff">';
        $html[0] .= <<< EOF
                                </div>   
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </article>
        EOF;
        for ($p_cont = 1; $p_cont < $page; $p_cont++) {
            $html[$p_cont] = "";
            $html[$p_cont] .= <<< EOF
            <style>
            @page{
                size: A4;
                margin: 0;
            }
            @media print{
                body{
                    width: 215mm;
                }
            }
            @media screen{
                body{
                    background: #eee;
                }
                .print_pages{
                    background: white;
                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                    margin: 5mm;
                }
            }
            h1{
                font-size: 24px;
            }
            table{
                width: 190mm;
                align-self: center;
            }
            th{
                border-bottom: 1px solid black;
            }

            th.data_h{
                border-right: 1px solid black;
                margin-left: 0;
                font-size: 8px;
            }
            td.data{
                border-right: 1px solid black;
                font-size: 6px;
                font-weight: normal;
                line-height: 7px;
            }
            span.top{
                margin-left: 10px;
            }
            .print_pages{
                width: 210mm;
                height: 279mm;
                page-break-after: always;
                position: relative;
            }
            .data_graff{
                border: 1px solid black;
                width: 180mm;
                height: 130mm;
            }
            table.data_table{
                border: 0 solid black;
            }
            span{
                font-size: 6px;
                height:7px;
            }
            span.hed{
                font-size: 8px;
            }
            </style>
            <article>
                <section class="print_pages">
                    <table border="0" class="top_table">
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <span><img src="./img/dlog_rogo.png" alt="dlog-cloud"></span>
                                </td>
                            </tr>
                            <tr>  
                                <td colspan="5" align="center" style="height: 17px;">
                                    <span style="font-size: 16px;">データー リスト</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="1" class="top_table" style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <th class="data_h">
                                    <span class="hed">日付</span>
                                    <span class="hed">&emsp;&emsp;時刻</span>
                                    <span class="hed">&emsp;℃</span>
                                    <span class="hed">&emsp;%RH</span>
                                </th>
                                <th class="data_h">
                                    <span class="hed">日付</span>
                                    <span class="hed">&emsp;&emsp;時刻</span>
                                    <span class="hed">&emsp;℃</span>
                                    <span class="hed">&emsp;%RH</span>
                                </th>
                                <th class="data_h">
                                    <span class="hed">日付</span>
                                    <span class="hed">&emsp;&emsp;時刻</span>
                                    <span class="hed">&emsp;℃</span>
                                    <span class="hed">&emsp;%RH</span>
                                </th>
                                <th class="data_h">
                                    <span class="hed">日付</span>
                                    <span class="hed">&emsp;&emsp;時刻</span>
                                    <span class="hed">&emsp;℃</span>
                                    <span class="hed">&emsp;%RH</span>
                                </th>
                                <th class="data_h">
                                    <span class="hed">日付</span>
                                    <span class="hed">&emsp;&emsp;時刻</span>
                                    <span class="hed">&emsp;℃</span>
                                    <span class="hed">&emsp;%RH</span>
                                </th>
                            </tr>
                            <tr>
                                <td class="data">
            EOF;
            for ($i = $print_row; $i < $row_count + $print_row; $i ++) {
                if (isset($data_arr[$i])) {
                    $html[$p_cont] .= '<span>' . $data_arr[$i][0] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][1] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][2] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][3] . '</span><br>';
                } else {
                    break;
                }
            }
            $print_row = $i;
            $tag = mb_substr($html[$p_cont], -4);
            if ($tag == '<br>') {
                $html[$p_cont] = substr($html[$p_cont], 0, strlen($html[$p_cont]) - 4);
            }
            $html[$p_cont] .= <<< EOF
                                </td>
                                <td class="data">
            EOF;
            for ($i = $print_row; $i < $row_count + $print_row; $i ++) {
                if (isset($data_arr[$i])) {
                    $html[$p_cont] .= '<span>' . $data_arr[$i][0] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][1] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][2] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][3] . '</span><br>';
                } else {
                    break;
                }
            }
            $print_row = $i;
            $tag = mb_substr($html[$p_cont], -4);
            if ($tag == '<br>') {
                $html[$p_cont] = substr($html[$p_cont], 0, strlen($html[$p_cont]) - 4);
            }
            $html[$p_cont] .= <<< EOF
                                </td>
                                <td class="data">
            EOF;
            for ($i = $print_row; $i < $row_count + $print_row; $i ++) {
                if (isset($data_arr[$i])) {
                    $html[$p_cont] .= '<span>' . $data_arr[$i][0] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][1] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][2] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][3] . '</span><br>';
                } else {
                    break;
                }
            }
            $print_row = $i;
            $tag = mb_substr($html[$p_cont], -4);
            if ($tag == '<br>') {
                $html[$p_cont] = substr($html[$p_cont], 0, strlen($html[$p_cont]) - 4);
            }
            $html[$p_cont] .= <<< EOF
                                </td>
                                <td class="data">
            EOF;
            for ($i = $print_row; $i < $row_count + $print_row; $i ++) {
                if (isset($data_arr[$i])) {
                    $html[$p_cont] .= '<span>' . $data_arr[$i][0] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][1] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][2] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][3] . '</span><br>';
                } else {
                    break;
                }
            }
            $print_row = $i;
            $tag = mb_substr($html[$p_cont], -4);
            if ($tag == '<br>') {
                $html[$p_cont] = substr($html[$p_cont], 0, strlen($html[$p_cont]) - 4);
            }
            $html[$p_cont] .= <<< EOF
                                </td>
                                <td class="data">
            EOF;
            for ($i = $print_row; $i < $row_count + $print_row; $i ++) {
                if (isset($data_arr[$i])) {
                    $html[$p_cont] .= '<span>' . $data_arr[$i][0] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][1] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][2] . '</span>';
                    $html[$p_cont] .= '<span>&emsp;' . $data_arr[$i][3] . '</span><br>';
                } else {
                    break;
                }
            }
            $print_row = $i;
            $tag = mb_substr($html[$p_cont], -4);
            if ($tag == '<br>') {
                $html[$p_cont] = substr($html[$p_cont], 0, strlen($html[$p_cont]) - 4);
            }
            $html[$p_cont] .= <<< EOF
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </article>
            EOF;
        }

        for ($i = 0; $i < count($html); $i ++) {
            $tcpdf->AddPage();
            $tcpdf->SetFont("kozgopromedium", "", 10);
            $tcpdf->SetCreator($_SERVER['SERVER_NAME']);
            $tcpdf->writeHTML($html[$i]); // 表示htmlを設定
            $tcpdf->lastPage();
        }
    }
    public function reportMakeData($hed_data_arr, $data_arr, $ids, $file_name)
    {
        // 計測時間計算
        $ts1 = strtotime($hed_data_arr[0]['txtEndTime']);
        $ts2 = strtotime($hed_data_arr[0]['txtBeginTime']);
        $measurement_day = $ts2 - $ts1;
        $f_fl = 273.15;
        $aeh = -10000;
        $t = [];
        foreach ($data_arr as $key => $val) {
            $t[$key] = exp($aeh / ($val['Temperature'] + $f_fl));
        }
        $t_average = array_sum($t) / count($t);
        $MKT_Average = round($aeh / log($t_average) - $f_fl, 2);
        $insert_data = array(
            'ID' => $ids,
            'PID' => $hed_data_arr[0]['ProductID'],
            'DeviceID' => $hed_data_arr[0]['IMEI'],
            'DeviceTypeName' => $hed_data_arr[0]['ProductName'],
            'TemperatureUnit' => 0,
            'Language' => $hed_data_arr[0]['Language'],
            'TimeZone' => $hed_data_arr[0]['TimeZone'],
            'LogInterval' => $hed_data_arr[0]['TerminalDataInterval'] * 60,
            'BeginTime' => $hed_data_arr[0]['txtEndTime'],
            'EndTime' => $hed_data_arr[0]['txtBeginTime'],
            'T_Max' => $hed_data_arr[0]['T_Max'],
            'T_Min' => $hed_data_arr[0]['T_Min'],
            'T_Average' => $hed_data_arr[0]['T_Average'],
            'MKT' => $MKT_Average,
            'H_Max' => $hed_data_arr[0]['H_Max'],
            'H_Min' => $hed_data_arr[0]['H_Min'],
            'H_Average' => $hed_data_arr[0]['H_Average'],
            'Voltage' => -1000,
            'Battery' => -1000,
            'Total' => $hed_data_arr[0]['DataCount'],
            'TotalTime' => $measurement_day,
            'FileName' => $file_name,
            'Progress' => 100,
            'Status' => 2,
            'CreateTime' => date('Y-m-d H:i:s', strtotime("now +" . $hed_data_arr[0]['TimeZone'] . " hours")),
            'ServerTime' => date('Y-m-d H:i:s'),
            'isdelete' => 0
        );
        return $insert_data;
    }
}
