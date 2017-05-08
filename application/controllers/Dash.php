<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dash extends CI_Controller {
	function __construct()
        {
		parent::__construct();
		$this->load->model('Groups');
		//$this->load->model('Customers');
		//$this->load->model('Calendar');
	}

	public function index()
	{
		$this->load->view('header');
		$userdata = $this->session->userdata('user_data');
		$data['userName'] = $userdata[0]['usrNick'];
		$this->load->view('dash', $data);
		$menu['menu'] = $this->Groups->buildMenu();
		$this->load->view('menu',$menu);
		$this->load->view('content');
		$this->load->view('footerdash');
		$this->load->view('footer');
	}

}
