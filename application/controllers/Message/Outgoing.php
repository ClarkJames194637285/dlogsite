
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Outgoing extends MY_Controller
{
	public function __construct() {
		
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->config('email');
		$this->load->model('message');
		$this->load->library(array('session'));
		if (!isset($_SESSION['user_id'])) {
			redirect('/');
		}
		$this->load->helper('language');
		$site_lang=$this->session->userdata('lang');
		if ($site_lang) {
			$this->lang->load(array('menu','message'),$site_lang);
				} else {
			$this->lang->load(array('menu','message'),'japanese');
		}
	}
	public function index()
	{
		$data['unread']=$this->unread_message;
		$data['user_name']=$_SESSION['user_name'];
        
		$this->load->view('header',$data);
		$this->load->view('message/outgoing');
	}

	//--------------------------------------------------------------------
	public function recordMessage()
	{
		$data['message'] = $this->input->post('message');
		$data['FromAccount']=$_SESSION['user_name'];
		$data['ToAccount']=$this->config->item('smtp_user');
		echo $this->message->recordMessage($data);
	}
}
