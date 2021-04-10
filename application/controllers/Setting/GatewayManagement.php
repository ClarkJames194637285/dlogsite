


<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class GatewayManagement extends MY_Controller
{
	function __construct() {
        parent:: __construct();
        $this->load->helper('url');
				$this->load->helper('cookie');
				$this->load->library(array('session'));
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
				if ($_SESSION['user_name']!=='admin') {
					$this->load->view('nonaccess');
				}
				$this->load->helper('language');
				$site_lang=$this->session->userdata('lang');
				if ($site_lang) {
					$this->lang->load(array('menu','setting'),$site_lang);
					} else {
					$this->lang->load(array('menu','setting'),'japanese');
				}
    }

	public function index()
	{
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$data['unread']=$this->unread_message;
        $data['user_name']=$this->session->userdata('user_name');
        $this->load->view('header',$data);
		$this->load->view('setting/GatewayManagement');
	}
	public function edit()
	{
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$data['user_name']=$this->session->userdata('user_name');
        $this->load->view('header',$data);
		$this->load->view('setting/GatewayManagement_Edit');
	}
}