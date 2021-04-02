<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sensor_Model extends CI_Model {
    
	public function __construct() {
		
        parent::__construct();

		
	}

	
    public function SensorInfo($pid) {
		
       if ($this->db->table_exists("terminalhistory".$pid) )
        {
        // table exists some code run query
            $sql="select PID,Temperature,Humidity,Pressure,RTC from terminalhistory".$pid."  where RTC=(select MAX(RTC) from terminalhistory".$pid.")";    
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        else
        {
        // table does not exist
            return false;
        }
        
            
    }

    public function getHistoryData($id) {
        $sql="SELECT * FROM (SELECT th.ID AS ID, th.UUID AS UUID, th.PID AS PID, th.Temperature AS Temperature, th.Humidity AS Humidity, th.Pressure AS Pressure, th.Voltage AS Voltage, th.Battery AS Battery, th.RSSI AS RSSI, th.RTC AS RTC, pd.IMEI AS IMEI, pd.ProductName AS ProductName, pd.TerminalDataInterval AS TerminalDataInterval, pg.GroupName AS GroupName, pg.SortID AS SortID, pt.TypeName AS TypeName, pt.Model AS Model, pd.isdelete AS Pddel FROM `product` AS pd INNER JOIN `productgroup` AS pg ON pd.GroupID=pg.ID AND pg.isdelete=0 INNER JOIN `producttype` AS pt ON pd.TypeID=pt.ID AND pt.isdelete=0 INNER JOIN terminalhistory AS th ON pd.ID=th.PID WHERE pd.UserID=".$id." AND th.ID in (SELECT max(ID) FROM terminalhistory GROUP BY PID )) AS thd WHERE Pddel=0 ORDER BY SortID";    
        $query = $this->db->query($sql);
        return $query->result_array();
     }

    public function dateTimeMap($pid,$datetime) {
		
        $datetime=date('Y-m-d H:i:s',time()-$datetime*3600);
        $time=explode(':',$datetime);

        if ($this->db->table_exists("terminalhistory".$pid) )
         {
         // table exists some code run query
             $sql="select PID,Temperature,Humidity,Pressure,RTC from terminalhistory".$pid." where RTC LIKE '".$time[0]."%'";    
             $query = $this->db->query($sql);
             return $query->result_array();
         }
         else
         {
         // table does not exist
             return false;
         }
     }

    public function requireBattery($pid) {
		
        if ($this->db->table_exists("terminalhistory".$pid) )
        {
        // table exists some code run query
            $sql="select count(*) as count from terminalhistory".$pid." where RTC=(select MAX(RTC) from terminalhistory".$pid.") and Voltage<3.64";    
            $query = $this->db->query($sql);
            $result=$query->result_array();
            if($result[0]['count'])return true;
            else 
            return false;
        }
        else
        {
        // table does not exist
            return false;
        }
        
       
            
    }

  
    /**
     * get map infomation from map.
     * 
     */

       
	public function getMap($userId) {

		$this->db->select('ID,name,imageurl');
		$this->db->from('map'); 
		$this->db->where('UserID',$userId);
        $this->db->order_by("SortID", "asc");
        $query = $this->db->get();

        return $query->result_array();
	}
    public function getMapSensor($mapID,$userId) {
        $this->db->select('p.ID,p.ProductName');
        $this->db->from('product p');
        $this->db->join('producttype t', 'p.TypeID=t.ID');
        $this->db->join('productgroup g', 'p.UserID=g.UserID AND p.GroupID=g.ID');
        $this->db->where('p.RegionID', $mapID);
        $this->db->where('p.userID', $userId);
        $query = $this->db->get(); 
        return $query->result();
	}
    public function groupDecide($user,$groupName,$sortID) {
        $this->db->where('UserID', $user);
        $this->db->where('GroupName', $groupName);
        $data = array(
			'SortID' =>$sortID
			
		);
		$result=$this->db->update('productgroup',$data);

        return $result;
    }
    public function mapDecide($user,$mapId,$sortID) {
        $this->db->where('userID', $user);
        $this->db->where('ID', $mapId);
        $data = array(
			'SortID' =>$sortID
		);
		$result=$this->db->update('map',$data);

        return $result;
    }
    public function sensorDecide($user,$sensorName,$receiverID) {
        $this->db->where('UserID', $user);
        $this->db->where('ProductName', $sensorName);
        $data = array(
			'ReceiverID' =>$receiverID
			
		);
		$result=$this->db->update('product',$data);

        return $result;
    }

    /**
     * get sensor infomation from product.
     * 
     */
    public function allSensorPid($id){
        $sql="select p.ID,p.GroupID,p.ProductName,p.RegionID from product as p JOIN producttype AS t ON p.TypeID=t.ID JOIN productgroup AS g ON p.GroupID=g.ID AND p.UserID=g.UserID where p.UserID=? ORDER BY ReceiverID";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }
    public function getSensorType($id){
        $sql="select t.TypeName as ProductName,count(*) as count from product as p join producttype as t on p.TypeID=t.ID JOIN productgroup AS g ON p.UserID=g.UserID AND p.GroupID=g.ID where p.UserID=? GROUP BY t.TypeName";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }
    public function getSensorDatas($id){
        $sql="select count(*) as y,t.TypeName as name from product as p left join producttype as t on p.TypeID=t.ID JOIN productgroup AS g ON p.GroupID=g.ID AND p.UserID=g.UserID where p.UserID=? GROUP BY TypeID";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }
    public function getSensorData($id){
        $sql="select ProductName as name from product where UserID=? ORDER BY ReceiverID";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }
    public function checkSensor($content,$name,$user){
        $this->db->select('ID');
        $this->db->from('product');
        $this->db->where('UserID', $user);
        $this->db->where('ProductName', $name);
        $query = $this->db->get()->row(); 
        
        if($query){
            if ($this->db->table_exists("terminalhistory".$query->ID) ){
                $this->db->select('Humidity');
                $this->db->from("terminalhistory".$query->ID);
                $this->db->where('ID', 1);
                $check = $this->db->get()->row(); 
                $val=$check->Humidity;
                if(($val<0)||($val>100)){
                    $temp="temperature";
                }else{
                    $temp="humidity";
                }
                if($content==$temp){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }

    public function getSensor($user,$type,$content){
        $this->db->select('ID');
        $this->db->from('product');
        $this->db->where('UserID', $user);
        $this->db->where('ProductName', $type);
        $query = $this->db->get()->row(); 
        
        if($query){
            if ($this->db->table_exists("terminalhistory".$query->ID) )
            {
               
            // table exists some code run query
                if($content="temperature"){
                    $this->db->select('RTC,Temperature');
                    $this->db->from('terminalhistory'.$query->ID);
                    $query = $this->db->get();
                    return $query->result();
                }else if($content="humidity"){
                    $this->db->select('RTC,Humidity');
                    $this->db->from('terminalhistory'.$query->ID);
                    $query = $this->db->get();
                    return $query->result();
                }else{
                    return false;
                }
            }
            else
            {
            // table does not exist
                return false;
            }
            
            
        }else{
            return false;
        }
    }
   
    public function allUserPid($id){
        $sql="select p.ID from product as p JOIN producttype AS t ON p.TypeID=t.ID JOIN productgroup AS g ON p.UserID=g.UserID AND p.GroupID=g.ID where p.UserID=?";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }


    public function getUserId($user){
        $sql="select ID from users where UserName=?";
        $query = $this->db->query($sql,$user);
        return $query->result_array();
    }
    
    public function listSensor($pid){

        if ($this->db->table_exists("terminalhistory".$pid) )
        {
        // table exists some code run query
            $sql="select  PID,Temperature,Humidity,Voltage,RTC from terminalhistory".$pid." where RTC=(select MAX(RTC) from terminalhistory".$pid.")";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        else
        {
        // table does not exist
            return false;
        }
        
    }
    
    public function getGroupName($id){
        $sql="select GroupName from productgroup where ID=?";
        $query = $this->db->query($sql,$id);
        return $query->result_array();
    }

    public function sensorinfos($pid){
        if ($this->db->table_exists("terminalhistory".$pid) )
        {
        // table exists some code run query
            $sql="select p.ProductName,p.IMEI,t.TypeName from product as p left join producttype as t on p.TypeID=t.ID where p.ID=?";
            $query = $this->db->query($sql,$pid);
            return $query->result_array();
        }
        else
        {
        // table does not exist
            return false;
        }
        
    }
    /**
     * get groupId from sensorInfo.
     * 
     */
    public function GetGroupId($user){
        $sql="select ID,GroupName from productgroup where UserID='".$user."' ORDER BY SortID";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function GetMapId($user){
        $sql="select ID,name,imageurl from map where UserID='".$user."' ORDER BY SortID";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /**
     * register new sensor.
     */
	function setSensor($data){
        $sql="insert into product (IMEI,TypeID,GroupID,ProductName,UserID) values ('".$data['IMEI']."',".$data['typeId'].",".$data['groupId'].",'".$data['name']."',".$data['userId'].")";
        $result=$this->db->query($sql);
       return $result;
        
    }
    public function getMapInfo($user,$mapID){
        $this->db->select('name,imageurl');
        $this->db->from('map');
        $this->db->where('UserID', $user);
        $this->db->where('ID', $mapID);
        return $query = $this->db->get()->row(); 
    }
    public function deleteMap($mapID){
        $this -> db -> where('ID', $mapID);
        return $this -> db -> delete('map');
    }
    function set_map_Info($map_url,$mapname,$mapID,$user) {
        if($mapID==""){
            $data = array(
                'imageurl' =>$map_url,
                'name' =>$mapname,
                'userID' =>$user,
                'CreateTime' => date('Y-m-j H:i:s')
            );
            return $this->db->insert('map', $data);
        }else{
            $data = array(
                'imageurl' =>$map_url,
                'name' =>$mapname,
                'userID' =>$user,
                'CreateTime' => date('Y-m-j H:i:s')
            );
            $this->db->where('ID', $mapID);
            return $this->db->update('map', $data);
        }
    }
	
}
