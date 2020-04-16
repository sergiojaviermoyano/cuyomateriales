<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bank extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Banks');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Banks->Bank_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('banks/list', $data, true));
	}
	
	public function getBank(){
		$data['data'] = $this->Banks->getBank($this->input->post());
		$response['html'] = $this->load->view('banks/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setBank(){
		$data = $this->Banks->setBank($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}
	
}
