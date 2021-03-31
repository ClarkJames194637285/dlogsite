
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
		$this->load->library('MethodClass');
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$pid=$this->sensor_Model->allSensorPid($id[0]['ID']);
		$alarmdata['his_list']=[];
		foreach($pid as $value){
			$temp=$this->sensor_Model->getHistoryData($id[0]['ID'],$value['ID']);
			if(!empty($temp)){
				foreach($temp as $val){
					array_push($alarmdata['his_list'],$val);
				}
			}
		}
		$data['unread']=$this->unread_message;
		$data['user_name']=$this->session->userdata('user_name');
		$this->load->view('header',$data);
		$this->load->view('alarmHistory',$alarmdata);
	}
}