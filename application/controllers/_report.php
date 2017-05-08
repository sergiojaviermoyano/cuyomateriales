<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Reports');
	}

	public function in($permission)
	{
		$this->load->view('reports/in');
	}

	public function getIn()
	{
		$data = $this->Reports->getIn($this->input->post());

		echo json_encode($data);
	}

	public function vt($permission)
	{
		$this->load->view('reports/vt');
	}

	public function getVt()
	{
		$data = $this->Reports->getVt($this->input->post());

		echo json_encode($data);
	}
	
	/*
	public function getZone(){
		$data['data'] = $this->Zones->getZone($this->input->post());
		$response['html'] = $this->load->view('zones/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setZone(){
		$data = $this->Zones->setZone($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}
	*/
}
