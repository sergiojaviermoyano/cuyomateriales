<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lists extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function List_List(){
		$this->db->from('listadeprecios');
		$this->db->order_by('lpDescripcion', 'asc');
		$query = $this->db->get(); 
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}

	function getList($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idLis = $data['id'];
			$data = array();

			//Datos del rubro
			$query= $this->db->get_where('listadeprecios',array('lpId'=>$idLis));
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$data['lista'] = $c[0];

			} else {
				$art = array();
				$art['lpId'] = '';
				$art['lpDescripcion'] = '';
				$art['lpEstado'] = '';
				$art['lpMargen'] = '';
				$art['lpDefault'] = 0;
				$data['lista'] = $art;
			}
			$data['lista']['action'] = $action;

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;
			$data['action'] = $action;
			
			return $data;
		}
	}

	function setList($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id 	= $data['id'];
            $act 	= $data['act'];
            $name 	= $data['name'];
            $status = $data['status'];
            $margin = $data['margin'];
            $defau  = $data['def'];            

			$data = array(
				   'lpDescripcion' 				=> $name,
				   'lpEstado'					=> $status,
				   'lpMargen'					=> $margin,
				   'lpEstado'					=> $status,
				   'lpDefault'					=> ($defau === 'true')
				);

			if(($defau === 'true')){
				//Actualizar todos a 0
				$update = array('lpDefault' => false);
				if($this->db->update('listadeprecios', $update) == false) {
				 		return false;
				}
			}
			switch($act){
				case 'Add':
					//Agregar Lista de precio 
					if($this->db->insert('listadeprecios', $data) == false) {
						return false;
					} 
					break;
				
				 case 'Edit':
				 	//Actualizar Lista de precio
				 	if($this->db->update('listadeprecios', $data, array('lpId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
					
				 case 'Del':
				 	//Eliminar Lista de precio
				 	if($this->db->delete('listadeprecios', array('lpId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
				 	
			}
			return true;

		}
	}
}