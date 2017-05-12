<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Orders extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Orders_List(){

		$this->db->order_by('ocFecha', 'asc');

		$query= $this->db->get('ordendecompra');

		if ($query->num_rows()!=0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function getOrder($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idOrder = $data['id'];

			$data = array();

			//Datos del proveedor
			$query= $this->db->get_where('ordendecompra',array('ocId'=>$idOrder));
			if ($query->num_rows() != 0)
			{
				$order = $query->result_array();
				$data['order'] = $order[0];
			} else {
				$Order = array();
				$this->db->select_max('ocId');
 				$query = $this->db->get('ordendecompra');
 				$id = $query->result_array();
				$Order['ocId'] = $id[0]['ocId'] + 1;

				$Order['ocObservacion'] = '';
				$Order['ocFecha'] = '';
				$Order['ocEstado'] = '';
				$Order['ocEsPresupuesto'] = '';
				$Order['usrId'] = '';
				$Order['lpId'] = '';
				$Order['cliId'] = '';
				$data['order'] = $Order;
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
