<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class rubro extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Rubros');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Rubros->Rubro_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('rubros/list', $data, true));
	}

	
	public function getRubro(){
		$data['data'] = $this->Rubros->getRubro($this->input->post());
		$response['html'] = $this->load->view('rubros/view_', $data, true);
		echo json_encode($response);
	}

	public function setRubro(){
		$data = $this->Rubros->setRubro($this->input->post());
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