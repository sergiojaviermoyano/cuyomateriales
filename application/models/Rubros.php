<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Rubros extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Rubro_List(){
		$this->db->from('rubros');
		$this->db->order_by('rubDescripcion', 'asc');
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

	function getRubro($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idRub = $data['id'];
			$data = array();

			//Datos del rubro
			$query= $this->db->get_where('rubros',array('rubId'=>$idRub));
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$data['rubro'] = $c[0];

			} else {
				$art = array();
				$art['rubId'] = '';
				$art['rubDescripcion'] = '';
				$art['rubEstado'] = '';
				$data['rubro'] = $art;
			}
			$data['rubro']['action'] = $action;

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

	function setRubro($data = null){
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
            

			$data = array(
				   'rubDescripcion' 				=> $name,
				   'rubEstado' 						=> $status				   
				);

			switch($act){
				case 'Add':
					//Agregar Rubro 
					if($this->db->insert('rubros', $data) == false) {
						return false;
					} 
					break;
				
				 case 'Edit':
				 	//Actualizar rubro
				 	if($this->db->update('rubros', $data, array('rubId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
					
				 case 'Del':
				 	//Eliminar rubro
				 	if($this->db->delete('rubros', array('rubId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
				 	
			}
			return true;

		}
	}
}
?>