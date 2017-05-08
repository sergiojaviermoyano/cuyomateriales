<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Providers extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Providers_List(){

		$this->db->order_by('prvRazonSocial', 'asc');
		
		$query= $this->db->get('proveedores');

		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{	
			return false;
		}
	}
	
	function getProvider($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idPro = $data['id'];

			$data = array();

			//Datos del proveedor
			$query= $this->db->get_where('proveedores',array('prvId'=>$idPro));
			if ($query->num_rows() != 0)
			{
				$p = $query->result_array();
				$data['provider'] = $p[0];
			} else {
				$Pro = array();
				//select max id de cliente
				$this->db->select_max('prvId');
 				$query = $this->db->get('proveedores');
 				$id = $query->result_array();
				$Pro['prvId'] = $id[0]['prvId'] + 1;

				$Pro['prvNombre'] = '';
				$Pro['prvApellido'] = '';
				$Pro['prvRazonSocial'] = '';
				$Pro['docId'] = '';
				$Pro['prvDocumento'] = '';
				$Pro['prvDomicilio'] = '';
				$Pro['prvMail'] = '';
				$Pro['prvEstado'] = '';
				$Pro['prvTelefono'] = '';
				$data['provider'] = $Pro;
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
	
	function setProvider($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
            $act = $data['act'];
            $id = $data['id'];
            $act = $data['act'];
            $nom = $data['nom'];
            $ape = $data['ape'];
            $rz = $data['rz'];
            $tp = $data['tp'];
            $doc = $data['doc'];
            $dom = $data['dom'];
            $mai = $data['mai'];
            $est = $data['est'];
            $tel = $data['tel'];

			$data = array(
				   'prvNombre' 			=> $nom,
				   'prvApellido' 		=> $ape,
				   'prvRazonSocial' 	=> $rz,
				   'docId' 				=> $tp,
				   'prvDocumento' 		=> $doc,
				   'prvDomicilio'		=> $dom,
				   'prvMail'			=> $mai,
				   'prvEstado'			=> $est,
				   'prvTelefono'		=> $tel
				);

			switch($act){
				case 'Add':
					//Agregar Proveedor 
					if($this->db->insert('proveedores', $data) == false) {
						return false;
					} 
					break;
				
				case 'Edit':
				 	//Actualizar Proveedor
				 	if($this->db->update('proveedores', $data, array('prvId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
					
				case 'Del':
				 	//Eliminar Proveedor
				 	if($this->db->delete('proveedores', array('prvId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
			}
			return true;

		}
	}
	
}
?>