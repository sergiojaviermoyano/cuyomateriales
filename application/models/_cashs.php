<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cashs extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Cashs_List(){

		$this->db->select('admcredits.*, admcustomers.cliName, admcustomers.cliLastName, sisusers.usrNick ');
		$this->db->from('admcredits');
		$this->db->join('admcustomers', 'admcustomers.cliId = admcredits.cliId');
		$this->db->join('sisusers', ' sisusers.usrId = admcredits.usrId');
		
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
	
	function getMotion($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idCrd = $data['id'];

			$data = array();

			//Datos del movimiento
			$this->db->select('admcredits.*, sisusers.usrNick ');
			$this->db->from('admcredits');
			$this->db->join('sisusers', ' sisusers.usrId = admcredits.usrId');	
			$this->db->where('admcredits.crdId',$idCrd); // Set Filter		
			$query= $this->db->get();

			if ($query->num_rows() != 0)
			{
				$m = $query->result_array();
				$data['motion'] = $m[0];
			} else {
				$mov = array();
				$mov['crdId'] = '';
				$mov['crdDescription'] = '';
				$mov['crdDate'] = '';
				$mov['crdDebe'] = '';
				$mov['crdHaber'] = '';
				$mov['crdNote'] = '';
				$data['motion'] = $mov;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			//Customers
			$query= $this->db->get('admcustomers');
			if ($query->num_rows() != 0)
			{
			 	$data['customers'] = $query->result_array();	
			}
			
			return $data;
		}
	}
	
	function setMotion($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
            $act = $data['act'];
            $cliId = $data['cliId'];
            $desc = $data['desc'];
            $debe = $data['debe'];
            $haber = $data['haber'];
            $note = $data['note'];

            $userdata = $this->session->userdata('user_data');
			$usrId = $userdata[0]['usrId'];

			$data = array(
				   'crdDescription' => $desc,
				   'crdDate' => date('Y-m-d H:i:s'),
				   'crdDebe' => $debe,
				   'crdHaber' => $haber,
				   'crdNote' => $note,
				   'cliId' => $cliId,
				   'usrId' => $usrId				   
				);

			switch($act){
				case 'Add':
					//Agregar Movimiento 
					if($this->db->insert('admcredits', $data) == false) {
						return false;
					} 
					break;

				case 'Del':
					//Eliminar movimiento
					if($this->db->delete('admcredits', array('crdId'=>$id)) == false) {
						return false;
					}
					break;
			}
			return true;

		}
	}

	function getBalance(){
		$data = array();
		$this->db->select('admcustomers.cliId, admcustomers.cliName, admcustomers.cliLastName, IF((sum(admcredits.crdDebe) - sum(admcredits.crdHaber) ) IS NULL, 0 ,(sum(admcredits.crdDebe) - sum(admcredits.crdHaber) )) as balance ');
		$this->db->from('admcustomers');
		$this->db->join('admcredits', ' admcredits.cliId = admcustomers.cliId', 'left');	
		$this->db->group_by("admcustomers.cliId"); 
		$query= $this->db->get();

		if ($query->num_rows() != 0)
		{
		 	$data['customers'] = $query->result_array();	
		}
		
		return $data;
	}

	function setPay($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$insert = array();

			$insert['id'] = 0;
            $insert['act'] = 'Add';
            $insert['cliId'] = $data['cliId'];
            $insert['desc'] = 'Pago a cuenta';
            $insert['debe'] = 0;
            $insert['haber'] = $data['price'];
            $insert['note'] = '';

			if($this->setMotion($insert) == true)
			{
				$update = array();
				$update['vstStatus'] = 'VS';
				//Actualizar estado de visita
				if($this->db->update('admvisits', $update, array('vstId'=>$data['vstId'])) == false) {
					return false;
				}

				$this->db->select_max('crdId');
				$query = $this->db->get('admcredits');

				$m = $query->result_array();
				$id = $m[0]['crdId'];

				//Consultar el saldo 
				$this->db->select('admcustomers.cliId, IF((sum(admcredits.crdDebe) - sum(admcredits.crdHaber) ) IS NULL, 0 ,(sum(admcredits.crdDebe) - sum(admcredits.crdHaber) )) as balance ');
				$this->db->from('admcustomers');
				$this->db->join('admcredits', ' admcredits.cliId = admcustomers.cliId', 'left');	
				$this->db->where('admcustomers.cliId', $data['cliId']);
				$this->db->group_by("admcustomers.cliId"); 
				$query= $this->db->get();

				$s = $query->result_array();
				$saldo = $s[0]['balance'];

				$visita_ = "";

				if($saldo > 0)
				{
					//programar proxima visita
					$this->db->select('cliDay');
					$query = $this->db->get_where('admcustomers', array('cliId'=>$data['cliId']));

					$d = $query->result_array();
					$dias = $d[0]['cliDay'];

					$this->db->select('vstDate');
					$query = $this->db->get_where('admvisits', array('vstId'=>$data['vstId']));

					$v = $query->result_array();
					$visit = $v[0]['vstDate'];

					$fecha = date($visit);
					$nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
					$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );

					$insert = array(
						   'vstDate' => $nuevafecha,
						   'cliId' => $data['cliId'],
						   'vstStatus' => 'PN',
						   'vstNote' => $data['note']
						);

					if($this->db->insert('admvisits', $insert) == false) {
						return false;
					}else{
						$fecha = explode(' ', $nuevafecha);
						$dia = explode('-', $fecha[0]);
						$hora = explode(':', $fecha[1]);
						$visita_ = '<br> y se programo la visita para el día <br>'.$dia[2].'-'.$dia[1].'-'.$dia[0].' a las '.$hora[0].':'.$hora[1].'<br>';
						//$visita_.= '<center><button type="button" class="btn btn-success pull-left" id="btnReprogram_">Reprogramar</button></center><br>';
					}
				}
				//------------------
				$id=str_pad($id, 10, "0", STR_PAD_LEFT);
				return "Se registro el pago con el número de operación ".$id." ".$visita_;
			}
			else
			{
				return false;
			}
		}
	}
	
}
?>