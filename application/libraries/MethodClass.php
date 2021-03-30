<?php
/**
 * メソッドclass
 *
 * methodclass
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

class Methodclass
{
    private function sslPrm($password, $cipher)
    {
        return array($password, $cipher);
    }
    public function enCrypt($plain_text, $cipher, $password, $sslkey)
    {
        /**
         * plain_text = 暗号化対象文字列
         * cipher = 暗号化タイプ文字列
         * password = 暗号化キー文字列
         * 暗号化文字列を返す
         */
        
        if (in_array($cipher, openssl_get_cipher_methods())) {
            //$ivlen = openssl_cipher_iv_length($cipher); // 作成用
            //$iv = openssl_random_pseudo_bytes($ivlen); // 作成用
            //echo "iv = " . urlencode($iv) . '<br>'; // 作成用
            $iv = urldecode($sslkey); // sslkey 使用作成用
            list ($pass, $method) = $this->sslPrm($password, $cipher);
            if (function_exists('openssl_encrypt')) {
                $encrypted = openssl_encrypt(urlencode($plain_text), $method, $pass, OPENSSL_RAW_DATA, $iv);
            } else {
                return urlencode(exec("echo \"".urlencode($plain_text)."\" | openssl enc -".urlencode($method).
                " -base64 -nosalt -K ".bin2hex($pass)." -iv ".bin2hex($iv)));
            }
        }
        return urlencode($encrypted);
    }

    public function deCrypt($encrypted_text, $cipher, $password, $sslkey)
    {
        /**
         * encrypted_text = 複合対象文字列
         * cipher = 暗号化タイプ文字列
         * password = 暗号化キー文字列
         * 複合化文字列を返す
         */
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $iv = urldecode($sslkey);
            list ($pass, $method) = $this->sslPrm($password, $cipher);
            if (function_exists('openssl_decrypt')) {
                $decrypted_text = openssl_decrypt(
                    urldecode($encrypted_text),
                    $method,
                    $pass,
                    OPENSSL_RAW_DATA,
                    $iv
                );
            } else {
                return trim(urldecode(exec("echo \"".urldecode($encrypted_text)."\" | openssl enc -".$method.
                " -d -base64 -nosalt -K ".bin2hex($pass)." -iv ".bin2hex($iv))));
            }
        }
        return trim(urldecode($decrypted_text));
    }

    public function compStr($targetstr, $findstr)
    {
        /**
         * targetstr = 対象文字列
         * findstr = 照合文字列
         * 文字照合出来なければfalseを返す
         * 成功なら照合文字列（小文字）を返す
         */
        $astr = mb_strtolower($targetstr);
        $bstr = mb_strtolower($findstr);
        if (preg_match('/'.$bstr.'/', $astr)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getFieldType($darray, $fieldname)
    {
        /**
         * dataarray = 連想配列
         * fieldname =　対象フィールド名
         * int型タイプがあればtypeを返す
         * int以外ならfalseを返す
         */
        foreach ($darray as $val) {
            if ($this->compStr(':'. $val["Field"], $fieldname)) {
                return $this->compStr($val["Type"], "int");
            }
        }
        return false;
    }

    public function getTimeDifference($defoulttzstr, $timezonstr)
    {
        /**
         * defoulttzstr = デフォルトタイムゾーン文字列
         * timezonstr = 対象タイムゾーン文字列
         * 時差数値を返す
         */
        $tzstr = date_default_timezone_get();
        date_default_timezone_set($defoulttzstr);
        $ndt = new \DateTime();
        $ndt->setTimeZone(new \DateTimeZone($defoulttzstr));
        $ndt->setTimeZone(new \DateTimeZone($timezonstr));
        date_default_timezone_set($timezonstr);
        $edt = $ndt->format(\DateTime::ISO8601);
        date_default_timezone_set($tzstr);
        return (int)substr($edt, strlen($edt) -5, 3);
    }

    private function constA()
    {
        $A_arr = array(
            "A1" => 1.67195956943544,
            "A2" => 0.74007712664196,
            "A3" => 1.79828214918766,
            "A4" => 10.1913249740675,
            "A5" => 5.07138806965905,
            "A6" => 364.130812008484
        );
        return $A_arr;
    }
    private function constB()
    {
        $B_arr = array(
            "B1" => -0.0006659,
            "B2" => 7.196,
            "B3" => -3.892,
            "B4" => -1.297
        );
        return $B_arr;
    }
    public function getHD($T, $R)
    {
        /**
         * 飽差計算
         */
        $A = 100;
        $B = 217;
        $C = 273.15;
        $D = 6.1078;
        $E = 10;
        $F = 7.5;
        $G = 237.3;
        $eT = $D * $E **(($F * $T / ($T + $C)));
        $VH = $B * $eT / ($T + $G);
        $HD = ($A - ($R * 100)) * $VH / 100;
        return round($HD, 1);
    }
    public function getHeatIndex($Ta, $RH)
    {
        /**
         * 暑さ指数計算
         */
        $A = $this->constA();
        $B = $this->constB();
        $WBGT = $A['A1']+$A['A2']*$Ta+$A['A3']*($RH*$A['A4']*exp(($A['A5']*$Ta)/($A['A6']+$Ta)))
        +$B['B1']*($Ta-$B['B2'])**2+$B['B3']*($RH-$B['B4'])**2;
        return round($WBGT, 1);
    }
    public function chDevice()
    {
    
        $ua = $_SERVER['HTTP_USER_AGENT'];
            
        if ((strpos($ua, 'Android') !== false) &&
        (strpos($ua, 'Mobile') !== false) ||
        (strpos($ua, 'iPhone') !== false) ||
        (strpos($ua, 'Windows Phone') !== false)) {
            // スマホからのアクセス
            $check_device = "mobile";
        } elseif ((strpos($ua, 'Android') !== false) || (strpos($ua, 'iPad') !== false)) {
            // タブレットからのアクセス
            $check_device = "tablet";
        } elseif ((strpos($ua, 'DoCoMo') !== false) ||
        (strpos($ua, 'KDDI') !== false) ||
        (strpos($ua, 'SoftBank') !== false) ||
        (strpos($ua, 'Vodafone') !== false) ||
        (strpos($ua, 'J-PHONE') !== false)) {
            // 携帯からのアクセス
            $check_device = "old-phone";
        } else {
            // PCからのアクセス
            $check_device = "pc";
        }
    
        return $check_device;
    }
}
