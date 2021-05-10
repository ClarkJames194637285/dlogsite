
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class GraphComparison extends MY_Controller
{
	public function __construct() {
        parent:: __construct();
				$this->load->helper('url');
				$this->load->library(array('session'));
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
				$this->load->model('sensor_Model');
				$site_lang=$this->session->userdata('lang');
				if ($site_lang) {
					$this->lang->load(array('menu','analysis'),$site_lang);
					} else {
					$this->lang->load(array('menu','analysis'),'japanese');
				}
    }
	public function index()
	{
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$sensorinfo['sensors']=$this->sensor_Model->getSensorData($id[0]['ID']);
		$data['unread']=$this->unread_message;
        $data['user_name']=$this->session->userdata('user_name');
        $this->load->view('header',$data);
		$this->load->view('analysis/graphComparison',$sensorinfo);
	}

	//--------------------------------------------------------------------
	public function getData()
	{
		$type = $this->input->post('type');
		$content = $this->input->post('content');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$data=$this->sensor_Model->getSensor($id[0]['ID'],$type,$content);
		$arrayData=[];
		array_push($arrayData,$type);
		foreach($data as $val){
			$unit=[];
			array_push($unit,strtotime($val->RTC."+11 hours")*1000,floatval($val->Temperature));
			array_push($arrayData,$unit);
		}
		
		$res=json_encode($arrayData);
		print_r($res);
	}
}