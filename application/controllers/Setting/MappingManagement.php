

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
		$datas['unread']=$this->unread_message;
        $datas['user_name']=$this->session->userdata('user_name');
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
			<p class="mapping-name">'.$map['name'].'</p>
			<a class="drop-btn"></a></div>';$n++;
		}
		$datas['mapName']=$str;
	// 	.' <div class="mapping-block flexlyr">
	// 		<label class="container1">
	// 				<input type="checkbox">
	// 				<span class="checkmark"></span>
	// 		</label>
	// 		<p class="mapping-name">名称未設定</p>
	// 		<a class="drop-btn"></a>
	// </div>'
		// $this->load->view('header',$data);
		$this->load->view('setting/mappingManagement',$datas);
	}
	public function add()
	{
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			$this->load->view('header',$data);
			$this->load->view('setting/mappingManagement_Edit');
			
	}
	public function edit()
	{
			$mapId=$this->input->get('mapId');
			$mapName=$this->input->get('mapName');
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			$map['mapId']=$mapId;
			$map['mapName']=$mapName;
			$this->load->view('header',$data);
			$this->load->view('setting/mappingManagement_Edit',$map);
			
	}
	public function update()
	{
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			$mapName = $this->input->post('mapName');
			$mapId = $this->input->post('mapId');
			if($mapId){
					$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
					$this->sensor_Model->setMap($mapName,$mapId,$id[0]['ID']);
					redirect('setting/mappingManagement');
			}else{
					$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
					$data=$this->sensor_Model->GetMapId($id[0]['ID']);
					$check=false;
					foreach($data as $map){
						if($map['name']==$mapName){
							$this->session->set_flashdata('error', '同じ名前のマップが既に存在しています。');
							$check=true;
							break;
						}
					}
					if($check){
						$this->load->view('header',$data);
						$this->load->view('setting/mappingManagement_Edit');
					}else{
						$mapId="";
						$this->sensor_Model->setMap($mapName,$mapId,$id[0]['ID']);
						redirect('setting/mappingManagement');
					}
				
			}
	}
	//--------------------------------------------------------------------
	public function uploadImage() { 
		header('Content-Type: application/json');
		$map = $_FILES['mapImage']['tmp_name'];
		$mapname = $this->input->post('mapname');
	
		$map_url ='assets/upload/'.$this->session->userdata('user_name').'/map_'.time().'.png';
		$path = 'assets/upload/'.$this->session->userdata('user_name').'/';
		if (!file_exists(FCPATH.$map_url)) {
			mkdir(FCPATH.$path, 0777, true);
		}
		if(move_uploaded_file($map,FCPATH.$map_url)){
				$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
				//Insert thank_photo URL to database
				if ($this->sensor_Model->set_map_Info($map_url,$mapname,$id[0]['ID'])) {
					echo true;
				} else {
					echo false;
				}
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
		if($result==true)	redirect('/setting/mappingmanagement');
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