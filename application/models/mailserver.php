<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailserver extends CI_Model {
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
    protected $table = 'mailsvrs';
	public function __construct() {
		
		parent::__construct();
		
	}
    
    
	public function getdata($user){

        $sql="select loginID,loginPW from mailsvrs where senderName='".$user."'"; 
        
        $query=$this->db->query($sql);
        return $query->row_array();
    }
    
	
}
