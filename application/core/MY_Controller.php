<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller  extends CI_Controller{

    // imap server connection
    public $conn;
    public $role;
    public $user_check;
    public $roleid;
    // inbox storage and inbox message count
    private $inbox;
    public $msg_cnt;
    public $unread_message;
    // email login credentials
    private $server = '{127.0.0.1:110/pop3}INBOX';
    // private $server = '{mail.dlog.com:110/pop3}INBOX';
    // private $user   = 'test@test.com';
    // private $pass   = 'test';

    // connect to the server and get the inbox emails
    function __construct() {
        parent:: __construct();
        error_reporting(0);
        $this->load->helper('url');
        $this->load->library(array('session'));
        $this->load->model('message');
        $this->load->model('user_model');
        $this->load->model('mailserver');
        // $this->config->load('email');
        $this->load->library("imap");
        if(!isset($_SESSION['user_name'])){
            redirect('/');
        }

        $this->role = $this->user_model->get_role($_SESSION['user_id']);
        $this->user_check = $this->user_model->user_check($_SESSION['user_id']);
        $this->roleval = $this->user_model->get_user_role($_SESSION['user_id']);
        for ($i = 5; $i > 0; $i --) {
            $strval = '00000' . (string)decbin($this->roleval);
            $toi = $i * -1;
            if ((int)substr($strval, $toi, 1) == 1) {
                $this->roleid[$i] = "checked";
            } else {
                $this->roleid[$i] = "";
            }
        }
        if ($_SESSION['user_name']!=='admin') {
            $data=$this->mailserver->getdata($_SESSION['user_name']);
            $this->user=$data['loginID'];
            $this->pass=$data['loginPW'];
          
            $this->connect();
            if(!empty($this->conn)){
                $this->inbox();
                $this->differMessage();
                $this->unread_message=$this->unread_message;
            }
            // else{
            //     $this->load->view('mailserver_error');
            // }
        }else{
             //on pageload
            session_start();

            $durationtime=1800;//after 30 minutes the user gets logged out

            if (time()-$_SESSION['timestamp']>$durationtime){
                session_destroy();
                session_unset();
                $this->logout();
                
            }else{
                $_SESSION['timestamp']=time();
            }
            $this->load->model('inbox_model');
            $this->unread_message=$this->inbox_model->count_unread_message();
        }
        $errors = imap_errors();
    }

    public function logout() {
		$this->config->load('db_config');
		$this->load->library('DbClass');
		$this->load->library('MethodClass');
		$this->config->load('openSSL_config');
		$this->config->load('reCAPTURE_config');
		$dlogdb = new Dbclass();
		$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
		$tzstr = date_default_timezone_get();
		$defoulttz = "Asia/Tokyo";
		date_default_timezone_set($defoulttz);
		$new_time = new \DateTime();
		$logouttime = new \DateTime($new_time->format('Y-m-d H:i:s'), new \DateTimeZone($defoulttz));
		$up_darry = array(
			'UserStateID' => 1,
			'LastLoginTime' => $logouttime->format('Y-m-d H:i:s')
		);
		$userlist = $dlogdb->dbUpdate($dbpdo, 'users', $up_darry, 'ID', $this->session->userdata('user_id'));
		$dlogdb = null;
		date_default_timezone_set($tzstr);
		if (isset($_COOKIE['BSCM'])) {
			$get_cookie = $_COOKIE['BSCM'];
			$cookie_arry = explode(',', $get_cookie);
			for ($i = 0; $i < count($cookie_arry); $i ++) {
				$keyval = explode(':', $cookie_arry[$i]);
				$get_data[$keyval[0]] = $keyval[1];
			}
			if ((int)$get_data['resaved'] == 0) {
				$cookiestr = null;
				$expiration_time = time() - 60 * 60 * 24 * 30;
				setcookie('BSCM', $cookiestr, $expiration_time);
				$get_data['resaved'] = 0;
				$resaved = "";
			} else {
				$get_data['resaved'] = 1;
				$resaved = "checked";
			}
		}
		$this->session->sess_destroy();
		redirect(base_url());
		
	}
    public function index()
	{
        
        $this->close();
	
}

    // close the server connection
    function close() {
        $this->inbox = array();
        $this->msg_cnt = 0;
        imap_close($this->conn);
    }
    // open the server connection
    // the imap_open function parameters will need to be changed for the particular server
    // these are laid out to connect to a Dreamhost IMAP server
    function connect() {
        $this->conn = imap_open($this->server, $this->user, $this->pass);
        
    }

    // move the message to a new folder
    function move($msg_index, $folder='INBOX.Processed') {
        // move on server
        imap_mail_move($this->conn, $msg_index, $folder);
        imap_expunge($this->conn);
        // re-read the inbox
        $this->inbox();
    }
    // get a specific message (1 = first email, 2 = second email, etc.)

    function get($msg_index=NULL) {
        if (count($this->inbox) <= 0) {
            return array();
        }
        elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) 
        {
            return $this->inbox[$msg_index];
        }
        return $this->inbox[0];
    }

    // read the inbox
    function inbox() {
        $this->msg_cnt = imap_num_msg($this->conn);
        // if($this->msg_cnt==0) echo "mailbox is empty";
        $in = array();
        for($i = 1; $i <= $this->msg_cnt; $i++) {
           
            $in[] = array(
                'index'     => $i,
                'header'    => imap_headerinfo($this->conn, $i),
                'body'      => imap_utf8(imap_body($this->conn, $i)),
                'structure' => imap_fetchstructure($this->conn, $i)
            );

        }
        // $in['check'] = imap_mailboxmsginfo($this->conn);
        $this->inbox = $in;
    }

    function deleteMail(){
        $this->msg_cnt = imap_num_msg($this->conn);
        for($i = 1; $i <= $this->msg_cnt; $i++) {
            imap_delete($this->conn, $i);
            imap_expunge($this->conn);
        }
    } 
   function insertMessage($data){
        foreach($data as $value){
            $data=array(
                'MessageTypeID'=> 2,
                'MessageTitle'=> $value['MessageTitle'],
                'MessageContent'=>imap_utf8( $value['MessageContent']),
                'FromAccount'=> $value['FromAccount'],
                'ToAccount'=> $value['ToAccount'],
                'CreateTime'=> $value['CreateTime'],
            );
        $this->message->insertMessage($data);
        }
   }
   function differMessage(){
       $data=array();
       foreach($this->inbox as $key=>$value){
            $data[$key]=array(
            'MessageTypeID'=> 2,
            'MessageTitle'=> imap_utf8($value['header']->subject),
            'MessageContent'=> imap_utf8($value['body']),
            'FromAccount'=>imap_utf8($value['header']->fromaddress),
            'CreateTime'=>imap_utf8($value['header']->date),
            'ToAccount'=> imap_utf8($value['header']->toaddress),
            );
        }
        $getdata=$this->message->getMessage();
        $tmpArray = array();
        $this->unread_message=0;
        foreach($data as $data1) {

            $duplicate = false;
            foreach($getdata as $data2) {
                if($data1['MessageTitle'] === $data2->MessageTitle && $data1['FromAccount'] === $data2->FromAccount && $data1['CreateTime'] === $data2->CreateTime) $duplicate = true;
            }

            if($duplicate === false) {$tmpArray[] = $data1;$this->unread_message++;}
        }
        return $tmpArray;
   }
    // function unreadMessage(){
    //     $count = imap_num_msg($this->conn);
    //     echo "unread message";
    //     for($msgno = 1; $msgno <= $count; $msgno++) {

    //         $headers = imap_headerinfo($this->conn, $msgno);
    //         // if($headers->Unseen == 'U') {
    //             print_r($headers);
    //         // }
        
    //     }
    //     $result = imap_search($this->conn, 'UNSEEN');
    //     print_r($result);
    // }
}
