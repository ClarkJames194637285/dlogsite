<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    // 'smtp_host' => 'localhost', 
    'smtp_host' => 'localhost', 
    'smtp_port' => 25,
    'smtp_user' => 'clark@dlog.com',
    'smtp_pass' => 'qwert',
    'mailtype' => 'text', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'utf-8',
    'wordwrap' => TRUE
);
// $config['smtp_host'] = "smtp.lolipop.jp";
// $config['smtp_port'] = 587;
// $config['smtp_user'] = "info@noisy-hita-1879.boy.jp";
// $config['smtp_pass'] = 'd-9mKDJWgwTUb21-';
