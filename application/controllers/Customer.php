<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class customer extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Customers');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Customers->Customers_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('customers/list', $data, true));
	}
	
	public function getCustomer(){
		$data['data'] = $this->Customers->getCustomer($this->input->post());
		$response['html'] = $this->load->view('customers/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setCustomer(){
		$data = $this->Customers->setCustomer($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	public function setCustomer2(){
		$data = $this->Customers->setCustomer2($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	}

	public function findCustomer(){
		$data = $this->Customers->findCustomer($this->input->post());
		echo json_encode($data);
	}

	public function buscadorClientes() {
		$data = $this->Customers->buscadorClientes($this->input->post());
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