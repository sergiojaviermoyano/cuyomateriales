<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class order extends CI_Controller {

	function __construct(){

		parent::__construct();
    $this->load->model('Orders');
		$this->load->model('Lists');
    $this->load->model('Customers');
		$this->Users->updateSession(true);

	}

	public function index($permission){
		$data['orders'] = $this->Orders->Orders_List();
		$data['permission'] = $permission;
		echo json_encode($this->load->view('orders/list', $data, true));
	}


	public function listingOrders(){

		$totalOrdenes=$this->Orders->getTotalOrders($_REQUEST);
		$orders = $this->Orders->Orders_List_datatable($_REQUEST);

		$result=array(
			'draw'=>$_REQUEST['draw'],
			'recordsTotal'=>$totalOrdenes,
			'recordsFiltered'=>$totalOrdenes,
			'data'=>$orders,
		);
		echo json_encode($result);
	}

	public function listingOrdersSales(){
		$totalOrdenes=$this->Orders->getTotalOrdersSales($_REQUEST);
		$orders = $this->Orders->Orders_List_datatable_sales($_REQUEST);

		$result=array(
			'draw'=>$_REQUEST['draw'],
			'recordsTotal'=>$totalOrdenes,
			'recordsFiltered'=>$totalOrdenes,
			'data'=>$orders,
		);
		echo json_encode($result);
	}

	public function listingOrdersPresu(){
		$totalOrdenes=$this->Orders->getTotalOrdersPresu($_REQUEST);
		$orders = $this->Orders->Orders_List_datatable_presu($_REQUEST);

		$result=array(
			'draw'=>$_REQUEST['draw'],
			'recordsTotal'=>$totalOrdenes,
			'recordsFiltered'=>$totalOrdenes,
			'data'=>$orders,
		);
		echo json_encode($result);
	}

	public function getOrder(){

    $data['ListaPrecios']=$this->Lists->List_List();
    $data['Clientes']=$this->Customers->Customers_List();

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


	public function printOrder(){
		echo json_encode($this->Orders->printOrder($this->input->post()));
	}

	public function printRemito(){
		echo json_encode($this->Orders->printRemito($this->input->post()));
	}
}
