<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller
{

	function __construct()
    {
		parent::__construct();
		$this->load->helper('url');
    }

	public function index()
	{
		
		// $this->load->view('message');
	}

	//--------------------------------------------------------------------
    public function not_found(){
		/* Redirect browser */
		redirect(base_url(),'refresh');
		
    }
}