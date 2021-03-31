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

        $this->form_validation->set_rules('captcha', '認証コード', 'required|callback_captcha_check');
        $this->form_validation->set_rules('username', 'ユーザー名', 'trim|required|min_length[4]|callback_username_check');
        $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email');
        $this->form_validation->set_rules('password', 'パスワード', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'パスワードの再入力', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            return $this->load->view('register');
        }

        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $raw_password = $this->input->post('password');

        $password = openssl_encrypt($raw_password, $this->config->item('cipher'), $this->config->item('key'));
        $created_row = $this->user_model->create_user($username, $email, $password);

        if (!$created_row) {
            $this->session->set_flashdata('error', 'Something went wrong! Please retry again or contact to admin!');
            $this->load->view('register');
        }

        return $this->load->view('registerConfirm');
    }

    public function username_check($username)
    {
        if ($this->user_model->getUserByEmailAndUsername($this->input->post('email'), $username)) {
            $this->form_validation->set_message(__FUNCTION__, 'The Email and Username is already existed!');

            return false;
        }

        return true;
    }

    public function captcha_check($captcha)
    {
        if (!Captcha::is_valid($captcha)) {
            $this->form_validation->set_message(__FUNCTION__, '承認コードが正しくないです。');

            return false;
        }

        return true;
    }
}