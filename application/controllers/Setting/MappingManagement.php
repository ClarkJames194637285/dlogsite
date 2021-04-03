

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MappingManagement extends MY_Controller
{
	public function __construct() {
        parent:: __construct();
				$this->load->helper('url');
				$this->load->library(array('session'));
				$this->load->model('sensor_Model');
				$this->load->library('upload');
				if (!isset($_SESSION['user_id'])) {
					redirect('/');
				}
				if($this->roleid[2]!=="checked"){
					$this->load->view('nonaccess');
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
		$data['unread']=$this->unread_message;
        $data['user_name']=$this->session->userdata('user_name');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$mapId=$this->sensor_Model->GetMapId($id[0]['ID']);
		$str='';$n=1;
		foreach($mapId as $map){
			if($n==1){$datas['firstMap']=$map['name'];$datas['map_url']=base_url().$map['imageurl'];$datas['ID']=$map['ID'];}
			$str.='<div class="mapping-block flexlyr" id="map'.$map['ID'].'">
			<label class="container1">
				<input type="checkbox">
				<span class="checkmark"></span>
			</label>
			<a  onclick="editMap('.$map['ID'].')" class="edit-btn"></a>
			<p class="mapping-name">'.$map['name'].'</p>
			<a class="drop-btn"></a></div>';$n++;
		}
		$datas['mapName']=$str;
		$this->load->view('header',$data);
		$this->load->view('setting/mappingManagement',$datas);
	}

	//--------------------------------------------------------------------
	public function uploadImage() { 
		header('Content-Type: application/json');
		$map = $_POST['map'];
		$mapname = $this->input->post('mapname');
		$mapID = $this->input->post('mapid');
		list($type, $map) = explode(';',$map);
		list(, $map) = explode(',',$map);
		$map = base64_decode($map);
		$map_url ='assets/upload/'.$this->session->userdata('user_name').'/map_'.time().'.png';
		$path = 'assets/upload/'.$this->session->userdata('user_name').'/';
		if (!file_exists($map_url)) {
			mkdir($path, 0777, true);
		}
		file_put_contents($map_url, $map);
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		//Insert thank_photo URL to database
		if ($this->sensor_Model->set_map_Info($map_url,$mapname,$mapID,$id[0]['ID'])) {
			echo '<div id="editImage"><p class="map-name">'.$mapname.'</p> <div class="editimage"><img src="'.base_url().$map_url.'" alt=""></div></div> ';
		} else {
			echo false;
		}
	 }
	 public function mapList() { 
		$data['user_name']=$this->session->userdata('user_name');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$mapId=$this->sensor_Model->GetMapId($id[0]['ID']);
		$str='<div id="sortableMap">';
		foreach($mapId as $map){
			$str.='<div class="mapping-block flexlyr" id="map'.$map['ID'].'">
			<label class="container1">
				<input type="checkbox">
				<span class="checkmark"></span>
			</label>
			<a  onclick="editMap('.$map['ID'].')" class="edit-btn"></a>
			<p class="mapping-name">'.$map['name'].'</p>
			<a class="drop-btn"></a></div>';
		}
		$str.='</div>';
		echo json_encode($str);
	 }
	 public function getMap(){ 
		$data['user_name']=$this->session->userdata('user_name');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$mapID = $this->input->post('mapid');
		$data=$this->sensor_Model->getMapInfo($id[0]['ID'],$mapID);
		$data->imageurl=base_url().$data->imageurl;
		echo json_encode($data);
	 }
	 public function deleteMap(){ 
		$data = $this->input->post('data');
		foreach($data as $id){
			if($id=="")continue;
			$result=$this->sensor_Model->deleteMap($id);
		}
		echo $result;
	 }
	 public function mapDecide(){
		$data=$this->input->post('data');
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		foreach($data as $val){
			if($val[0]=="")continue;
			$result=$this->sensor_Model->mapDecide($id[0]['ID'],$val[0],$val[1]);
		}
		echo $result;
	}
}