<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class reception extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Receptions');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Receptions->Reception_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('receptions/list', $data, true));
	}

	public function pagination()
	{
		echo json_encode($this->Receptions->Reception_List($this->input->post()));
	}
	
	public function getReception(){
		$data['data'] = $this->Receptions->getReception($this->input->post());
		$response['html'] = $this->load->view('receptions/view_', $data, true);

		echo json_encode($response);
	}

	public function setReception(){
		$data = $this->Receptions->setReception($this->input->post());
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