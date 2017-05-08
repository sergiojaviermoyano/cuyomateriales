<?php
class Calendar extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function setVisit($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$cliId = $data['cliId'];
			$dia = $data['dia'];
			$hora = $data['hora'];
			$min = $data['min'];
			$note = $data['note'];
			
			$dia = explode('-', $dia);

			$insert = array(
				   'vstDate' => $dia[2].'-'.$dia[1].'-'.$dia[0].' '.$hora.':'.$min,
				   'cliId' => $cliId,
				   'vstStatus' => 'PN',
				   'vstNote' => $note
				);

			if($this->db->insert('admvisits', $insert) == false) {
				return false;
			}else{
				return "Se programo la visita para el día <br>".$data['dia']." a las ".$data['hora'].":".$data['min'];
			}
		}
	}

	function setReprogramVisit($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$vstId = $data['vstId'];
			$dia = $data['dia'];
			$hora = $data['hora'];
			$min = $data['min'];
			$note = $data['note'];
			
			$dia = explode('-', $dia);

			$update = array(
				   'vstDate' => $dia[2].'-'.$dia[1].'-'.$dia[0].' '.$hora.':'.$min,
				   'vstStatus' => 'PN',
				   'vstNote' => $note
				);

			if($this->db->update('admvisits', $update, array('vstId'=>$vstId)) == false) {
				return false;
			}else{
				return "Se re-programo la visita para el día <br>".$data['dia']." a las ".$data['hora'].":".$data['min'];
			}
		}
	}

	function cancelVisit($data = null){
		if($data == null)
			{
				return false;
			}
			else
			{
				$update = array();
				$update['vstStatus'] = 'VS';
				//Actualizar estado de visita
				if($this->db->update('admvisits', $update, array('vstId'=>$data['vstId'])) == false) {
					return false;
				}
			}
		return "La visita fue cerrada";
	}

	function getSaleData(){
		$data = array();
		$this->db->select('admcustomers.cliId, admcustomers.cliName, admcustomers.cliLastName, admcustomers.cliDni, admcustomers.cliAddress, admcustomers.cliImagePath, IF((sum(admcredits.crdDebe) - sum(admcredits.crdHaber) ) IS NULL, 0 ,(sum(admcredits.crdDebe) - sum(admcredits.crdHaber) )) as balance ');
		$this->db->from('admcustomers');
		$this->db->join('admcredits', ' admcredits.cliId = admcustomers.cliId', 'left');	
		$this->db->group_by("admcustomers.cliId"); 
		$this->db->order_by('admcustomers.cliLastName','asc');
		$this->db->order_by('admcustomers.cliName','asc');
		$query= $this->db->get();

		if ($query->num_rows() != 0){
		 	$data['customers'] = $query->result_array();	
		} else {
			$data['customers'] = array();
		}
		
		$this->db->select('prodId, prodCode, prodDescription, prodPrice, prodMargin');
		$this->db->from('admproducts');
		$this->db->where(array('prodStatus'=>'AC'));
		$this->db->order_by('prodDescription','asc');
		$query = $this->db->get();

		if ($query->num_rows() != 0){
		 	$data['products'] = $query->result_array();	
		} else {
			$data['products'] = array();
		}

		return $data;
	}

	function setSale($data = null){
		if($data == null)
			{
				return false;
			}
			else
			{
				$cliId = $data['cliId'];
				$ped = $data['ped'];
				$to_acount = $data['aCuent'];
				$dia = $data['fecha'];
                $hora = $data['hora'];
                $min = $data['min'];
                $note = $data['note'];

				$userdata = $this->session->userdata('user_data');
				$usrId = $userdata[0]['usrId'];

				$insert = array(
						'cliId' => $cliId,
						'saleEstado' => 'AC',
						'saleDate' => date('Y-m-d H:i:s'),
						'usrId' => $usrId
					);
				

				//Insertar venta
				if($this->db->insert('admsales', $insert) == false) {
					return false;
				} else {
					$idSale = $this->db->insert_id();
					$venta = 0;

					foreach ($ped as $p) {
						$insert = array(
								'saleId' => $idSale,
								'prodId' => $p['prodId'],
								'prodPrice' => $p['prodPrice'],
								'prodDescription' => $p['prodDesc'],
								'prodCant' => $p['prodCant']
							);

						$venta += ($p['prodPrice'] * $p['prodCant']);

						if($this->db->insert('admsalesdetail', $insert) == false) {
							return false;
						}

						//Restar stock
						$insert = array(
								'prodId' => $p['prodId'],
								'stkCant' => $p['prodCant'] * -1,
								'usrId' => $usrId,
								'stkDate' => date('Y-m-d H:i:s'),
								'stkMotive' => 'Venta'
							);

						if($this->db->insert('admstock', $insert) == false) {
							return false;
						}

						//Insertar movimiento
						$insert = array(
						   'crdDescription' => 'Importe venta '. $idSale,
						   'crdDate' => date('Y-m-d H:i:s'),
						   'crdDebe' => $venta,
						   'crdHaber' => 0,
						   'crdNote' => '',
						   'cliId' => $cliId,
						   'usrId' => $usrId,
						   'saleId'	=> $idSale				   
						);

						//Agregar Movimiento 
						if($this->db->insert('admcredits', $insert) == false) {
							return false;
						} 

						//Registrar si entrega plata
						if($to_acount > 0){
							$insert = array(
							   'crdDescription' => 'Pago a cuenta venta '. $idSale,
							   'crdDate' => date('Y-m-d H:i:s'),
							   'crdDebe' => 0,
							   'crdHaber' => $to_acount,
							   'crdNote' => '',
							   'cliId' => $cliId,
							   'usrId' => $usrId,
							   'saleId'	=> $idSale
							);

							//Agregar Movimiento 
							if($this->db->insert('admcredits', $insert) == false) {
								return false;
							} 	
						}

						//Registrar proxima visita
						$dia = explode('-', $dia);

						$insert = array(
							   'vstDate' => $dia[2].'-'.$dia[1].'-'.$dia[0].' '.$hora.':'.$min,
							   'cliId' => $cliId,
							   'vstStatus' => 'PN',
							   'vstNote' => $note
							);

						if($this->db->insert('admvisits', $insert) == false) {
							return false;
						}


					}
					//echo $idSale;
					//var_dump($ped);
				}
			}
		return true;
	}
}

?>