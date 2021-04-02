

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MappingMonitoring extends MY_Controller
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
		$site_lang=$this->session->userdata('lang');
		if ($site_lang) {
			$this->lang->load(array('menu','monitoring'),$site_lang);
        } else {
			$this->lang->load(array('menu','monitoring'),'japanese');
        }
	}
	public function index()
	{
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
			$data['maps']=$this->sensor_Model->getMap($id[0]['ID']);
			$data['unregSensor']=$this->unregisteredSensor($data['maps']);
			if(empty($data['maps'])){
				$data['mapUrl']="";
				$data['mapName']="地図なし";
			}else{
				foreach($data['maps'] as $key=>$val){
					$data['mapName']=$val['name'];
					$receive=$this->mapSensor($val['ID']);
					break;
				}
			}
			$data['sensors'].=$receive['sensorName'];
			$data['mapSensors']=$receive['mapSensors'];
			$data['mapUrl']=$receive['mapUrl'];
			$this->load->view('mappingMonitoring',$data);
			
	}

	//--------------------------------------------------------------------
	public function showMapSensor()
	{
		$mapId = $this->input->post('mapId');
		$data=[];
		$receive=$this->mapSensor($mapId);
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$data['maps']=$this->sensor_Model->getMap($id[0]['ID']);
		$data['unregSensor']=$this->unregisteredSensor($data['maps']);
		$data['sensorName'].=$receive['sensorName'];
		$data['mapSensors']=$receive['mapSensors'];
		$data['mapUrl']=$receive['mapUrl'];
		$data['mapName']=$receive['mapName'];
		echo json_encode($data);
	}

	public function unregisteredSensor($maps)
	{
		$n=1;
		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		$data['user_name']=$_SESSION['user_name'];
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$maps=$this->sensor_Model->getMap($id[0]['ID']);
		$pid=$this->sensor_Model->allSensorPid($id[0]['ID']);
		foreach($pid as $sensorval){
			$check=false;
			foreach($maps as $map){
					if($sensorval['RegionID']==$map['ID']){
						$check=true;break;
					}
			}
			if(!$check){
				$sensorInfo=$this->sensor_Model->SensorInfo($sensorval['ID']);
				$temp=$sensorInfo[0]['Temperature'];
				if(empty($sensorInfo))continue;
				if($sensorInfo[0]['Humidity']>0&&($sensorInfo[0]['Humidity']<100)){
					$hum=$sensorInfo[0]['Humidity'];
				}else{
					$mapSensor.='<div class="sensorGroup senseor-icon icon-03" id="sensor-0'.$n++.'" onclick="registerSensor('.$sensorval['ID'].')">
					<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
				</div>';continue;
				}
				$ET=6.1078*pow(10,(7.5*$temp/($temp+273.15)));
				$VH=217*$ET/($temp+273.15);
				// echo $VH.'<br>';
				$HD=(100-$hum)*$VH/100;
				// echo $HD.'<br>';
				$wbgt=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
				$mapSensor.='<div class="sensorGroup " id="sensor-0'.$n++.'" onclick="registerSensor('.$sensorval['ID'].')">';
					$mapSensor.='<div class="senseor-icon icon-01 layer1">
						<div class="top-circle '.$this->tempComptop($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
						<div class="bottom-circle '.$this->humidityComp($hum).'"><p>'.round($hum*100,1).'<span>%</span></p></div>
						<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-01 layer2" style="display:none;">
						<div class="top-circle '.$this->tempComptop($temp).'"></div>
						<div class="bottom-circle '.$this->humidityComp($hum).'"></div>
						<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-03 layer3" style="display:none;">
						<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-06 layer4" style="display:none;">
						<div class="all-circle '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-05 layer5" style="display:none;">
							<div class="all-circle '.$this->VhComp($VH).'"><p>'.round($VH,1).'<br><span>mg/m3</span></p></div>
						</div>';
					$mapSensor.='</div>';
			}
		}
		return $mapSensor;
	}

	public function registerSensor()
	{
		$mapId = $this->input->post('mapId');
		$pid = $this->input->post('pid');
		$result=$this->sensor_Model->updateMapInfo($pid,$mapId);
		echo $result;
	}

	public function mapSensor($mapId){
		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		$data['user_name']=$_SESSION['user_name'];
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$data['maps']=$this->sensor_Model->getMap($id[0]['ID']);
		$n=1;
		$mapSensors='<div id="mapSensor">';
		foreach($data['maps'] as $val){
			if($val['ID']!==$mapId)continue;
			$mapUrl=$val['imageurl'];$mapName=$val['name'];
			$sensors=$this->sensor_Model->getMapSensor($val['ID'],$id[0]['ID']);
			$sensorName='<ul class="sensor-item" id="sensorName">';
			$mapSensor='';
			foreach($sensors as $sensor){
				$sensorInfo=$this->sensor_Model->SensorInfo($sensor->ID);
				$temp=$sensorInfo[0]['Temperature'];
				if(empty($sensorInfo))continue;
				$sensorName.='<li class="view-on"><a>'.$sensor->ProductName.'</a></li>';
				if($sensorInfo[0]['Humidity']>0&&($sensorInfo[0]['Humidity']<100)){
					$hum=$sensorInfo[0]['Humidity'];
				}else{
					$mapSensor.='<div class="sensorGroup senseor-icon icon-03" id="sensor-0'.$n++.'">
					<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
				</div>';continue;
				}
				
				$ET=6.1078*pow(10,(7.5*$temp/($temp+273.15)));
				$VH=217*$ET/($temp+273.15);
				// echo $VH.'<br>';
				$HD=(100-$hum)*$VH/100;
				// echo $HD.'<br>';
				$wbgt=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
				$mapSensor.='<div class="sensorGroup" id="sensor-0'.$n++.'">';
					$mapSensor.='<div class="senseor-icon icon-01 layer1">
						<div class="top-circle '.$this->tempComptop($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
						<div class="bottom-circle '.$this->humidityComp($hum).'"><p>'.round($hum*100,1).'<span>%</span></p></div>
						<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-01 layer2" style="display:none;">
						<div class="top-circle '.$this->tempComptop($temp).'"></div>
						<div class="bottom-circle '.$this->humidityComp($hum).'"></div>
						<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-03 layer3" style="display:none;">
						<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-06 layer4" style="display:none;">
						<div class="all-circle '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
					</div>';
					$mapSensor.='<div class="senseor-icon icon-05 layer5" style="display:none;">
							<div class="all-circle '.$this->VhComp($VH).'"><p>'.round($VH,1).'<br><span>mg/m3</span></p></div>
						</div>';
					$mapSensor.='</div>';
			}
			$sensorName.='</ul>';
			break;
			$mapSensor='</div>';
		}
		$mapSensors.=$mapSensor.'</div>';
		$data['mapSensors'].=$mapSensors;
		$data['mapUrl']='<img src="'.base_url().$mapUrl.'" alt="" id="mapUrl">';
		$data['mapName']='<h2 class="block-title" id="mapName">'.$mapName.'</h2>';
		$data['sensorName']=$sensorName;
		return $data;
	}
	public function dateTimeMap(){
		$mapId = $this->input->post('mapId');
		$datetime = $this->input->post('datetime');
		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$data['maps']=$this->sensor_Model->getMap($id[0]['ID']);
		$n=1;
		$mapSensors='<div id="mapSensor">';
		foreach($data['maps'] as $val){
			if($val['ID']!==$mapId)continue;
			$sensors=$this->sensor_Model->getMapSensor($val['ID'],$id[0]['ID']);
			$sensorName='<ul class="sensor-item" id="sensorName">';
			$mapSensor='';
			foreach($sensors as $sensor){
				$sensorName.='<li class="view-on"><a>'.$sensor->ProductName.'</a></li>';
				$sensorInfo=$this->sensor_Model->dateTimeMap($sensor->ID,$datetime);
				if(empty($sensorInfo))continue;
				if($sensorInfo[0]['Humidity']>0&&($sensorInfo[0]['Humidity']<100)){
					$hum=$sensorInfo[0]['Humidity'];
				}else{
					$mapSensor.='<div class="sensorGroup senseor-icon icon-03" id="sensor-0'.$n++.'">
					<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
				</div>';continue;
				}
				$temp=$sensorInfo[0]['Temperature'];
				$ET=6.1078*pow(10,(7.5*$temp/($temp+273.15)));
				$VH=217*$ET/($temp+273.15);
				// echo $VH.'<br>';
				$HD=(100-$hum)*$VH/100;
				// echo $HD.'<br>';

				$wbgt=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
				$mapSensor.='<div class="sensorGroup" id="sensor-0'.$n++.'">';
				$mapSensor.='<div class="senseor-icon icon-01 layer1">
					<div class="top-circle '.$this->tempComptop($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
					<div class="bottom-circle '.$this->humidityComp($hum).'"><p>'.round($hum*100,1).'<span>%</span></p></div>
					<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
				</div>';
				$mapSensor.='<div class="senseor-icon icon-01 layer2" style="display:none;">
					<div class="top-circle '.$this->tempComptop($temp).'"></div>
					<div class="bottom-circle '.$this->humidityComp($hum).'"></div>
					<div class="heat_level '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
				</div>';
				$mapSensor.='<div class="senseor-icon icon-03 layer3" style="display:none;">
					<div class="all-circle '.$this->tempComp($temp).'"><p>'.round($temp,1).'<span>℃</span></p></div>
				</div>';
				$mapSensor.='<div class="senseor-icon icon-06 layer4" style="display:none;">
					<div class="all-circle '.$this->tempComp($HD).'"><img src="'.base_url().'assets/img/asset_35.png" alt=""></div>
				</div>';
				$mapSensor.='<div class="senseor-icon icon-05 layer5" style="display:none;">
						<div class="all-circle '.$this->VhComp($VH).'"><p>'.round($VH,1).'<br><span>mg/m3</span></p></div>
					</div>';
				$mapSensor.='</div>';
			}
			$sensorName.='</ul>';
			break;
			$mapSensor='</div>';
		}
		$mapSensors.=$mapSensor.'</div>';
		echo $mapSensors;
	}
	public function tempComp($temp){
		if(($temp<25)&&($temp>10))return 'lvl1';
		else if($temp<28)return 'lvl2';
		else return 'lvl3';
	}
	public function tempComptop($temp){
		if(($temp<20)&&($temp>10))return 't-color1';
		else if($temp<30)return 't-color2';
		else if($temp<40)return 't-color3';
		else if($temp<50)return 't-color4';
		else return 't-color5';
	}
	public function humidityComp($humidity){
		if(($humidity>0)&&($humidity<20))return 'h-color1';
		else if($humidity<40)return 'h-color2';
		else if($humidity<60)return 'h-color3';
		else if($humidity<80)return 'h-color4';
		else return 'h-color5';
	}
	public function VhComp($VH){
		if($VH>=6)return 's-color1';
		else if($VH<6&&$VH>=2.9)return 's-color2';
		else return 's-color3';
	}
	
}

