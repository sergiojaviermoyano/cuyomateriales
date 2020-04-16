<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Checks extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Check_List(){

		$this->db->select('cheques.*, bancos.bancoDescripcion');
		$this->db->from('cheques');
		$this->db->join('bancos', 'bancos.bancoId = cheques.bancoId');
		$query= $this->db->get();
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}
	
	function getCheck($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$id = $data['id'];

			$data = array();

			$query= $this->db->get_where('cheques',array('cheId'=>$id));
			if ($query->num_rows() != 0)
			{
				$t = $query->result_array();
				$data['check'] = $t[0];
			} else {
				$check = array();
				$check['cheNumero'] 		= '';
				$check['cheImporte'] 		= '0';
				$check['cheVencimiento'] 	= '';
				$check['bancoId'] 			= '';
				$check['cheEstado'] 		= 'AC';
				$check['cheObservacion'] 	= '';
				$check['cliId'] 			= '';
				$data['check'] = $check;
			}

			//Readonly
			$readonly = false;
			if($action == 'View' || $action == 'Dep'){
				$readonly = true;
			}
			$data['read'] = $readonly;
			$data['act'] = $action;

			return $data;
		}
	}

	function setCheck($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id=    	$data['id']; 
			$act=   	$data['act'];
			$cliId=  	$data['cliId'];
			$cheNro= 	$data['cheNro'];
			$cheImp= 	$data['cheImp'];
			$cheVto= 	$data['cheVto'];
			$bancoI=	$data['bancoI'];
			$cheObs= 	$data['cheObs'];
			$cheDis=	$data['cheDis'];
			$cheDet=	$data['cheDet']; 	

			$cheVto = strtotime($cheVto);
			$cheVto = date('Y-m-d', $cheVto);

			$userdata = $this->session->userdata('user_data');

			$data = array(
					   'cheNumero' 		=> $cheNro,
					   'cheImporte'		=> $cheImp,
					   'cheVencimiento'	=> $cheVto,
					   'bancoId'		=> $bancoI,  
					   'cheObservacion'	=> $cheObs,
					   'cliId'			=> $cliId
					);

			switch($act){
				
				case 'Add':
					$data['usrIdCreate'] = $userdata[0]['usrId'];
					if($this->db->insert('cheques', $data) == false) {
						return false;
					}
					break;

				case 'Dep':
					$data['usrIdDispose'] 	= $userdata[0]['usrId'];
					$data['cheDispose'] 	= date("Y-m-d H:i:s");
					$data['cheDetalle']		= $cheDet;
					$data['cheEstado'] 		= $cheDis;
					if($this->db->update('cheques', $data, array('cheId' => $id)) == false) {
						return false;
					}
					break;

			}

			//var_dump($this->db->last_query());

			return true;

		}
	}

	function vencimientos($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$month = $data['month'] + 1;
			$estados = array('AC', 'FA');
            $this->db->select('cheques.*, DATEDIFF(cheques.cheVencimiento,NOW()) as dias, bancoDescripcion');
            $this->db->from('cheques');
            $this->db->join('bancos', 'cheques.bancoId = bancos.bancoId');
			$this->db->where(array('month(cheVencimiento)' => $month));
			$query = $this->db->get();

			//echo $this->db->last_query();
            if ($query->num_rows()!=0)
            {
                return $query->result_array();
            }
            else
            {
                return false;
            }
        }
    }
}
?>