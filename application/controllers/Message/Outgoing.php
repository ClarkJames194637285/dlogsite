
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
        
		$data['messages'] = $this->message->getMessageType();
		$this->load->view('header',$data);
		$this->load->view('message/outgoing',$data);
	}

	//--------------------------------------------------------------------
	public function recordMessage()
	{

		$data['messageType'] = $this->input->post('messageType');
		$data['message'] = $this->input->post('message');
		$data['FromAccount']=$_SESSION['user_name'];
		$data['ToAccount']=$this->config->item('smtp_user');
		$result1=$this->send($data);
		if($result1==true){
			echo $this->message->recordMessage($data);
		}else{
			echo 0;
		}
		// $this->sendMailtoAdmin($data);
		
	}
	function send($data) {
        
        $this->load->library('email');
		$config = array(
			'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
			'smtp_host' => 'localhost', 
			'smtp_port' => 25,
			'smtp_user' => 'clark@dlog.com',
			'smtp_pass' => 'qwert',
			'mailtype' => 'text', //plaintext 'text' mails or 'html'
			'smtp_timeout' => '4', //in seconds
			'charset' => 'utf-8',
			'wordwrap' => TRUE
		);
		
		$this->email->initialize($config);
        $from =$_SESSION['user_name'];
		$subject = $data['messageType'];
        $message = $data['message'] ;

        
        $this->email->set_newline("\r\n");
        $this->email->from($config['smtp_user'],$from);
        $this->email->to($config['smtp_user'],'admin');
        $this->email->subject($subject);
        $this->email->message($message);
		try {
			if($this->email->send()){
				return true;
			}else{
				return false;
			}
			
		}
		catch (exception $e) {
			//code to handle the exception
			error_log($e->getMessage(), 3, APPPATH."logs/test.log");
			return false;
		}
		
    }
}
