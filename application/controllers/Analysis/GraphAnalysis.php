
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class GraphAnalysis extends MY_Controller
{
	public function __construct() {
        parent:: __construct();
			$this->load->helper('url');
			$this->load->library(array('session'));
			if (!isset($_SESSION['user_id'])) {
				redirect('/');
			}
			$this->load->model('sensor_Model');
			$this->load->helper('language');
			$this->load->helper('language');
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
		// $sensorinfo['Group']=$this->sensor_Model->GetGroupId($id[0]['ID']);
		$sensors=$this->sensor_Model->getSensorData($id[0]['ID']);
		$str='<ul class="filter-type" id="sensorType">';
		$n=1;
		foreach($sensors as $val){
			if($n==1) $str.='<li class="view-on" id="sensor'.$n.'"><a >'.$val['name'].'</a></li>';
			else $str.='<li class="" id="sensor'.$n.'"><a >'.$val['name'].'</a></li>';
			$n++;
		}
		$str.='</ul>';
		$sensorinfo['sensors']=$str;
		$data['unread']=$this->unread_message;
        $data['user_name']=$this->session->userdata('user_name');
        $this->load->view('header',$data);
		$this->load->view('analysis/graphAnalysis',$sensorinfo);
	}

	//--------------------------------------------------------------------
	
	public function getData()
	{
		$type = $this->input->post('type');
		$content = $this->input->post('content');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$data=$this->sensor_Model->getSensor($id[0]['ID'],$type,$content);
		$arrayData=[];
		foreach($data as $val){
			$unit=[];
			array_push($unit,strtotime($val->RTC)*1000,floatval($val->Temperature));
			array_push($arrayData,$unit);
		}
		$res=json_encode($arrayData);
		print_r($res);
	}
	public function checkSensor(){
		$type = $this->input->post('type');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$datas=$this->sensor_Model->getSensorData($id[0]['ID']);
		$str='<ul class="filter-type" id="sensorType">';$n=1;
		foreach($datas as $data){
			$check=$this->sensor_Model->checkSensor($type,$data['name'],$id[0]['ID']);
			if($check){
				if($n==1)$str.=' <li class="view-on" id="sensor'.$n.'"><a >'.$data['name'].'</a></li>';
				else $str.=' <li class="" id="sensor'.$n.'"><a >'.$data['name'].'</a></li>';
				$n++;
			}else{
				continue;
			}
		}
		$str.='</ul>';
		echo json_encode($str);
	}
	public function showAll(){
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		// $sensorinfo['Group']=$this->sensor_Model->GetGroupId($id[0]['ID']);
		$sensors=$this->sensor_Model->getSensorData($id[0]['ID']);
		$str='<ul class="filter-type" id="sensorType">';
		$n=1;
		foreach($sensors as $val){
			if($n==1) $str.='<li class="view-on" id="sensor'.$n.'"><a >'.$val['name'].'</a></li>';
			else $str.='<li class="" id="sensor'.$n.'"><a >'.$val['name'].'</a></li>';
			$n++;
		}
		$str.='</ul>';
		echo json_encode($str);
	}

}