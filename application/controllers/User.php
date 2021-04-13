<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	function __construct() {
        parent:: __construct();
        $this->load->helper('url');
				$this->load->helper('cookie');
				$this->load->library(array('session'));
				$this->load->model('user_model');
				$this->load->helper('language');
				$site_lang=$this->session->userdata('lang');
				if ($site_lang) {
            $this->lang->load('auth',$site_lang);
        } else {
            $this->lang->load('auth','japanese');
        }
    }

	public function index()
	{
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$this->config->load('reCAPTURE_config');
		
		$this->load->view('login');
	}

	public function langset($language="")
	{
		$language = ($language != "") ? $language : "japanese";
		$this->session->set_userdata('lang', $language);
        redirect(base_url());
	}

	public function logout() {
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$this->config->load('reCAPTURE_config');
		$dlogdb = new Dbclass();
		$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
		$tzstr = date_default_timezone_get();
		$defoulttz = "Asia/Tokyo";
		date_default_timezone_set($defoulttz);
		$new_time = new \DateTime();
		$logouttime = new \DateTime($new_time->format('Y-m-d H:i:s'), new \DateTimeZone($defoulttz));
		$up_darry = array(
			'UserStateID' => 1,
			'LastLoginTime' => $logouttime->format('Y-m-d H:i:s')
		);
		$userlist = $dlogdb->dbUpdate($dbpdo, 'users', $up_darry, 'ID', $this->session->userdata('user_id'));
		$dlogdb = null;
		date_default_timezone_set($tzstr);
		if (isset($_COOKIE['BSCM'])) {
			$get_cookie = $_COOKIE['BSCM'];
			$cookie_arry = explode(',', $get_cookie);
			for ($i = 0; $i < count($cookie_arry); $i ++) {
				$keyval = explode(':', $cookie_arry[$i]);
				$get_data[$keyval[0]] = $keyval[1];
			}
			if ((int)$get_data['resaved'] == 0) {
				$cookiestr = null;
				$expiration_time = time() - 60 * 60 * 24 * 30;
				setcookie('BSCM', $cookiestr, $expiration_time);
				$get_data['resaved'] = 0;
				$resaved = "";
			} else {
				$get_data['resaved'] = 1;
				$resaved = "checked";
			}
		}
		$this->session->sess_destroy();
		redirect(base_url());
		
	}
}

