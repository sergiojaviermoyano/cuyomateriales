<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Receptions extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Reception_List($data_ = null){
		$data = array();
		
		if($data_ == null){
			$this->db->select('receptions.*, proveedores.prvNombre, proveedores.prvApellido, proveedores.prvRazonSocial');
			$this->db->from('receptions');
			$this->db->join('proveedores', ' proveedores.prvId = receptions.prvId');
			$this->db->order_by('receptions.recId', 'desc');
			$this->db->limit(10);
			$query = $this->db->get();
			
			$data['page'] = 1;
			$data['totalPage'] = ceil($this->db->count_all_results('receptions') / 10);
			$data['data'] = $query->result_array();
		} else {
			$this->db->select('receptions.*, proveedores.prvNombre, proveedores.prvApellido, proveedores.prvRazonSocial');
			$this->db->from('receptions');
			$this->db->join('proveedores', ' proveedores.prvId = receptions.prvId');
			$this->db->order_by('receptions.recId', 'desc');
			$this->db->or_like('recId', $data_['txt']);
			$this->db->or_like('prvNombre', $data_['txt']);
			$this->db->or_like('prvApellido', $data_['txt']);
			$this->db->or_like('prvRazonSocial', $data_['txt']);
			$this->db->limit(10, (($data_['page'] - 1) * 10));
			$query= $this->db->get();
			$data['page'] = $data_['page'];
			$data['data'] = $query->result_array();

			$this->db->select('*');
			$this->db->from('receptions');
			$this->db->join('proveedores', ' proveedores.prvId = receptions.prvId');
			$this->db->order_by('receptions.recId', 'desc');
			$this->db->or_like('prvNombre', $data_['txt']);
			$this->db->or_like('prvApellido', $data_['txt']);
			$this->db->or_like('prvRazonSocial', $data_['txt']);
			$query= $this->db->get();

			$data['totalPage'] = ceil($query->num_rows() / 10);
		}
		
		return $data;
	}
	
	function getReception($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$id = $data['id'];

			$data = array();
			$data['articles'] = array();

			$this->db->select('prvId, prvNombre, prvApellido, prvRazonSocial');
			$this->db->from('proveedores');
			$this->db->order_by('prvRazonSocial');
			$query = $this->db->get();
			$data['providers'] = $query->result_array();

			$query= $this->db->get_where('receptions',array('recId'=>$id));
			if ($query->num_rows() != 0)
			{
				$r = $query->result_array();
				$data['reception'] = $r[0];

				$this->db->select('articles.artBarCode, articles.artDescription, receptionsdetail.artId, receptionsdetail.recdCant');
				$this->db->from('articles');
				$this->db->join('receptionsdetail', 'receptionsdetail.artId = articles.artId');
				$this->db->where(array('recId'=> $id));
				$query = $this->db->get();
				$data['articles'] = $query->result_array();
			} else {
				$rec = array();
				$rec['recFecha'] = '';
				//$rec['recEstado'] = '';
				$rec['prvId'] = '';
				$rec['recObservacion'] = '';
				$data['reception'] = $rec;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View' || $action == 'Conf' || $action == 'Disc'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			return $data;
		}
	}
	
	function setReception($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$arts = $data['rec'];
			$id 	= $data['id_'];
	        $act 	= $data['act'];
	        $prvId  = $data['prvId'];
	        $date 	= $data['date'];
	        $obsv 	= $data['obsv'];
			
			$date = explode('-', $date);
			$data = array(
					   'prvId'		 	=> $prvId,
					   'recObservacion'	=> $obsv,
					   'recFecha'		=> $date[2].'-'.$date[1].'-'.$date[0],
					   'recEstado'		=> 'AC'
					);
			
			switch($act){
				case 'Add':
					if($this->db->insert('receptions', $data) == false) {
						return false;
					} else {
						$idRec = $this->db->insert_id();

						foreach ($arts as $a) {
							$insert = array(
									'recId' 		=> $idRec,
									'artId' 		=> $a['artId'],
									'recdCant'		=> $a['recCant']
								);

							if($this->db->insert('receptionsdetail', $insert) == false) {
								return false;
							}
						}	
					}
					break;

				case 'Conf':
					$data = array(
					   'recEstado'		=> 'CF'
					);
					if($this->db->update('receptions', $data, array('recId'=>$id)) == false) {
						return false;
					} else {
						$query = $this->db->get_where('receptionsdetail', array('recId'=>$id));
						foreach ($query->result_array() as $art) {
							$insert = array(
									'recId' 		=> $id,
									'artId' 		=> $art['artId'],
									'stkCant'		=> $art['recdCant'],
									'stkOrigen'		=> 'RC'
								);

							if($this->db->insert('stock', $insert) == false) {
								return false;
							}
						}
					}
					break;

				case 'Disc':
					$data = array(
					   'recEstado'		=> 'DS'
					);
					if($this->db->update('receptions', $data, array('recId'=>$id)) == false) {
						return false;
					}
					break;
			}
			

			return true;

		}
	}
}
?>