<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Captcha extends CI_Controller
{
    private const PERMITTED_CHARS = 'ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * 挿入する文字列のフォント(インストールされていないといけない)    (今回はWindowsに入ってたメイリオを使う)
     */
    private const AVAILABLE_FONTS = [
        'Windows' => [
            'C:\Windows\Fonts\ALGER.TTF',
            'C:\Windows\Fonts\VINERITC.TTF',
            'C:\Windows\Fonts\BAUHS93.TTF',
            'C:\Windows\Fonts\BERNHC.TTF',
            'C:\Windows\Fonts\BRADHITC.TTF',
            'C:\Windows\Fonts\TEMPSITC.TTF',
            'C:\Windows\Fonts\CHILLER.TTF',
            'C:\Windows\Fonts\BRUSHSCI.TTF',
            'C:\Windows\Fonts\COLONNA.TTF',
            'C:\Windows\Fonts\BROADW.TTF',
        ],
        'Darwin' => [
            '/System/Library/Fonts/SFNSItalic.ttf',
            '/System/Library/Fonts/NewYorkItalic.ttf',
        ]
    ];

    private const CAPTCHA_BACKGROUND_IMAGES_FOLDER = './assets/captcha_background_images';

    function __construct()
    {
        parent:: __construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->helper('directory');
        $this->load->library(array('session'));
    }

    public function generate()
    {
        $text = $this->createCaptchaText();
        $_SESSION['captcha_text'] = $text;
        $image = $this->createCaptchaImage($text);

        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    /**
     * @param int $size
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function createCaptchaText(int $size = 6)
    {
        if ($size < 0) {
            throw new InvalidArgumentException('size must be greater than 1');
        }

        $random_characters = [];

        $input_length = strlen(self::PERMITTED_CHARS);
        for ($i = 0; $i < $size; $i++) {
            $random_characters[] = self::PERMITTED_CHARS[mt_rand(0, $input_length - 1)];
        }

        return implode($random_characters);
    }

    private function getBackgroundImage()
    {
        $available_background_files = directory_map(self::CAPTCHA_BACKGROUND_IMAGES_FOLDER);

        return self::CAPTCHA_BACKGROUND_IMAGES_FOLDER . DIRECTORY_SEPARATOR . $available_background_files[array_rand($available_background_files)];
    }

    private function createCaptchaImage($text)
    {
        // コピー先画像作成
        $image = imagecreatefromjpeg($this->getBackgroundImage());

        // 挿入する文字列の色(白)
        $color = imagecolorallocate($image, 255, 255, 255);

        // 挿入する文字列のサイズ(ピクセル)
        try {
            $fontsize = random_int(50, 70);
        } catch (Exception $e) {
            $fontsize = 72;
        }

        // 挿入する文字列の角度
        $angle = 0;

        // 挿入位置
        $insert_positions = $this->createTextPositions(strlen($text), 10, 10 + $fontsize);

        $available_fonts = self::AVAILABLE_FONTS[PHP_OS_FAMILY];
        foreach ($insert_positions as $i => $position) {
            $fontfile = $available_fonts[array_rand($available_fonts)];
            // 文字列挿入
            imagettftext(
                $image,     // 挿入先の画像
                $fontsize,      // フォントサイズ
                $angle,     // 文字の角度
                $position[0],         // 挿入位置 x 座標
                $position[1],         // 挿入位置 y 座標
                $color,     // 文字の色
                $fontfile,  // フォントファイル
                $text[$i]);     // 挿入文字列
        }

        // ファイル名を指定して画像出力
        return $image;
    }

    private function createTextPositions(int $size, $start_x, $start_y)
    {
        $current_x = $start_x;
        $current_y = $start_y;
        $positions = [];
        for ($i = 0; $i < $size; $i++) {
            $positions[] = [$current_x, $current_y];
            $current_x = $current_x + 60 + random_int(-20, 20);;
            $current_y = $start_y + random_int(-5, 5);;
        }

        return $positions;
    }

    public static function is_valid($captcha)
    {
        $is_valid = $captcha && ($captcha == $_SESSION['captcha_text']);
        $_SESSION['captcha_text'] = ''; // reset old-captcha

        return $is_valid;
    }
}
