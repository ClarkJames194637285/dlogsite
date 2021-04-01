

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class UserOperation extends MY_Controller
{

	function __construct() {
        parent:: __construct();
        $this->load->helper('url');
				$this->load->helper('cookie');
				$this->load->library(array('session'));
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
				$this->load->helper('language');
				$site_lang=$this->session->userdata('lang');
				if ($site_lang) {
					$this->lang->load('menu',$site_lang);
						} else {
					$this->lang->load('menu','japanese');
				}
    }

	public function index()
	{
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			$this->config->load('db_config');
			$this->config->load('openSSL_config');
			$this->load->library('User_logic');
			$this->load->view('header',$data);
			$this->load->view('useroperation/publish_info');
	}
	public function publish_news()
	{
			$this->config->load('db_config');
			$this->config->load('openSSL_config');
			$this->load->library('User_logic');
			$this->load->view('useroperation/publish_news');
	}
	public function publish_conf()
	{
		$data['unread']=$this->unread_message;
		$data['user_name']=$_SESSION['user_name'];
		$this->load->view('header',$data);
		$this->load->view('useroperation/publish_conf');
	}
	
}