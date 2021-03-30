
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class TransmissionType extends MY_Controller
{

	public function __construct() {
        parent:: __construct();

        $this->load->helper('url');
				$this->load->model('message');
				$this->load->library(array('session'));
				$this->load->helper('language');
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
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
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
        $data['user_name']=$_SESSION['user_name'];

				$this->load->view('header',$data);
				$this->load->view('message/transmissiontype');
       
	}

	//--------------------------------------------------------------------
	public function setConfig()
	{
		$data['isOpen']=$this->input->post('isOpen');
		$data['temperature']=$this->input->post('temperature');
		$data['humidity']=$this->input->post('humidity');
		$data['security']=$this->input->post('security');
		$data['UserID']=$_SESSION['user_id'];
		$data['isdelete']=0;
		$result=$this->message->setConfig($data);
		echo $result;
	}
}
