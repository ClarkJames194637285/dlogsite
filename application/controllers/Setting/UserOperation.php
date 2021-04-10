

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
	public function publish_upload()
	{
		if (isset($_FILES['userfile'])) {
			$tempfile = $_FILES['userfile']['tmp_name'];
			//info.htmlに上書き
			$filename = './info.html';
			if (is_uploaded_file($tempfile)) {
				if (move_uploaded_file($tempfile, $filename)) {
					redirect(base_url().'setting/useroperation/publish_conf');
				} else {
					$text = "ファイルをアップロードできません。";
				}
			} else {
				$text = "ファイルが選択されていません。";
			};
		}
		if (isset($text)) {
			$alert = "<script type='text/javascript'>alert('" . $text . "');</script>";
			echo $alert;
		}
	}
	public function publish_conf()
	{
		$data['unread']=$this->unread_message;
		$data['user_name']=$_SESSION['user_name'];
		$this->load->view('header',$data);
		$this->load->view('useroperation/publish_conf');
	}
	public function uploadtext(){
			$text = $this->input->post('text');
			$filename = $this->input->post('filename');
			$this->load->helper('file');
			// $dir=APPPATH.'advertisement/'.$filename;
			$dir=APPPATH.'advertisement/advertisement.txt';
			if ( ! write_file($dir, $text))
			{
							echo false;
			}
			else
			{
						echo true;
			}
	}
	
}