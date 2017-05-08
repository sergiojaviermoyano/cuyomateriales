<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Users');
	}

	public function index($permission)
	{
		$data['list'] = $this->Users->User_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('users/list', $data, true));
	}
	
	public function getUser(){
		$data['data'] = $this->Users->getUser($this->input->post());
		$response['html'] = $this->load->view('users/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setUser(){
		$data = $this->Users->setUser($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}


	public function lost(){
		$data = array();
		echo json_encode($this->load->view('users/lost', null, true));
	}

	public function cerrarSession(){
		$this->Users->cerrarSession();
	}
	
	
}
