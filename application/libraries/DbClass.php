<?php
/**
 * データベースアクセスclass
 *
 * dbclass
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

class Dbclass
{
    // メンバー
    public function dbCi($host, $username, $password, $dbname)
    {
        /**
         * PDO接続
         * host = ホスト
         * username = ユーザー
         * password = パスワード
         * dbname = データベース名
         * PDOインスタンスを返す
         */
        
        $charaset = "SET NAMES utf8";
        try {
            $dsn = "mysql:host=".$host."; dbname=".$dbname.";charset=utf8; ";
            $dbpdo = new \PDO($dsn, $username, $password);
            $dbpdo->query($charaset);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $dbpdo;
    }

    public function dbAllSelect($dbpdo, $tname)
    {
        /**
         * dbpdo = PDOインスタンス
         * tname = テーブル名
         * wfname = 照合フィールド名
         * wstr = 照合データ
         * セレクトデータ配列を返す
         */
        try {
            $query = 'SELECT * FROM';
            $query .= '`' .  $tname . '`';
            $stmt = $dbpdo->prepare($query);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }

    public function dbSelect($dbpdo, $tname, $like, $wfname, $wstr, string $order = null)
    {
        /**
         * dbpdo = PDOインスタンス
         * tname = テーブル名
         * wfname = 照合フィールド名
         * wstr = 照合データ
         * セレクトデータ配列を返す
         */
        try {
            $check_fn_query = 'DESCRIBE `' . $tname . '` `isdelete`;';
            $stmtcheck = $dbpdo->prepare($check_fn_query);
            $stmtcheck->execute();
            $res = $stmtcheck->fetchAll(\PDO::FETCH_ASSOC);
            $and_isdelete = "";
            if (!empty($res)) {
                if ($res[0]["Field"] == "isdelete") {
                    $and_isdelete = "AND `isdelete`=0 ";
                }
            }
            
            if ($wstr == '*') {
                $query = 'SELECT * FROM ';
                $query .= '`' . $tname . '`;';
            } else {
                $query = 'SELECT * FROM ';
                $query .= '`' .  $tname . '` ';
                $query .= ' WHERE ';
                $query .= '`' . $wfname . '`' .$like. ' "' . $wstr . '" ' . $and_isdelete . $order;
            }
            $stmt = $dbpdo->prepare($query);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }

    public function dbUpdate($dbpdo, $tname, $up_darry, $wfname, $wstr)
    {
        /**
         * dbpdo = PDOインスタンス
         * tnam = テーブル
         * up_darry = 連想配列データ
         * sfname = 照合フィールド
         * wstr = 照合データ
         * 成功すれば、クエリ文字列を返す
         */

        $dbtype_arry = $this->getType($dbpdo, $tname);
        try {
            $mcls = new Methodclass();
            $darry = array();
            $query = "UPDATE `" . $tname . "` SET ";
            /* 配列でフィールドデータを読み込む */
            foreach ($up_darry as $key => $val) {
                $query .= "`" . $key . "` =:" . $key . ", ";
                $keystr = ':' . $key;
                $insertarry = array($keystr => $val);
                array_push($darry, $insertarry);
            }
            $query = substr($query, 0, strlen($query) - 2);
            $query .= " WHERE ";
            $query .= "`" . $wfname . "` =:" . $wfname;
            $keystrid = ':' . $wfname;
            $stmt = $dbpdo->prepare($query);

            foreach ($darry as $mval) {
                foreach ($mval as $key => $val) {
                    $typedata = $mcls->getFieldType($dbtype_arry, $key);
                    if ($typedata == 1) {
                        $stmt->bindValue($key, $val, \PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue($key, $val, \PDO::PARAM_STR);
                    }
                }
            }
            $stmt->bindValue($keystrid, (int)$wstr, \PDO::PARAM_INT);
            $rs = $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        $mcls = null;
        return $rs;
    }
    public function getType($dbpdo, $tablename)
    {
        /**
         * dbpdo = PDO院スタンス
         * tablename = テーブル名
         * テーブルの情報を連想配列で返す
         */
        try {
            $query = "SHOW COLUMNS FROM ".$tablename;
            $stmt = $dbpdo->query($query);
            $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $rs;
    }
    public function getUserSetting($dbpdo, $user_id)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * UserSettingとjoin抽出
         * テーブルの情報を連想配列で返す
         */
        $user_tn = "users";
        $userset_tn = "usersetting";

        $query = "";
        $query .= "SELECT ";
        $query .= "us.ID, ";
        $query .= "us.UserName, ";
        $query .= "us.Password, ";
        $query .= "us.TimeZone, ";
        $query .= "uss.ID, ";
        $query .= "uss.UserID, ";
        $query .= "uss.IsSearchRTC, ";
        $query .= "uss.TemperatureUnit ";
        $query .= "FROM " . $user_tn . " AS us ";
        $query .= "INNER JOIN " . $userset_tn . " AS uss ";
        $query .= "ON us.ID = uss.UserID ";
        $query .= "WHERE us.ID=:userid;";

        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->bindValue(":userid", $user_id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }

    public function getProductGroup($dbpdo, $user_id)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * ProductGroup,User,join抽出
         * テーブルの情報を連想配列で返す
         */
        $query = "";
        $user_tn = "users";
        $productg_tn = "productgroup";

        $query .= "SELECT ";
        $query .= "pdg.ID AS ID, ";
        $query .= "pdg.GroupName AS GroupName, ";
        $query .= "pdg.ParentID AS ParentID, ";
        $query .= "pdg.UserID AS UserID, ";
        $query .= "pdg.SortID AS SortID, ";
        $query .= "pdg.Permission AS Permission, ";
        $query .= "pdg.Description AS Description, ";
        $query .= "pdg.isdelete AS isdelete ";
        $query .= "FROM " . $productg_tn . " AS pdg ";
        $query .= "INNER JOIN " . $user_tn . " AS us ";
        $query .= "ON pdg.UserID = us.ID ";
        $query .= "WHERE pdg.UserID=:userid AND pdg.isdelete=0 ";
        $query .= "ORDER BY pdg.SortID ASC;";
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->bindValue(":userid", $user_id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }

    public function getProduct($dbpdo, $group_id, $user_id)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * Product,Producttype,ProductGroup,join抽出
         * テーブルの情報を連想配列で返す
         */
        $query = "";
        $product_tn = "product";
        $productg_tn = "productgroup";
        $producttype_tn = "producttype";
        $query .= "SELECT ";
        $query .= "pd.ID AS ID, ";
        $query .= "pd.ProductName AS ProductName, ";
        $query .= "pd.TypeID AS TypeID, ";
        $query .= "pd.UserID AS UserID, ";
        $query .= "pd.GroupID AS GroupID, ";
        $query .= "pd.CreateTime AS CreateTime, ";
        $query .= "pty.ID AS ProducttypeID, ";
        $query .= "pty.TypeName AS TypeName, ";
        $query .= "pdg.ID AS ProductGroupID, ";
        $query .= "pdg.GroupName AS ProductgroupName, ";
        $query .= "pdg.SortID AS SortID, ";
        $query .= "FROM " . $product_tn . " AS pd ";
        $query .= "INNER JOIN " . $productg_tn . " AS pdg ";
        $query .= "ON pd.GroupID = pdg.ID ";
        $query .= "AND pdg.isdelete = 0 ";
        $query .= "INNER JOIN " . $producttype_tn . " AS pty ";
        $query .= "ON pty.ID = pd.TypeID ";
        $query .= "WHERE pd.UserID=:userid ";
        $query .= "AND pd.GroupID=:groupid ";
        $query .= "AND pd.isdelete=0 ";
        $query .= "ORDER BY pdg.SortID ASC;";
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->bindValue(":userid", $user_id, \PDO::PARAM_INT);
            $stmt->bindValue(":groupid", $group_id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }
    public function insertAlarmConfig($dbpdo, $data_arry)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * Product,Producttype,ProductGroup,join抽出
         * テーブルの情報を連想配列で返す
         */
        $query = "";
        $alarmconfig_tn = "alarmconfig";
        $query .= "INSERT INTO ";
        $query .= "`" . $alarmconfig_tn . "` ";
        $query .= "(";
        foreach ($data_arry as $key => $val) {
            $query .= "`" . $key . "`, ";
        }
        $query = rtrim(trim($query), ',');
        $query .= ") ";
        $query .= "VALUES ";
        $query .= "(";
        foreach ($data_arry as $key => $val) {
            $query .= ":" . $key . ", ";
        }
        $query = rtrim(trim($query), ',');
        $query .= ");";
        try {
            $stmt = $dbpdo->prepare($query);
            foreach ($data_arry as $key => $val) {
                $stmt->bindValue(":" . $key, $val);
            }
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:" . $e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }
    public function getProductSensor($dbpdo, $user_id, $pid = null)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * Product,Producttype,ProductGroup,join抽出
         * テーブルの情報を連想配列で返す
         */
        if ($pid != null) {
            $query = "SELECT pd.ID AS ID, pd.IMEI AS IMEI, pd.ProductName AS ProductName, pd.TypeID AS TypeID, pd.UserID AS UserID, pd.GroupID AS GroupID, pd.RegionID AS RegionID, pd.CreateTime AS CreateTime, pd.ExpireTime AS ExpireTime, pd.isdelete AS IsExpire, pd.TerminalPassword AS TerminalPassword, pd.TerminalDataInterval AS TerminalDataInterval, pd.Description AS Description, pd.IsAutoPay AS IsAutoPay, pty.ID AS ProducttypeID, pty.TypeName AS TypeName, pdg.ID AS ProductGroupID, pdg.SortID AS SortID, pdg.GroupName AS GroupName FROM product AS pd INNER JOIN productgroup AS pdg ON pd.GroupID = pdg.ID AND pdg.isdelete = 0  AND pdg.UserID=".$user_id." INNER JOIN producttype AS pty ON pty.ID = pd.TypeID WHERE pd.ID=".$pid." AND pd.isdelete=0 ORDER BY pdg.SortID ASC";
        } else {
            $query = "SELECT pd.ID AS ID, pd.IMEI AS IMEI, pd.ProductName AS ProductName, pd.TypeID AS TypeID, pd.UserID AS UserID, pd.GroupID AS GroupID, pd.RegionID AS RegionID, pd.CreateTime AS CreateTime, pd.ExpireTime AS ExpireTime, pd.isdelete AS IsExpire, pd.TerminalPassword AS TerminalPassword, pd.TerminalDataInterval AS TerminalDataInterval, pd.Description AS Description, pd.IsAutoPay AS IsAutoPay, pty.ID AS ProducttypeID, pty.TypeName AS TypeName, pdg.ID AS ProductGroupID, pdg.SortID AS SortID, pdg.GroupName AS GroupName FROM product AS pd INNER JOIN productgroup AS pdg ON pd.GroupID = pdg.ID AND pdg.isdelete = 0  AND pdg.UserID=".$user_id." INNER JOIN producttype AS pty ON pty.ID = pd.TypeID WHERE  pd.isdelete=0 ORDER BY pdg.SortID ASC";
        }
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }

    public function insertData($dbpdo, $tn, $data_arry)
    {
        /**
         * dbpdo = PDインスタンス
         *
         * data_arry = 登録データ配列
         *
         */

        $dbtype_arry = $this->getType($dbpdo, $tn);
        $mcls = new Methodclass();
        $darry = array();
        $query = "UPDATE `" . $tn . "` SET ";
        /* 配列でフィールドデータを読み込む */
        foreach ($data_arry as $key => $val) {
            $query .= "`" . $key . "` =:" . $key . ", ";
            $keystr = ':' . $key;
            $insertarry = array($keystr => $val);
            array_push($darry, $insertarry);
        }
        $query = "";
        $query .= "INSERT INTO ";
        $query .= "`" . $tn . "` ";
        $query .= "(";
        foreach ($data_arry as $key => $val) {
            $query .= "`" . $key . "`, ";
        }
        $query = rtrim(trim($query), ',');
        $query .= ") ";
        $query .= "VALUES ";
        $query .= "(";
        foreach ($data_arry as $key => $val) {
            $query .= ":" . $key . ", ";
        }
        $query = rtrim(trim($query), ',');
        $query .= ");";
        try {
            $stmt = $dbpdo->prepare($query);
            foreach ($darry as $mval) {
                foreach ($mval as $key => $val) {
                    $typedata = $mcls->getFieldType($dbtype_arry, $key);
                    if ($typedata == 1) {
                        $stmt->bindValue($key, $val, \PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue($key, $val, \PDO::PARAM_STR);
                    }
                }
            }
            /* foreach ($data_arry as $key => $val) {
                $stmt->bindValue(":" . $key, $val);
            } */
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:" . $e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $stmt;
    }
    public function getReport($dbpdo, $user_id)
    {
        /**
         * dbpdo = PDインスタンス
         * User_id = 対象ID
         * Product,Producttype,ProductGroup,join抽出
         * テーブルの情報を連想配列で返す
         */
        
        $query = "";
        $report_tn = "report";
        $product_tn = "product";
        $productg_tn = "productgroup";
        $producttype_tn = "producttype";

        $query .= "SELECT ";
        $query .= "pd.ID AS ID ";
        $query .= "FROM " . $product_tn . " AS pd ";
        $query .= " JOIN " . $productg_tn . " AS pg ";
        $query .= "ON pd.GroupID=pg.ID join producttype as t on pd.TypeID=t.ID";
        $query .= " WHERE pd.UserID=:userid ";
        $query .= "AND pg.isdelete=0 ";
        $query .= "AND pd.isdelete=0 ";
        $query .= "ORDER BY pg.SortID;";
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->bindValue(":userid", $user_id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //var_dump($res);
        // get list
        $query = "";
        $query .= "SELECT ";
        $query .= "pd.ID AS ProductID, ";
        $query .= "pd.ProductName AS ProductName, ";
        $query .= "pd.IMEI AS IMEI, ";
        $query .= "pd.UserID AS UserID, ";
        $query .= "pg.ID AS GroupID, ";
        $query .= "pg.GroupName AS GroupName, ";
        $query .= "pg.SortID AS SortID, ";
        $query .= "pdt.ID AS ProductTypeID, ";
        $query .= "pdt.TypeName AS TypeName, ";
        $query .= "rp.ID AS ID, ";
        $query .= "rp.PID AS PID, ";
        $query .= "rp.T_Average AS T_Average, ";
        $query .= "rp.H_Average AS H_Average, ";
        $query .= "rp.Status AS Status, ";
        $query .= "rp.CreateTime AS CreateTime, ";
        $query .= "rp.FileName AS FileName ";
        $query .= "FROM " . $product_tn . " AS pd ";
        $query .= "INNER JOIN " . $productg_tn . " AS pg ";
        $query .= "ON pd.GroupID=pg.ID ";
        $query .= "AND pg.isdelete=0 ";
        $query .= "INNER JOIN " . $producttype_tn . " AS pdt ";
        $query .= "ON pd.TypeID=pdt.ID ";
        $query .= "INNER JOIN " . $report_tn . " AS rp ";
        $query .= "ON rp.PID=pd.ID ";
        $query .= "WHERE rp.PID=:pid ";
        $query .= "ORDER BY rp.CreateTime DESC LIMIT 10";
        try {
            $stmt = $dbpdo->prepare($query);
            foreach ($res as $key => $val) {
                $stmt->bindValue(":pid", $val['ID'], \PDO::PARAM_INT);
                $stmt->execute();
                $list[$key] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $list;
    }
    public function reportCsvHedData($dbpdo, $b_time, $e_time, $pid)
    {
        /**
         * dbpdo = PDインスタンス
         * pid = 対象ID
         * terminalhistory,product,user,join抽出
         * テーブルの情報を連想配列で返す
         */
        $query = "";
        $product_tn = "product";
        $user_tn = "users";
        $terminalhistory_tn = "terminalhistory".$pid;
        $query = "";
        $query .= "SELECT ";
        $query .= "pd.IMEI AS IMEI, ";
        $query .= "pd.ID AS ProductID, ";
        $query .= "pd.TerminalDataInterval AS TerminalDataInterval, ";
        $query .= "pd.ProductName AS ProductName, ";
        $query .= "us.Language AS Language, ";
        $query .= "us.TimeZone AS TimeZone, ";
        $query .= "max(th.Temperature) AS T_Max, ";
        $query .= "max(th.Humidity) AS H_Max, ";
        $query .= "min(th.Temperature) AS T_Min, ";
        $query .= "min(th.Humidity) AS H_Min, ";
        $query .= "avg(th.Temperature) AS T_Average, ";
        $query .= "avg(th.Humidity) AS H_Average, ";
        $query .= "max(th.RTC) AS txtEndTime, ";
        $query .= "min(th.RTC) AS txtBeginTime, ";
        $query .= "count(th.ID) AS DataCount ";
        $query .= "FROM ".$terminalhistory_tn;
        $query .= " AS th ";
        
        $query .= "INNER JOIN " . $product_tn . " AS pd ";
        $query .= "ON th.PID=pd.ID ";
        $query .= "INNER JOIN " . $user_tn . " AS us ";
        $query .= "ON pd.UserID=us.ID ";
        $query .= "where th.RTC>=:b_time ";
        $query .= "AND th.RTC<=:e_time ";
        // $query .= "WHERE th.PID=:pid;";
        try {
            $stmt = $dbpdo->prepare($query);
            // $stmt->bindValue(":pid", $pid, \PDO::PARAM_INT);
            $stmt->bindValue(":b_time", $b_time, \PDO::PARAM_STR);
            $stmt->bindValue(":e_time", $e_time, \PDO::PARAM_STR);
            $stmt->execute();
            $list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $list;
    }
    public function reportCsvData($dbpdo, $b_time, $e_time, $pid)
    {
        /**
         * dbpdo = PDインスタンス
         * pid = 対象ID
         * terminalhistory,product,user,join抽出
         * テーブルの情報を連想配列で返す
         */

        $terminalhistory_tn = "terminalhistory".$pid;
        $query = "";
        $query .= "SELECT ";
        $query .= "th.RTC AS RTC, ";
        $query .= "th.Temperature AS Temperature, ";
        $query .= "th.Humidity AS Humidity ";
        $query .= "FROM " . $terminalhistory_tn . ' AS th ';
        $query .= " WHERE  ";
        $query .= " th.RTC>=:b_time ";
        $query .= "AND th.RTC<=:e_time;";
        try {
            $stmt = $dbpdo->prepare($query);
            // $stmt->bindValue(":pid", $pid, \PDO::PARAM_INT);
            $stmt->bindValue(":b_time", $b_time, \PDO::PARAM_STR);
            $stmt->bindValue(":e_time", $e_time, \PDO::PARAM_STR);
            $stmt->execute();
            $list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $list;
    }
    public function nextRowNo($dbpdo, $t_n)
    {
        $query = "";
        $query .= "SELECT ";
        $query .= "MAX(ID) + 1 AS nextid ";
        $query .= "FROM " . $t_n . ";";
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->execute();
            $list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $list;
    }
    public function getHistoryData($dbpdo, $user_id)
    {
        /**
         * dbpdo = PDインスタンス
         * pid = 対象ID
         * terminalhistory,product,productgroup,join抽出
         * テーブルの情報を連想配列で返す
         */
        $query = "";
        $product_tn = "product";
        $productg_tn = "productgroup";
        $producttype_tn = "producttype";
        $terminalhistory_tn = "terminalhistory";

        $query .= "SELECT * ";
        $query .= "FROM (";
        $query .= "SELECT ";
        $query .= "th.ID AS ID, ";
        $query .= "th.UUID AS UUID, ";
        $query .= "th.PID AS PID, ";
        $query .= "th.Temperature AS Temperature, ";
        $query .= "th.Humidity AS Humidity, ";
        $query .= "th.Pressure AS Pressure, ";
        $query .= "th.Voltage AS Voltage, ";
        $query .= "th.Battery AS Battery, ";
        $query .= "th.RSSI AS RSSI, ";
        $query .= "th.RTC AS RTC, ";
        $query .= "pd.IMEI AS IMEI, ";
        $query .= "pd.ProductName AS ProductName, ";
        $query .= "pd.TerminalDataInterval AS TerminalDataInterval, ";
        $query .= "pg.GroupName AS GroupName, ";
        $query .= "pg.SortID AS SortID, ";
        $query .= "pt.TypeName AS TypeName, ";
        $query .= "pt.Model AS Model, ";
        $query .= "pd.isdelete AS Pddel ";
        $query .= "FROM `";
        $query .= $product_tn . "` AS pd ";
        $query .= "INNER JOIN `";
        $query .= $productg_tn . "` AS pg ";
        $query .= "ON pd.GroupID=pg.ID ";
        $query .= "AND pg.isdelete=0 ";
        $query .= "INNER JOIN `";
        $query .= $producttype_tn . "` AS pt ";
        $query .= "ON pd.TypeID=pt.ID ";
        $query .= "AND pt.isdelete=0 ";
        $query .= "INNER JOIN `";
        $query .= $terminalhistory_tn . "` AS th ";
        $query .= "ON pd.ID=th.PID ";
        $query .= "WHERE ";
        $query .= "pd.UserID=:uid ";
        $query .= "AND th.ID in ";
        $query .= "(";
        $query .= "SELECT ";
        $query .= "max(ID) ";
        $query .= "FROM `";
        $query .= $terminalhistory_tn . "` ";
        $query .= "GROUP BY PID ";
        $query .= ")";
        $query .= ") AS thd ";
        $query .= "WHERE ";
        $query .= "Pddel=0 ";
        $query .= "ORDER BY SortID";
        //echo $query . '<br>';
        try {
            $stmt = $dbpdo->prepare($query);
            $stmt->bindValue(":uid", $user_id, \PDO::PARAM_INT);
            $stmt->execute();
            $list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $elogstr ="Error:".$e->getMessage();
            error_log($elogstr, 3, APPPATH."logs/test.log");
            die();
        }
        return $list;
    }
}
