<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class order extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Orders');
		$this->Users->updateSession(true);
	}

	public function index($permission){
		$data['orders'] = $this->Orders->Orders_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('orders/list', $data, true));
	}

	public function getOrder(){
		$data['data'] = $this->Orders->getOrder($this->input->post());
		$response['html'] = $this->load->view('orders/view_', $data, true);

		echo json_encode($response);
	}

	public function setOrder(){
		$data = $this->Orders->setOrder($this->input->post());
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