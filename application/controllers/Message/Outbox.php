

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Outbox extends MY_Controller
{

	public function __construct() {
        parent:: __construct();

        $this->load->helper('url');
        $this->load->model('Outbox_model');
        $this->load->model('message');
        $this->load->library("pagination");
        $this->load->library(array('session'));
        if (!isset($_SESSION['user_id'])) {
			redirect('/');
		}
        $this->load->helper('language');
        $site_lang=$this->session->userdata('lang');
		if ($site_lang) {
			$this->lang->load(array('menu','message'),$site_lang);
        } else {
			$this->lang->load(array('menu','message'),'japanese');
        }
    }

    public function index() {

		
		$config = array();
		$config["base_url"] = base_url() . "Outbox";
        $config["total_rows"] = $this->Outbox_model->get_count();
        $config["per_page"] = 10;
        $config['num_links'] = 2;
        $config["uri_segment"] = 0;
		
        $config["full_tag_open"] = '<div class="pagination">';
		$config["full_tag_close"] = '</div>';
		$config["first_link"] = '≪';
		$config["last_link"] = '≫';
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if($page=="outbox")$page=0;
        $data['page']=$page;
        $data["links"] = $this->pagination->create_links();

        $data['OutboxMessage'] = $this->Outbox_model->getMessage($config["per_page"], $page,$_SESSION['user_name']);
        $delete = $this->input->post('delete');
        if($delete){
            foreach($data['OutboxMessage'] as $message){
                $result=$this->Outbox_model->delete_message($message->ID);
            }
            echo $result;
            exit();
        }
        $data['unread']=$this->unread_message;
        
        $data['user_name']=$_SESSION['user_name'];

        $this->load->view('header',$data);
        $this->load->view('message/Outbox', $data);
	}


	public function delete(){
        $data = $this->input->post('id');
		if($data){
            $result=$this->Outbox_model->delete_message($data);   
            echo $result;
        }else{
            echo 0;
        }
    }

    public function deleteMulti(){
		$data=$this->input->post('data');
		foreach($data as $val){
            $result=$this->Outbox_model->delete_message($val[0]);   
		}
		echo $result;
	}
    
}
