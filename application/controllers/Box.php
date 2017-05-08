<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class box extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Boxs');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Boxs->Box_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('boxs/list', $data, true));
	}

	public function pagination()
	{
		echo json_encode($this->Boxs->Box_List($this->input->post()));
	}
	
	
	public function getBox(){
		$data['data'] = $this->Boxs->getBox($this->input->post());
		$response['html'] = $this->load->view('boxs/view_', $data, true);
		echo json_encode($response);
	}
	
	public function setBox(){
		$data = $this->Boxs->setBox($this->input->post());
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