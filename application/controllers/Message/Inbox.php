
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends MY_Controller
{

	public function __construct() {
        parent:: __construct();

        $this->load->helper('url');
        $this->load->model('inbox_model');
        $this->load->model('message');
        $this->load->library("pagination");
        $this->load->library(array('session'));
        if (!isset($_SESSION['user_id'])) {
			redirect('/');
		}
        if($this->role!=="99999"){
            redirect('message/outgoing');
        }
        if($this->conn){
            $this->insertMessage($this->differMessage());
            $this->deleteMail();
        }
        $this->load->helper('language');
        $site_lang=$this->session->userdata('lang');
		if ($site_lang) {
			$this->lang->load(array('menu','message'),$site_lang);
        } else {
			$this->lang->load(array('menu','message'),'japanese');
        }
        
        // $this->load->library("EmailReader");
    }

    public function index() {
        $config = array();
		$config["base_url"] = base_url() . "Inbox";
        $config["total_rows"] = $this->inbox_model->get_count();
        $config["per_page"] = 10;
        $config['num_links'] = 2;
        $config["uri_segment"] = 0;
        $config["full_tag_open"] = '<div class="pagination">';
		$config["full_tag_close"] = '</div>';
		$config["first_link"] = '≪';
		$config["last_link"] = '≫';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if($page=="inbox")$page=0;
        $data['page']=$page;
        $data["links"] = $this->pagination->create_links();
        $data['InboxMessage'] = $this->inbox_model->getMessage($config["per_page"], $page,$_SESSION['user_name']);
        $delete = $this->input->post('delete');
        if($delete){
            foreach($data['InboxMessage'] as $message){
                $result=$this->inbox_model->delete_message($message->ID);
            }
            echo $result;
            exit();
        }
        $hdata['unread']=0;
        $hdata['user_name']=$_SESSION['user_name'];
        $this->load->view('header',$hdata);
        $this->load->view('message/Inbox', $data);
        
	}

	public function delete(){
        $data = $this->input->post('id');
		if($data){
            $result=$this->inbox_model->delete_message($data);
            echo $result;
        }else{
            echo false;
        }
    }
}
