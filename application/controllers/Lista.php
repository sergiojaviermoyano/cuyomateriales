<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class lista extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Lists');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Lists->List_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('lists/list', $data, true));
	}
	
	public function getLista(){
		$data['data'] = $this->Lists->getList($this->input->post());
		$response['html'] = $this->load->view('lists/view_', $data, true);
		echo json_encode($response);
	}

	public function setLista(){
		$data = $this->Lists->setList($this->input->post());
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