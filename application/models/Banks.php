<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Banks extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Bank_List(){

		$query= $this->db->get('bancos');
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}
	
	function getBank($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$id = $data['id'];

			$data = array();

			$query= $this->db->get_where('bancos',array('bancoId'=>$id));
			if ($query->num_rows() != 0)
			{
				$f = $query->result_array();
				$data['bank'] = $f[0];
			} else {
				$bank = array();
				$bank['bancoDescripcion'] = '';
				$bank['bancoEstado'] = '';
				$data['bank'] = $bank;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			return $data;
		}
	}
	
	function setBank($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
			$act = $data['act'];
			$name = $data['name'];
			$esta = $data['esta'];

			$data = array(
					   'bancoDescripcion'	=> $name,
					   'bancoEstado'		=> $esta
					);

			switch($act){
				case 'Add':
					if($this->db->insert('bancos', $data) == false) {
						return false;
					}
					break;

				case 'Edit':
					if($this->db->update('bancos', $data, array('bancoId'=>$id)) == false) {
						return false;
					}
					break;

				case 'Del':
					if($this->db->delete('bancos', $data, array('bancoId'=>$id)) == false) {
						return false;
					}
					
					break;
			}


			return true;

		}
	}

	function getActiveBanks(){
		$query = $this->db->query('
				Select *
				From bancos
				Where bancoEstado = \'AC\'
				Order by bancoDescripcion');
		return $query->result_array();
	}
}
?>