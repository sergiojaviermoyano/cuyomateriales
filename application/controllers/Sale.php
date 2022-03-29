<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sale extends CI_Controller {

	function __construct()
        {
		parent::__construct();
		$this->load->model('Sales');
		$this->Users->updateSession(true);
	}

	public function index($permission)
	{
		//$data['list'] = $this->Sales->Sale_List(); 
		$data['permission'] = $permission;
		echo json_encode($this->load->view('sales/list', $data, true));
	}
	
	// public function getTabContent(){
	// 	$data['data'] = $this->Sales->getView($this->input->post());
	// 	switch ($this->input->post()['id']) {
	// 		case '1':
	// 			//Ordenes Activas
	// 			echo json_encode($this->load->view('sales/orders', $data, true));
	// 			break;
			
	// 		case '2':
	// 			//Facturas
	// 			echo json_encode($this->load->view('sales/tickets', $data, true));
	// 			break;

	// 		case '3':
	// 			//Caja
	// 			echo json_encode($this->load->view('sales/boxs', $data, true));
	// 			break;

	// 	}
	// }

	public function listingSales(){
		$totalVentas=$this->Sales->getTotalVentas($_REQUEST);
		$ventas = $this->Sales->Ventas_List_datatable($_REQUEST);

		$result=array(
			'draw'=>$_REQUEST['draw'],
			'recordsTotal'=>$totalVentas,
			'recordsFiltered'=>$totalVentas,
			'data'=>$ventas,
		);
		echo json_encode($result);
	}

	public function getOrden(){
		$data['data'] = $this->Sales->getOrder($this->input->post());
		$response['html'] = $this->load->view('sales/order_', $data, true);

		echo json_encode($response);
	}

	public function getMPagos(){
		$data['data'] = $this->Sales->getMPagos($this->input->post());
		$response['html'] = $this->load->view('sales/medios', $data, true);

		echo json_encode($response);
	}
	
	public function setSale(){
		$data = $this->Sales->setSale($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}

	public function getArticles(){
		$data['data'] = $this->Sales->getArticles($this->input->post());
		$response['html'] = $this->load->view('articles/salesdetail', $data, true);
		echo json_encode($response);
	}

	public function data($permission)
	{
		$data['list'] = array();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('sales/ingresos', $data, true));
	}

	public function getSales__(){
		$data['data'] = $this->Sales->getSales__($this->input->post());
		$response['html'] = $this->load->view('sales/ingresosdetail', $data, true);
		echo json_encode($response);
	}

	public function caja(){
		echo json_encode($this->load->view('sales/caja', null, true));
	}

	public function printBox(){
		echo json_encode($this->Sales->printBox($this->input->post()));
	}
}