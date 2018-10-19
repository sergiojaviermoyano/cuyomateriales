<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cuentacorriente extends CI_Controller {

	function __construct() 
        {
		parent::__construct();
		$this->load->model('Cuentacorrientes');
		$this->load->model('Providers');
		$this->load->model('Customers');
		$this->Users->updateSession(true);
	}

	public function indexp($permission)
	{
		$data['list'] = $this->Providers->Providers_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('cuentacorrientes/listp', $data, true));
	}

	public function getCtaCteP(){
		$data['data'] = $this->Cuentacorrientes->getCtaCteP($this->input->post());
		$response['html'] = $this->load->view('cuentacorrientes/viewp', $data, true);

		echo json_encode($response);
	}

	public function setCtaCteP(){
		$data = $this->Cuentacorrientes->setCtaCteP($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	//Clientes
	public function indexc($permission)
	{
		$data['list'] = $this->Customers->Customers_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('cuentacorrientes/listc', $data, true));
	}

	public function getCtaCteC(){
		$data['data'] = $this->Cuentacorrientes->getCtaCteC($this->input->post());
		$response['html'] = $this->load->view('cuentacorrientes/viewc', $data, true);

		echo json_encode($response);
	}

	public function setCtaCteC(){
		$data = $this->Cuentacorrientes->setCtaCteC($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	} 

	public function printRecibo(){
		echo json_encode($this->Cuentacorrientes->printRecibo($this->input->post()));
	}

	public function printExtracto(){
		echo json_encode($this->Cuentacorrientes->printExtracto($this->input->post()));	
	}
}