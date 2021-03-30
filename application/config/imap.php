<?php
defined('BASEPATH') || exit('No direct script access allowed');

$config['encrypto'] = 'tls';
$config['validate'] = true;
$config['host']     = '{127.0.0.1:110}INBOX';
$config['port']     = 110;
$config['username'] = 'clark@test.com';
$config['password'] = 'clark';

$config['folders'] = [
	'inbox'  => 'INBOX',
	'sent'   => 'Sent',
	'trash'  => 'Trash',
	'spam'   => 'Spam',
	'drafts' => 'Drafts',
];

$config['expunge_on_disconnect'] = false;

$config['cache'] = [
	'active'     => false,
	'adapter'    => 'file',
	'backup'     => 'file',
	'key_prefix' => 'imap:',
	'ttl'        => 60,
];