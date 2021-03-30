
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
	function __construct()
    {
			parent::__construct();
			$this->load->helper('url');
			$this->load->library(array('session'));
			if (!isset($_SESSION['user_id'])) {
				redirect(base_url());
			}
			$this->load->model('sensor_Model');
			$this->load->helper('language');
			$site_lang=$this->session->userdata('lang');
			if ($site_lang) {
            $this->lang->load(array('menu','home'),$site_lang);
        } else {
            $this->lang->load(array('menu','home'),'japanese');
        }
    }
	public function index()
	{
			$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
			$sensorType=$this->sensor_Model->getSensorType($id[0]['ID']);
			$colors = [ 
				'#ff0000', "#33B800", '#0000ff', 
				'#ff3333',  "#3F3F3F", '#ff6600' 
			]; 
			$str='<div class="param-block1">';
			
			foreach($sensorType as $key=>$val){
				$str.='<p class="normal-param" style="color:'.$colors[$key%6].';">'.$val['ProductName'].'</p>';
			}
			$str.='</div>';
			$str.='<div class="param-block2">';
			foreach($sensorType as $key=>$val){
				$str.='<p class="normal-param" style="color:'.$colors[$key%6].';">'.$val['count'].'</p>';
			}
			$str.='</div>';
			$alert['string']=$str;
			$data['unread']=$this->unread_message;
			$data['user_name']=$this->session->userdata('user_name');
			$alert['count']=$this->getSensorInfo();
			$this->load->view('header',$data);
			$this->load->view('home',$alert);
		
	}

	//--------------------------------------------------------------------
	public function getSensorInfo(){
		/**
		 * constant array for calculating the WBGT
		 */
		$count['working']=0;
		$count['warning1']=0;
		$count['warning2']=0;
		$count['notWorking']=0;
		$currentTime=date('Y-m-j H:i:s');

		$a = array(1.672,0.7401,1.798,10.19,5.071,364.1);
		$b = array(-0.000666,7.196,-3.892,-1.297);
		/**
		 * the function which get the groupId from product table
		 */
		// $id=$this->sensor_Model->getUserId($user);
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$data=$this->sensor_Model->allUserPid($id[0]['ID']);//$data['pid']

		foreach($data as $val){
			/**
			 * get the latest value from terminalhistory table.
			 *  PID,Temperature,Humidity,Pressure,RTC 
			 */
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
				$currentTime=strtotime(date('Y-m-j H:i:s'));
				$getTime=strtotime($sensorInfo[0]['RTC']);
				
				$difftime=($currentTime-$getTime);
				$diffday=$difftime/(60*60*24);
				if($diffday>1000){
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

				$wbgt=$a[0]+$a[1]*$temp+$a[2]*($hum*$a[3]*exp(($a[4]*$temp)/($a[5]+$temp)))+$b[0]*pow(($temp-$b[1]),2)+$b[2]*pow(($hum-$b[3]),2);
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

			// if($SensorInfo)
		}
		// printf("working : %d\n , warning1 : %d \n,warning2 : %d\n notWorking : %d\n", $count['working'], $count['warning1'], $count['warning2'],$count['notWorking']);
		return $count;
	}
	public function getdata(){
		$id=$this->sensor_Model->getUserId($this->session->userdata('user_name'));
		$sensorType=$this->sensor_Model->getSensorDatas($id[0]['ID']);

		echo json_encode($sensorType);
	}

}
