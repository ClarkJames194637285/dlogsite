<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Captcha.php';

/**
 * @property  CI_Form_validation form_validation
 * @property  CI_Input input
 * @property  User_model user_model
 * @property  CI_Config config
 * @property  CI_Email email
 * @property  CI_Session session
 */
class Passforget extends CI_Controller
{

	private const SALT = 'qTanKkycKlrabQ==';

	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->config->load('openSSL_config');
        $this->load->helper('cookie');
        $this->load->library(array('session'));
        $this->load->model('user_model');
        $this->load->config('email');
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
		$this->config->load('reCAPTURE_config');
		$this->load->view('passforget');
	}

	public function confirm()
    {
        $this->config->load('db_config');
        $this->config->load('openSSL_config');

        $this->form_validation->set_rules('captcha', $this->lang->line('authentication_code'), 'required|callback_captcha_check');
        $this->form_validation->set_rules('username', $this->lang->line('user_name'), 'trim|required|min_length[4]|callback_username_check');
        $this->form_validation->set_rules('email', $this->lang->line('mailaddress'), 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            return $this->load->view('passforget');
        }

        $username = $this->input->post('username');
        $email = $this->input->post('email');

        // $this->load->model('user_model');

        if (!$this->sendMail($email, $username)) {
            $this->session->set_flashdata('error', $this->lang->line('mail_failed'));

            if (ENVIRONMENT !== 'production') {
                var_dump($this->email->print_debugger());
            }

            return $this->load->view('passforget');
        }

        return $this->load->view('pass_forget_confirm');
    }

    //--------------------------------------------------------------------
    public function sendMail($email, $username)
    {
        $this->load->library('email');
        ['payload' => $payload, 'token' => $token] = $this->generateToken(['username' => $username, 'email' => $email]);
        $config = array(
			'protocol' => $this->config->item('protocol'),
			'smtp_host' => $this->config->item('smtp_host'),
			'smtp_port' => $this->config->item('smtp_port'),
			'smtp_user' => $this->config->item('smtp_user'),
			'smtp_pass' => $this->config->item('smtp_pass'),
			'charset' => $this->config->item('charset'),
			'wordwrap'=> TRUE,
			'mailtype' => 'html'
		);
        $this->email->initialize($config);
        $link = base_url() . "Passforget/reset?payload={$payload}&token={$token}";
        $from = $this->config->item('smtp_user');
        $message = "<a href='{$link}'>???????????????????????????????????????????????????????????????????????????????????????</a>";

        $this->email->set_newline("\r\n");
        $this->email->from($from, '[Dlog] System');
        $this->email->to($email);
        $this->email->subject('[Dlog] PW????????????');
        $this->email->message($message);

        if($this->email->send()){
			return true;
		}else{
			return false;
		}
    }

	public function reset()
	{
		$payload = $this->input->get('payload');
		$token = $this->input->get('token');
		$this->load->view('pass_forget_reset', compact('payload', 'token'));
	}

	public function resetConfirm()
    {
        $this->form_validation->set_rules('payload', '????????????', 'required');
        $this->form_validation->set_rules('token', '????????????', 'required');
        $this->form_validation->set_rules('captcha', '???????????????', 'required|callback_captcha_check');
        $this->form_validation->set_rules('password', '???????????????', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', '???????????????????????????', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            return $this->load->view('pass_forget_reset', ['token' => $this->input->post('token'), 'payload' => $this->input->post('payload')]);
        }

        $payload = $this->input->post('payload');
        $token = $this->input->post('token');
        $raw_password = $this->input->post('password');

        try {
            ['email' => $email, 'username' => $username] = $this->verifyToken($payload, $token);

            $user = $this->user_model->getUserByEmailAndUsername($email, $username);
            $password = openssl_encrypt($raw_password, $this->config->item('cipher'), $this->config->item('key'));

            $result = $this->user_model->updatePassword($user->ID, $password);
            if ($result) {
                return $this->load->view('pass_forget_reset_confirm');
            }
        } catch (TokenException $exception) {
            $this->session->set_flashdata('error', $exception->getMessage());

            return $this->load->view('pass_forget_reset', compact('payload', 'token'));
        } catch (\Throwable $exception) {
            $this->session->set_flashdata('error', $this->lang->line('whoops'));

            return $this->load->view('pass_forget_reset', compact('payload', 'token'));
        }

        $this->session->set_flashdata('error', $this->lang->line('whoops'));

        return $this->load->view('pass_forget_reset', compact('payload', 'token'));
    }

	private function generateToken(array $data)
	{
		$payload = base64_encode(json_encode([
			'data' => $data,
			'exp' => strtotime('now +60 minutes'),
			'iss' => time()
		]));

		$token = crypt($payload, self::SALT);

		return compact('payload', 'token');
	}

	/**
	 * @param string $payload_string
	 * @param string $received_token
	 *
	 * @return array
	 * @throws TokenException
	 */
	private function verifyToken(string $payload_string, string $received_token)
    {
        $token = crypt($payload_string, self::SALT);
        $this->throw_if($token != $received_token, new TokenException($this->lang->line('token_invalid')));

        $json = base64_decode($payload_string);
        $payload = json_decode($json, true);
        $this->throw_if($payload == null, new TokenException($this->lang->line('payload_invalid')));
        $time=time();
        $this->throw_if($payload['exp'] < time(), new TokenException($this->lang->line('token_expired')));

        return $payload['data'];
    }

	/**
	 * @param $condition
     * @param $exception
     *
     * @throws Throwable
     */
    private function throw_if($condition, Throwable $exception)
    {
        if ($condition) {
            throw $exception;
        }
    }

    public function username_check($username)
    {
        if (!$this->user_model->getUserByEmailAndUsername($this->input->post('email'), $username)) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('incorrect_password_email'));

            return false;
        }

        return true;
    }

    public function captcha_check($captcha)
    {
        if (!Captcha::is_valid($captcha)) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('incorrect_authontication_code'));

            return false;
        }
        return true;
    }
}
class TokenException extends Exception
{
}
