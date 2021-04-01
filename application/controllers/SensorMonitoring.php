

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class SensorMonitoring extends MY_Controller
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
			$data['unread']=$this->unread_message;
			$data['user_name']=$_SESSION['user_name'];
			// $this->load->view('sensorMonitoring');
			$sensorinfo['Sensors']=$this->ShowSensor($_SESSION['user_name']);
			$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
			$sensorinfo['Group']=$this->sensor_Model->GetGroupId($id[0]['ID']);
			$this->load->helper('language');
			$site_lang=$this->session->userdata('lang');
			if ($site_lang) {
				$this->lang->load(array('menu','monitoring'),$site_lang);
					} else {
				$this->lang->load(array('menu','monitoring'),'japanese');
					}
			$this->load->view('header',$data);
			$this->load->view('sensorMonitoring',$sensorinfo);
	}

	//--------------------------------------------------------------------
	public function ShowSensor($user){
		/**
		 * constant array for calculating the WBGT
		 */
		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		
		/**
		 * the function which get the groupId from product table
		 */
		$id=$this->sensor_Model->getUserId($user);
		$groupId=$this->sensor_Model->GetGroupId($id[0]['ID']);
		
		/**
		 * the function for getting the pid ,productname from product table
		 */
		$data=$this->sensor_Model->allSensorPid($id[0]['ID']);
		$Sensors='<div id="content">';
		foreach($groupId as $key=>$groupVal){

			$Sensors.='<div class="group'.$groupVal['ID'].' group"><h2 class="block-title">'.$groupVal['GroupName'].'</h2>
			<div class="block-grid flexlyr" >';
			foreach($data as $datavalue){
		
				$str="";
				if($datavalue['GroupID']==$groupVal['ID']){
					/**
					 * get the latest value from terminalhistory table.
					 *  PID,Temperature,Humidity,Pressure,RTC 
					 */
					$check=false;
					$sensorInfo=$this->sensor_Model->SensorInfo($datavalue['ID']);
						if(($sensorInfo[0]['Temperature']>-100)&&($sensorInfo[0]['Temperature'])<500){
							$temp=$sensorInfo[0]['Temperature'];
						}
						else{
							$str.='<div class="block-sensor offline bg-gray">
									<p class="sensor-label">'.$datavalue['ProductName'].'</p>
									<p class="sensor-status sensor-01-0">&nbsp;</p>
									<p class="sensor-status sensor-02-0">&nbsp;</p>
									<p class="sensor-status sensor-03-0">&nbsp;</p>
									<p class="sensor-status sensor-04-0" style="display:none;">&nbsp;</p>
									<div class="sensor-grid">
										<p class="sensor-infor"></p>
									</div>
								</div>
								';$Sensors.=$str;continue;
						}
						if(($sensorInfo[0]['Humidity']>0)&&($sensorInfo[0]['Humidity']<100)){
							$hum=$sensorInfo[0]['Humidity'];
						}else{
							$check=true;
						}
						if (empty($sensorInfo)) {
							$str.='<div class="block-sensor offline bg-gray">
									<p class="sensor-label">'.$datavalue['ProductName'].'</p>
									<p class="sensor-status sensor-01-0">&nbsp;</p>
									<p class="sensor-status sensor-02-0">&nbsp;</p>
									<p class="sensor-status sensor-03-0">&nbsp;</p>
									<p class="sensor-status sensor-04-0" style="display:none;">&nbsp;</p>
									<div class="sensor-grid">
										<p class="sensor-infor"></p>
									</div>
								</div>
								';
								
							}
						else{
							
							
								/**
								 * ET means water vapor pressure
								 * VH means saturated water vapor amount
								 * HD means 飽差
								 * */
								if(!$check){
									$ET=6.1078*pow(10,(7.5*$temp/($temp+273.15)));
									$VH=217*$ET/($temp+273.15);
									// echo $VH.'<br>';
									$HD=(100-$hum)*$VH/100;
									// echo $HD.'<br>';
				
									$wbgt=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
								
									$bool=$this->sensor_Model->requireBattery($datavalue['ID']);
									if($bool&&($wbgt>25))
										$str.='<div class="block-sensor battery warning';	
									else if($bool&&($wbgt<25))
										$str.='<div class="block-sensor battery';
									else if(!$bool&&($wbgt>25))
									$str.='<div class="block-sensor warning';
									else $str.='<div class="block-sensor';
									foreach($sensorInfo[0] as $key=>$value){
										if($key=='PID'){
											if($wbgt>=27.9)$str.=' bg-red';
											if($wbgt<27.9&&$wbgt>=25)	$str.=' bg-amber';
											if($wbgt<=24.9&&$wbgt>0)$str.=' bg-green';
											$str.=' humidity" id="sensor'.$value.'">
												<p class="sensor-label">'.$datavalue['ProductName'].'</p>
												
												<p class="sensor-status sensor-01-0">';
										}
										if($key=="Temperature")
											$str.=round($value,1).'<span>℃</span></p>
												<p class="sensor-status sensor-02-0">';
										if($key=="Humidity")
											$str.=round($value*100).'<span>%</span></p>
												<p class="sensor-status sensor-03-0">'.round($HD,1).'<span>℃</span></p>
												<p class="sensor-status sensor-04-0" style="display:none;">'.round($HD).'<span>g/m3</span></p>';
										
				
										if($key=="Pressure"){
											// $str.=round($value,1).'<span>Pa</span></p>
											// 	<div class="sensor-grid">';
											$str.='
												<div class="sensor-grid">';
											if($temp>40)
												$str.='<p class="sensor-icon sensor-01-red"></p>';
											else if($temp>25)
												$str.='<p class="sensor-icon sensor-01-amb"></p>';
											if($hum*100<50)	
												$str.='<p class="sensor-icon sensor-02-amb"></p>';
											if($HD>28)	
												$str.='<p class="sensor-icon sensor-03-red"></p>';
											else if($HD>25)	
												$str.='<p class="sensor-icon sensor-03-amb"></p>';
											if($VH>5.9)
												$str.='<p class="sensor-icon sensor-04-amb"></p>';
											
													
												$str.='<p class="sensor-infor"></p>
													</div>
												</div>';
										}
										
									}
								}else{
									$bool=$this->sensor_Model->requireBattery($datavalue['ID']);
									if($bool&&($temp>40))
										$str.='<div class="block-sensor battery warning';	
									else if($bool&&($temp<40))
										$str.='<div class="block-sensor battery';
									else if(!$bool&&($temp>25))
									$str.='<div class="block-sensor warning';
									else $str.='<div class="block-sensor';
									foreach($sensorInfo[0] as $key=>$value){
										if($key=='PID'){
											if($temp>=27.9)$str.=' bg-red';
											if($temp<27.9&&$temp>=25)	$str.=' bg-amber';
											if($temp<=24.9&&$temp>0)$str.=' bg-green';
											$str.=' temperature" id="sensor'.$value.'">
												<p class="sensor-label">'.$datavalue['ProductName'].'</p>
												
												<p class="sensor-status sensor-01-0">';
										}
										if($key=="Temperature")
											$str.=round($value,1).'<span>℃</span></p>
												<p class="sensor-status sensor-02-0">';
										
										if($key=="Pressure"){
											// $str.=round($value,1).'<span>Pa</span></p>
											// 	<div class="sensor-grid">';
											$str.='
												<div class="sensor-grid">';
											if($temp>40)
												$str.='<p class="sensor-icon sensor-01-red"></p>';
											else if($temp>25)
												$str.='<p class="sensor-icon sensor-01-amb"></p>';
													
												$str.='<p class="sensor-infor"></p>
													</div>
												</div>';
										}
										
									}
								}
						}
						$Sensors.=$str;
					
				}
				
			}
			$Sensors.='<div class="block-sensor block-sensor-new" onclick="newSensor('.$groupVal['ID'].')" >
							<a class="sensor-add"><img src="'.base_url().'assets/img/sensor_add.png" alt=""></a>
						</div>
					</div>
					</div>';
			
		}
		$Sensors.='</div>';
		return $Sensors;
	}
	public function getTerminalInfo(){
		$this->load->model('sensor_Model');
		$data=$this->ShowSensor($_SESSION['user_name']);
		echo json_encode($data);
	}
	public function registerSensor(){
		$this->load->model('sensor_Model');
		$data['IMEI'] = $this->input->post('IMEI');
		$data['groupId'] = $this->input->post('groupId');
		$data['sensorId'] = $this->input->post('sensorId');
		$data['name'] = $this->input->post('name');
		$id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$data['userId']=$id[0]['ID'];
		
		$result=$this->sensor_Model->setSensor($data);
		echo json_encode($result);
		
	}
	public function getSensorInfo(){
		/**
		 * constant array for calculating the WBGT
		 */
		header("Access-Control-Allow-Origin: *");

		$id=$this->input->post('id');
		$pwd=$this->input->post('password');
		$password = md5($pwd);

		$count['working']=0;
		$count['warning1']=0;
		$count['warning2']=0;
		$count['notWorking']=0;
		$currentTime=date('Y-m-j H:i:s');

		
		$this->load->model('sensor_Model');
		/**
		 * user confirm fucntion
		 */
		if(is_null($id)) redirect('/');
		$result=$this->sensor_Model->userconfirm($id);
		$existPass=$result[0]['Password'];
		
		if($existPass!==$password){ 
			echo "the password is incorrect.";
			return false;}
		/**
		 * the function which get the groupId from product table
		 */
		// $id=$this->sensor_Model->getUserId($user);
		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		
		/**
		 * the function which get the groupId from product table
		 */
		// $id=$this->sensor_Model->getUserId($_SESSION['user_name']);
		$groupId=$this->sensor_Model->GetGroupId($id);
		
		/**
		 * the function for getting the pid ,productname from product table
		 */
		$data=$this->sensor_Model->allSensorPid($id);
		foreach($groupId as $groupVal){
			foreach($data as $val){
				/**
				 * get the latest value from terminalhistory table.
				 *  PID,Temperature,Humidity,Pressure,RTC 
				 */
				if($val['GroupID']==$groupVal['ID']){
					$sensorInfo="";
					$sensorInfo=$this->sensor_Model->SensorInfo($val['ID']);
					if (empty($sensorInfo)) {
						$count['notWorking']++;
						continue;
							
					}else{
						if(($sensorInfo[0]['Temperature']>-100)&&($sensorInfo[0]['Temperature'])<500){
							$temp=$sensorInfo[0]['Temperature'];
						}
						else{
							$count['notWorking']++;
							continue;
						}
						if(($sensorInfo[0]['Humidity']>0)&&($sensorInfo[0]['Humidity']<100)){
							$hum=$sensorInfo[0]['Humidity'];
						}else{
							if($sensorInfo[0]['Temperature']<=10){
								$count['warning1']++;
								continue;
							}
							if(($sensorInfo[0]['Temperature']>10)&&($sensorInfo[0]['Temperature']<=25)){
								$count['working']++;
								continue;
							}
							if(($sensorInfo[0]['Temperature']>25)&&($sensorInfo[0]['Temperature']<=40)){
								$count['warning1']++;continue;
							}
							if($sensorInfo[0]['Temperature']>40){
								$count['warning2']++;continue;
							}
						}
						// $currentTime=strtotime(date('Y-m-j H:i:s'));
						$currentTime=date('Y-m-j H:i:s');
						$currentDay=explode(" ",$currentTime);
						$current=explode("-",$currentDay[0]);
						$cuDay=$current[2];

						$getTime=explode(".",$sensorInfo[0]['RTC']);
						$getDay=explode(" ",$getTime[0]);
						$get=explode("-",$getDay[0]);
						$geDay=$get[2];
						
						$diffDay=number_format($cuDay)-number_format($geDay);
						if($diffDay>1){
							$count['notWorking']++;
							continue;
						}
						
						/**
						 * ET means water vapor pressure
						 * VH means saturated water vapor amount
						 * HD means 飽差
						 * */
						
						$ET=6.1078*pow(10,(7.5*$temp/($temp+273.15)));
						$VH=217*$ET/($temp+273.15);
						// echo $VH.'<br>';
						$HD=(100-$hum)*$VH/100;
						// echo $HD.'<br>';

						$temp=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
						if($wbgt>=28){ 
							$count['warning2']++;
							continue;
						}
						if(($wbgt>=25)&&($wbgt<27.9)) {
							$count['warning1']++;
							continue;
						}
						$count['working']++;
						
					}
				}

				
			}
				// if($SensorInfo)
			}
		// printf("working : %d\n , warning1 : %d \n,warning2 : %d\n notWorking : %d\n", $count['working'], $count['warning1'], $count['warning2'],$count['notWorking']);
		
		// print_r($count);
		echo json_encode($count);
	}
}