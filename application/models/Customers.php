<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Customers extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Customers_List(){

		$this->db->order_by('cliApellido','asc');
		$this->db->order_by('cliNombre','asc');
		$this->db->select('clientes.*, tipos_documentos.docDescripcion');
		$this->db->from('clientes');
		$this->db->join('tipos_documentos', 'tipos_documentos.docId = clientes.docId', 'left');
		$query= $this->db->get();
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}
	
	function getCustomer($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idCust = $data['id'];

			$data = array();

			//Datos del usuario
			$query= $this->db->get_where('clientes',array('cliId'=>$idCust));
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$data['customer'] = $c[0];
			} else {
				$cust = array();
				
				//select max id de cliente
				$this->db->select_max('cliId');
 				$query = $this->db->get('clientes');
 				$id = $query->result_array();
				$cust['cliId'] = $id[0]['cliId'] + 1;

				$cust['cliNombre'] = '';
				$cust['cliApellido'] = '';
				$cust['cliDocumento'] = '';
				$cust['cliDomicilio'] = '';
				$cust['cliTelefono'] = '';
				$cust['cliMail'] = '';
				$cust['cliEstado'] = '';
				$cust['docId'] = '';

				$data['customer'] = $cust;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			//Tipos de Documento
			$this->db->order_by('docDescripcion');
			$query= $this->db->get_where('tipos_documentos',array('DP'));
			if ($query->num_rows() != 0)
			{
				$data['docs'] = $query->result_array();	
			}
			return $data;
		}
	}
	
	function setCustomer($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
			$act = $data['act'];
			$nro = $data['nro'];
			$name = $data['name'];
			$lnam = $data['lnam'];
			$doc = $data['doc'];
			$dni = $data['dni'];
			$mail = $data['mail'];
			$dom = $data['dom'];
			$tel = $data['tel'];
			$est = $data['est'];

			$data = array(
				   'docId' => $doc,
				   'cliDocumento' => $dni,
				   'cliNombre' => $name,
				   'cliApellido' => $lnam,
				   'cliDomicilio' => $dom,
				   'cliTelefono' => $tel,
				   'cliMail' => $mail,
				   'cliEstado' => $est
				);

			switch($act){
				case 'Add':
					//Agregar Cliente 
					if($this->db->insert('clientes', $data) == false) {
						return false;
					} 
					break;

				 case 'Edit':
				 	//Actualizar Cliente
				 	if($this->db->update('clientes', $data, array('cliId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;

				 case 'Del':
				 	//Eliminar cliente
				 	if($this->db->delete('clientes', array('cliId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
			}
			return true;

		}
	}	
}
?>