<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cash extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Cashs');
	}

	public function index($permission)
	{
		$data['list'] = $this->Cashs->Cashs_List();
		$data['permission'] = $permission;
		$this->load->view('cash/list', $data);
	}
	
	public function getMotion(){
		$data['data'] = $this->Cashs->getMotion($this->input->post());
		$response['html'] = $this->load->view('cash/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setMotion(){
		$data = $this->Cashs->setMotion($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	public function getBalance(){
		$data['data'] = $this->Cashs->getBalance();
		$response['html'] = $this->load->view('cash/balance_', $data, true);

		echo json_encode($response);
	}

	public function setPay(){
		$data = $this->Cashs->setPay($this->input->post());
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