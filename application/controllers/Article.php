<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class article extends CI_Controller {

	function __construct() 
        {
		parent::__construct();
		$this->load->model('Articles');
		$this->load->model('Rubros');
		$this->load->model('IvaAliCuotas');

		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		$data['list'] = $this->Articles->Articles_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('articles/list', $data, true));
	}
	
	public function getArticle(){
		
		$rubros=$this->Rubros->SubRubro_List();

		$ivaAliCuotas=$this->IvaAliCuotas->Iva_List();
		$data['rubros']=$rubros;
		$data['ivaAliCuotas']=$ivaAliCuotas;
		$data['data'] = $this->Articles->getArticle($this->input->post());
		$response['html'] = $this->load->view('articles/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setArticle(){

		
		$data = $this->Articles->setArticle($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	public function searchByCode() {
		$data = $this->Articles->searchByCode($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	} 

	public function searchByAll() {
		$data = $this->Articles->searchByAll($this->input->post());
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