<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Model {
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		
	}

	/**
	 *add  message function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function insertMessage($data) {
		

		$data=array(
			'MessageTypeID'=> $data['MessageTypeID'],
			'MessageTitle'=> $data['MessageTitle'],
			'MessageContent'=> $data['MessageContent'],
			'FromAccount'=> $data['FromAccount'],
			'ToAccount'=> $data['ToAccount'],
			'FromUserID'=> 0,
			'ToUserID'=> 0,
			'CreateTime'=> $data['CreateTime'],
			'IsOK'=> 1,
			'IsRead'=> 1,
			'isdelete'=> 0
		);
		
		return $this->db->insert('message', $data);
		
	}
	/**
	 *get  message function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function getMessage() {
		
		$this->db->select("MessageTitle,FromAccount,CreateTime");
		$this->db->from("message");
		$query = $this->db->get();        
		return $query->result();
		
		
	}
	/**
	 * recordMessage function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function recordMessage($data) {
		

		$data = array(
			'MessageTypeID'   => $data['messageType'],
			'MessageTitle'      => 'test',
			'MessageContent'   => $data['message'],
			'FromAccount'=>  $data['FromAccount'],
			'ToAccount'=>   $data['ToAccount'],
			'FromUserID'      => '1',
			'ToUserID'   => '2',
			'CreateTime' => date('Y-m-j H:i:s'),
		);
		
		return $this->db->insert('message_temp', $data);
		
	}
	public function getSendMessage(){

        $query=$this->db->get('message');
        return $query->result();
	}
	public function getMessageType(){

		$sql="select ID,TypeName from messagetype";    
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function get_count($table) {

        return $this->db->count_all($table);
	}

	public function setConfig($data) {

        $this->db->where('UserID', $data['UserID']);
		$req=$this->db->get('messageconfig');
		if($req->num_rows()>0){
			$this->db->where('UserID', $data['UserID']);
			$result=$this->db->update('messageconfig',$data);
		}else{
			$this->db->where('UserID', $data['UserID']);
			$result=$this->db->insert('messageconfig',$data);
		}

        return $result;
    }
}
