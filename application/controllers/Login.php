<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
	function __construct()
        {
		parent::__construct();
		$this->load->model('Users');
	}

	public function index()
	{
		$var = array('user_data' => null,'username' => null,'email' => null, 'logged_in' => false);
        $this->session->set_userdata($var);
        $this->session->unset_userdata(null);
        $this->session->sess_destroy();

		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}

	public function sessionStart_(){
		$data = $this->Users->sessionStart_($this->input->post());
		if($data  == false)
		{
			echo json_encode(0);
		}
		else
		{
			echo json_encode(1);	
		}
	}
	
}
