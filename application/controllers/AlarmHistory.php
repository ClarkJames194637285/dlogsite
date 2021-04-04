
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class AlarmHistory extends MY_Controller
{
	public function __construct() {
        parent:: __construct();
		$this->load->helper('url');
		$this->load->library(array('session'));
		$this->load->model('sensor_Model');
		if (!isset($_SESSION['user_id'])) {
			redirect('/');
		}
		$this->load->helper('language');
		$site_lang=$this->session->userdata('lang');
		if ($site_lang) {
			$this->lang->load(array('menu','monitoring'),$site_lang);
			} else {
			$this->lang->load(array('menu','monitoring'),'japanese');
		}
	}
	public function index()
	{
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$pid=$this->sensor_Model->allSensorPid($_SESSION['user_id']);
		$data['his_list']=[];
		foreach($pid as $id){
			$list=$this->sensor_Model->getHistoryData($id['ID']);
			if($list){
				array_push($data['his_list'],$list[0]);
			}
		}
		
		$data['unread']=$this->unread_message;
		$data['user_name']=$this->session->userdata('user_name');
		$this->load->view('header',$data);
		$this->load->view('alarmHistory',$data);
	}
}