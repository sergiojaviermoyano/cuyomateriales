<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stocks extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getStockByArtId($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			//Fecha 
			$df = $data['dtFrm'];
			$dt = $data['dtTo'];

			$artId = $data['artId'];
			$dtFrm = explode('-', $df);
			$dtFrm = $dtFrm[2].'-'.$dtFrm[1].'-'.$dtFrm[0];
			$dtTo = explode('-', $dt);
			$dtTo = $dtTo[2].'-'.$dtTo[1].'-'.$dtTo[0];
			$fecha = date($dtTo);
			$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
			$dtTo = date ('Y-m-j' , $nuevafecha );

			$data = array();


			//Fecha 
			$data['dateF'] = $df;
			$data['dateT'] = $dt;

			//Movimientos
			$this->db->select('*');
			$this->db->from('stock');
			$this->db->where(array('stkFecha >=' => $dtFrm, 'stkFecha <= ' => $dtTo, 'artId' => $artId));
			$query= $this->db->get();
			$data['mov'] = $query->result_array();

			//Stock Filtro
			$this->db->select('sum(stkCant) as cant');
			$this->db->from('stock');
			$this->db->where(array('stkFecha >=' => $dtFrm, 'stkFecha <= ' => $dtTo, 'artId' => $artId));
			$query= $this->db->get();
			$data['stkF'] = $query->result_array();

			//Stock Actual
			$this->db->select('sum(stkCant) as cant');
			$this->db->from('stock');
			$this->db->where(array('artId' => $artId));
			$query= $this->db->get();
			$data['stk'] = $query->result_array();

			//Articulo
			$this->db->select('artDescription, artBarCode');
			$this->db->from('articles');
			$this->db->where(array('artId' => $artId));
			$query= $this->db->get();
			$data['art'] = $query->result_array();

			return $data;
		}


	}

	function setFit($data = null){
		if($data == null) {
			return false;
		} else {
			$art = $data['artId'];
			$cnt = $data['cant'];

			//Agregar Ajuste

			$data = array(
			   'artId' 			=> $art,
			   'stkCant'		=> $cnt,
			   'stkOrigen'		=> 'AJ'
			);

			if($this->db->insert('stock', $data) == false) {
				return false;
			}

			return true;
		}
	}
}
?>