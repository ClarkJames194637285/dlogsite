<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

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
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $Email
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function create_user($username, $email, $password) {
		
		$data = array(
			'UserName' =>$username,
			'Email'   => $email,
			'Password'   => $password,
			'CreateTime' => date('Y-m-j H:i:s'),
			'isdelete' =>'0'
		);
		
		return $this->db->insert('users', $data);
		
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $Email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($Email) {
		$this->db->select('Password');
        $this->db->from('users');
        $this->db->where('UserName', $Email);
       
        $result = $this->db->get()->row(); 
		
		return $result->Password;
	}
	/**
	 * 
	 * 
	 * 
	 */
	public function getname($username){
		$this->db->select('Email');
		$this->db->from('users');
		$this->db->where('UserName', $username);

		$result=$this->db->get()->row('Email');
		return $result;
	}

	public function getUserByEmailAndUsername($email, $username){
	    $this->db->from('users');
	    $this->db->where('Email', $email);
        $this->db->where('UserName', $username);

	    return $this->db->get()->row();
    }

    public function updatePassword($id, $password){
        return $this->db->update('users', ['Password' => $password], ['ID' => $id]);
    }


	/**
	 * get_ID_from_Email function.
	 * 
	 * @access public
	 * @param mixed $Email
	 * @return int the user id
	 */
	public function get_ID_from_Email($Email) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('Email', $Email);

		return $this->db->get()->row('id');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $ID
	 * @return object the user object
	 */
	public function get_user($ID) {
		
		$this->db->from('users');
		$this->db->where('id', $ID);
		return $this->db->get()->row();
		
	}

	public function getLoggedinUser() {
		$this->db->select('UserName,LoginTime');
		$this->db->from('users');
		$this->db->where('UserStateID', 0);
		$this->db->where('UserName!=', 'admin');
		return $this->db->get()->result_array();
		
	}
		/**
	 * get_Role function.
	 * 
	 * @access public
	 * @param mixed $ID
	 * @return object the user object
	 */
	public function get_role($ID) {

		$this->db->select('UserName');
		$this->db->from('users');
		$this->db->where('id', $ID);
		$result=$this->db->get()->row('UserName');
		return $result;
	}

	public function get_user_role($ID) {

		$this->db->select('ROLEID');
		$this->db->from('users');
		$this->db->where('id', $ID);
		$result=$this->db->get()->row('ROLEID');
		return $result;
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
}
