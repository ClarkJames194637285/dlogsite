<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outbox_Model extends CI_Model {
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
    protected $table = 'message_temp';
	public function __construct() {
		
		parent::__construct();
		
	}
	
	public function getSendMessage(){
        // $this->db->where('status','pending');
        
        $query=$this->db->get($this->table);
        return $query->result();
    }
    
	public function get_count() {

        return $this->db->count_all($this->table);
    }
    
	public function getMessage($limit, $start,$username) {

		$this->db->select('m.ID,m.MessageContent,m.ToAccount,m.CreateTime');
		$this->db->from('message_temp m'); 
		$this->db->join('users u', 'm.ToAccount=u.Email', 'left');
		// $this->db->join('messagetype t', 't.ID=m.MessageTypeID', 'left');
		$this->db->limit($limit, $start);
		$this->db->where('FromAccount',$username);
		$this->db->order_by('m.ID','desc');
        $query = $this->db->get();

        return $query->result();
	}
	public function delete_message($num) {

		$this->db->where('ID', $num);
		
		$del=$this->db->delete($this->table);
        return $del;
    }
}
