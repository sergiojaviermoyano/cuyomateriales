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
			$data['act'] = $action;

			return $data;
		}
	}

	function setOrder($data = null){
		/*
		id : idOrder,
        act: acOrder,
        obser:  $('#ocObservacion').val(),
        cliId:  $('#cliId').val(),
        lpId:   $('#lpId').val(),
        art:    sale
        */
        if($data == null)
		{
			return false;
		}
		else
		{
			$action = 	$data['act'];
			$id = 		$data['id'];
			$obser = 	$data['obser'];
			$cliId = 	$data['cliId'];
			$lpId =		$data['lpId'];
			$arts = 	$data['art'];
			
			//Datos del vendedor
			$userdata = $this->session->userdata('user_data');
			$usrId = $userdata[0]['usrId'];

			$data = array(
				'ocObservacion'	=>$obser,
				'usrId'			=>$usrId,
				'lpId'			=>$lpId,
				'cliId'			=>$cliId
				);

			switch ($action) {
				case 'Add':
					if($this->db->insert('ordendecompra', $data) == false) {
						return false;
					} else {
						$idOrder = $this->db->insert_id();
				
						foreach ($arts as $a) {
							$insert = array(
									'ocId'	 		=> $idOrder,
									'artId' 		=> $a['artId'],
									//'artCode' 		=> '',
									'artDescripcion'=> $a['artDescription'],
									'artPCosto'		=> $a['artCoste'],
									'artPVenta'		=> $a['artFinal'],
									'ocdCantidad'	=> $a['venCant']
								);
							/*
									artId:          parseInt(this.children[6].textContent),
									artCode:        this.children[1].textContent,
									artDescription: this.children[2].textContent,
									artCoste:       parseFloat(this.children[8].textContent),
									artFinal:       parseFloat(this.children[4].textContent),
									venCant:        parseInt(this.children[3].textContent),
									artVenta:       parseFloat(this.children[7].textContent),
							*/

							if($this->db->insert('ordendecompradetalle', $insert) == false) {
								return false;
							}
						}
					}
					break;
				
				default:
					# code...
					break;
			}
			return true;
		}
	}
	/*
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
	*/

}
?>
