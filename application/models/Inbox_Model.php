<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox_Model extends CI_Model {
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
    protected $table = 'message';
	public function __construct() {
		
		parent::__construct();
		
	}
	
	public function differ_message(){
        
        $this->db->from('message_temp');
				$this->db->where('readstatus',0);
        return $this->db->get()->result_array();
    }

		public function updateMessage(){
			$this->db->where('readstatus', 0);
			$query = $this->db->update('message_temp', array('readstatus ' => 1));
	}
    
	public function count_unread_message() {
		$query = $this->db->query("SELECT * FROM message_temp where readstatus=0");
		return $query->num_rows();
    }

		public function get_count() {

			return $this->db->count_all($this->table);
	}
	public function getMessage($limit, $start) {

			$this->db->select('ID,MessageContent,FromAccount,CreateTime');
			$this->db->from('message'); 
			$this->db->limit($limit, $start);
			$this->db->order_by('ID','desc');
			$query = $this->db->get();
			return $query->result();
	}
	public function delete_message($num) {
		$this->db->where('ID', $num);
		$del=$this->db->delete($this->table);
        return $del;
    }
}
