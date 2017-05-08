<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class provider extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Providers');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Providers->Providers_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('provider/list', $data, true));
	}
	
	public function getProvider(){
		$data['data'] = $this->Providers->getProvider($this->input->post());
		$response['html'] = $this->load->view('provider/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setProvider(){
		$data = $this->Providers->setProvider($this->input->post());
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