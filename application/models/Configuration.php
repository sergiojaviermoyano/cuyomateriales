<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Configuration extends CI_Model
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

}
