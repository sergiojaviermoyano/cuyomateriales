<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class check extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Checks');
		$this->load->model('Banks');
		$this->load->model('Customers');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Checks->Check_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('checks/list', $data, true));
	}

	public function changeStatus(){
		$data = $this->Checks->changeStatus($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}
	
	public function getCheck(){
		$data['bancos'] = $this->Banks->Bank_List();
		$data['clientes'] = $this->Customers->Customers_List();
		$data['data'] = $this->Checks->getCheck($this->input->post());
		$response['html'] = $this->load->view('checks/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setCheck(){
		$data = $this->Checks->setCheck($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	public function vencimiento($permission)
	{
		$data['list'] = array();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('checks/calendar', $data, true));
	}

	public function vencimientos(){
		$data = $this->Checks->vencimientos($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	}
	
}
