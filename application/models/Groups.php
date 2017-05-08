<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Groups extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Group_List(){

		$query= $this->db->get('sisgroups');
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}

	function getMenu($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idGrp = $data['id'];

			if($idGrp == 0){
				$name = "";
			} else {
				$query= $this->db->get_where('sisgroups',array('grpId'=>$idGrp));
				if ($query->num_rows() != 0) {				
					$name = $query->row('grpName');
				} else {
					$name = "";
				}
			}

			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$menu = array();
			$menu['read'] = $readonly;
			$menu['name'] = $name;
			$menu['list'] = array();

			$query= $this->db->get_where('sismenu',array('menuFather'=>null));
			if ($query->num_rows() != 0)
			{
				foreach($query->result() as $items)
				{
					//ver si tiene hijos
					$querySon= $this->db->get_where('sismenu',array('menuFather'=>$items->menuId));
					if($querySon->num_rows() != 0)
					{
						//Añadir los hijos
						$items->childrens = $querySon->result();
						foreach ($items->childrens as $son) {
							$this->db->select('sismenuactions.*, sisactions.actDescriptionSpanish as actDescription, sisgroupsactions.grpactId ');
							$this->db->from('sismenuactions');
							$this->db->join('sisactions', 'sisactions.actId = sismenuactions.actId');
							$this->db->join('sisgroupsactions', ' sismenuactions.menuAccId = sisgroupsactions.menuAccId And sisgroupsactions.grpId = '.$idGrp.'', 'left');
							$this->db->where(array('menuId'=>$son->menuId));

							$queryActions= $this->db->get();
							$son->actions = $queryActions->result_array();	
							$son->childrens = array();
						}
						$items->actions = array();
						$menu['list'][] = $items;
					}
					else
					{
						//Buscar las acciones
						$items->childrens = array();
						$this->db->select('sismenuactions.*, sisactions.actDescriptionSpanish as actDescription, sisgroupsactions.grpactId ');
						$this->db->from('sismenuactions');
						$this->db->join('sisactions', 'sisactions.actId = sismenuactions.actId');
						$this->db->join('sisgroupsactions', ' sismenuactions.menuAccId = sisgroupsactions.menuAccId And sisgroupsactions.grpId = '.$idGrp.'', 'left');
						$this->db->where(array('menuId'=>$items->menuId));

						$queryActions= $this->db->get();
						$items->actions = $queryActions->result_array();
						$menu['list'][] = $items;
					}

				}

				return $menu;	
			}
			return $data;
		}
	}

	function setMenu($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
			$act = $data['act'];
			$name = $data['name'];
			$options = $data['options'];

			$data = array(
					   'grpName' => $name
					);

			switch($act){
				case 'Add':
					//Agregar grupo 
					if($this->db->insert('sisgroups', $data) == false) {
						return false;
					}else{
						$id = $this->db->insert_id();
					}

					//Agregar a sisgroupsactions
					foreach ($options as $o) {
						$data = array(
						   'grpId' => $id,
						   'menuAccId' => $o
						);
						if($this->db->insert('sisgroupsactions', $data) == false) {
							return false;
						}
					}
					break;

				case 'Edit':
					//Actualizar nombre
					if($this->db->update('sisgroups', $data, array('grpId'=>$id)) == false) {
						return false;
					}

					//Eliminar en sisgroupsactions
					if($this->db->delete('sisgroupsactions', array('grpId' => $id)) == false) {
						return false;
					}

					//Agregar a sisgroupsactions
					foreach ($options as $o) {
						$data = array(
						   'grpId' => $id,
						   'menuAccId' => $o
						);
						if($this->db->insert('sisgroupsactions', $data) == false) {
							return false;
						}
					}	
					break;

				case 'Del':
					//Eliminar en sisgroupsactions
					if($this->db->delete('sisgroupsactions', array('grpId' => $id)) == false) {
						return false;
					}

					//Eliminar nombre
					if($this->db->delete('sisgroups', $data, array('grpId'=>$id)) == false) {
						return false;
					}
					
					break;
			}

			return true;

		}
	}

	function buildMenu(){
		$userdata = $this->session->userdata('user_data');
		$grpId = $userdata[0]['grpId'];


		$this->db->select('sismenu.*');
		$this->db->from('sisgroups');
		$this->db->join('sisgroupsactions', 'sisgroupsactions.grpId = sisgroups.grpId');
		$this->db->join('sismenuactions', 'sismenuactions.menuAccId = sisgroupsactions.menuAccId');
		$this->db->join('sismenu', 'sismenu.menuId = sismenuactions.menuId');
		$this->db->where('sisgroups.grpId', $grpId);
		$this->db->group_by('sismenu.menuName');
		$this->db->order_by("sismenu.menuId", "asc");
		$this->db->order_by("sismenu.menuFather", "asc");
		$query = $this->db->get();
		
		$menu = $query->result_array();

		$main_menu = array();
		$father = 0;
		foreach ($menu as $m) {
			if($m['menuFather'] != null){
				if($father != $m['menuFather']) { 
					$father = $m['menuFather'];
					$son = $m;
					$item = $this->db->get_where('sismenu',array('menuId'=>$m['menuFather']));
					$m = $item->result_array();
					$m['actions'] = array();
					foreach($menu as $s) {
						if($s['menuFather'] == $father) {
							$this->db->select('sismenuactions.*, sisactions.actDescription, sisgroupsactions.grpactId ');
							$this->db->from('sismenuactions');
							$this->db->join('sisactions', 'sisactions.actId = sismenuactions.actId');
							$this->db->join('sisgroupsactions', ' sismenuactions.menuAccId = sisgroupsactions.menuAccId And sisgroupsactions.grpId = '.$grpId.'', 'left');
							$this->db->where(array('menuId'=>$s['menuId']));

							$queryActions= $this->db->get();
							$s['actions'] = $queryActions->result_array();

							$m['childrens'][] =	$s;
						}
					}

					$main_menu[] = $m;					
				}
			} else {
				$this->db->select('sismenuactions.*, sisactions.actDescription, sisgroupsactions.grpactId ');
				$this->db->from('sismenuactions');
				$this->db->join('sisactions', 'sisactions.actId = sismenuactions.actId');
				$this->db->join('sisgroupsactions', ' sismenuactions.menuAccId = sisgroupsactions.menuAccId And sisgroupsactions.grpId = '.$grpId.'', 'left');
				$this->db->where(array('menuId'=>$m['menuId']));

				$queryActions= $this->db->get();
				$m['actions'] = $queryActions->result_array();	
				$m['childrens'] = array();

				$main_menu[] = $m;
			}		
		}
		
		return $main_menu;
	}
}
?>