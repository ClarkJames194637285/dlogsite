<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Captcha.php';

/**
 * @property  CI_Form_validation form_validation
 * @property  CI_Input input
 * @property  User_model user_model
 * @property  CI_Config config
 * @property  CI_Session session
 */
class Register extends CI_Controller
{
    function __construct()
    {
        parent:: __construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->library(array('session'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<li class="alert alert-danger">', '</li>');
        $this->load->helper('language');
		$site_lang=$this->session->userdata('lang');
		if ($site_lang) {
            $this->lang->load('auth',$site_lang);
        } else {
            $this->lang->load('auth','japanese');
        }
        setcookie('register', 'true');
    }

    public function index()
    {
        $this->load->view('register');
    }

    public function confirm()
    {
        $this->config->load('db_config');
        $this->config->load('openSSL_config');
        $this->load->model('user_model');
        $terms_of_service = $this->input->post('terms_of_service');
        if(!$terms_of_service){
            $this->session->set_flashdata('error', $this->lang->line('termsofservice'));
            return $this->load->view('register');
        }
        $this->form_validation->set_rules('captcha', $this->lang->line('authentication_code'), 'required|callback_captcha_check');
        $this->form_validation->set_rules('username', $this->lang->line('user_name'), 'trim|required|min_length[4]|callback_username_check');
        $this->form_validation->set_rules('email', $this->lang->line('mailaddress'), 'required|valid_email');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            return $this->load->view('register');
        }
       
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $raw_password = $this->input->post('password');

        $password = openssl_encrypt($raw_password, $this->config->item('cipher'), $this->config->item('key'));
        $groupId=$this->user_model->getGroupID();
        if($groupId){
            $groupId=$groupId[0]['GroupID']+1;
        }else{
            $groupId=0;
        }
        $created_row = $this->user_model->create_user($username, $email, $password,$groupId);

        if (!$created_row) {
            $this->session->set_flashdata('error', '問題が発生しました！ もう一度やり直すか、管理者に連絡してください。');
            $this->load->view('register');
        }

        return $this->load->view('registerConfirm');
    }

    public function username_check($username)
    {
        if ($this->user_model->getUserByEmail($this->input->post('email'))) {
            $this->form_validation->set_message(__FUNCTION__, 'このメールアドレスは既に登録されています。');
            return false;
        }
        if($this->user_model->getUserByUsername($username)){
            $this->form_validation->set_message(__FUNCTION__, 'このユーザー名はすでに登録されています。');

            return false;
        }
        return true;
    }

    public function captcha_check($captcha)
    {
        if (!Captcha::is_valid($captcha)) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('invalid_code'));

            return false;
        }
        return true;
    }
}
