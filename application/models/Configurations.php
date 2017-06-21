<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Configurations extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_(){

		$data = array();

		$query= $this->db->get('configuracion');
		if ($query->num_rows() != 0)
		{
			$c = $query->result_array();
			$data['conf'] = $c[0];
		} 
		return $data;
	}

	function set_($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$insert = array(
					'title1'				=> $data['title1'],
					'title2'				=> $data['title2'],
					'utilizaordendecompra'	=> $data['orcomp'],
					'validezpresupuesto'	=> $data['dias']
				);

			if($this->db->update('configuracion', $insert) == false) {
				return false;
			} else return true;

		}
	}

}
