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
        
        $query=$this->db->get('message_temp');
				$this->db->where('readstatus',0);
        return $query->result_array();
    }

		public function updateMessage(){
			$this->db->where('readstatus', 0);
			$query = $this->db->update('message_temp', array('readstatus ' => 1));
	}
    
	public function get_count() {
		$query = $this->db->query("SELECT * FROM message_temp where readstatus=0");
		return $query->num_rows();
    }
	public function getMessage($limit, $start) {

		$this->db->select('m.ID,m.MessageContent,m.FromAccount,m.CreateTime');
		$this->db->from('message m'); 
		$this->db->join('users u', 'm.FromAccount=u.Email', 'left');
		// $this->db->join('messagetype t', 't.ID=m.MessageTypeID', 'left');
		$this->db->limit($limit, $start);
		// $this->db->where('ToAccount',$username);
		$this->db->order_by('m.ID','desc');
		// $this->db->join('user', 'message.FromAccount = user.ToAccount');
        $query = $this->db->get();

        return $query->result();
	}
	public function delete_message($num) {
		$this->db->where('ID', $num);
		$del=$this->db->delete($this->table);
        return $del;
    }
}
