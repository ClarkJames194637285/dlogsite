
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class ListManagement extends MY_Controller
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
					$this->lang->load(array('menu','setting'),$site_lang);
					} else {
					$this->lang->load(array('menu','setting'),'japanese');
				}
    }
	public function index()
	{
		if($this->roleid[1]!=="checked"){
			$this->load->view('nonaccess');
		}
		$data['unread']=$this->unread_message;
        $data['user_name']=$this->session->userdata('user_name');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$groupId=$this->sensor_Model->GetGroupId($id[0]['ID']);
		$str='';
		foreach($groupId as $group){
			$str.='<div class="list-block flexlyr">
				<p class="mapping-name">'.$group['GroupName'].'</p>
				<a class="drop-btn"></a>
			</div>';
		}
		$data['groupName']=$str;

		$str='';
		$sensors=$this->sensor_Model->allSensorPid($id[0]['ID']);
		foreach($sensors as $sensor){
			$str.='<div class="list-block flexlyr">
					<p class="mapping-name">'.$sensor['ProductName'].'</p>
					<a class="drop-btn"></a>
				</div>';
		}
		$data['sensorName']=$str;
		
		$this->load->view('setting/listManagement',$data);
	}

	//--------------------------------------------------------------------
	public function groupUndo(){
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$groupId=$this->sensor_Model->GetGroupId($id[0]['ID']);
		$str='<div id="sortableGroup">';
		foreach($groupId as $group){
			$str.='<div class="list-block flexlyr">
				<p class="mapping-name">'.$group['GroupName'].'</p>
				<a class="drop-btn"></a>
			</div>';
		}
		$str.='</div>';
		echo json_encode($str);
	}
	public function sensorUndo(){
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$str='<div id="sortableSensor">';
		$sensors=$this->sensor_Model->allSensorPid($id[0]['ID']);
		foreach($sensors as $sensor){
			$str.='<div class="list-block flexlyr">
					<p class="mapping-name">'.$sensor['ProductName'].'</p>
					<a class="drop-btn"></a>
				</div>';
		}
		$str.='</div>';
			echo json_encode($str);
	}
	public function groupDecide(){
		$data=$this->input->post('data');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		foreach($data as $val){
			if($val[0]=="")continue;
			$result=$this->sensor_Model->groupDecide($id[0]['ID'],$val[0],$val[1]);
		}
		echo $result;
	}
	public function sensorDecide(){
		$data=$this->input->post('data');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		foreach($data as $val){
			if($val[0]=="")continue;
			$result=$this->sensor_Model->sensorDecide($id[0]['ID'],$val[0],$val[1]);
		}
		echo $result;
	}
}