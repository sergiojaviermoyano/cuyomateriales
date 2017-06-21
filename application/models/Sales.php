<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sales extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getView($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$response = array();
			//Siempre preguntar si esta abierta la caja (para las opciones 1 y 2)
			//para el usuario logueado
			$userdata = $this->session->userdata('user_data');
			
			//verificar si hay cajas abiertas
			$this->db->select('*');
			$this->db->where(array('cajaCierre'=>null, 'usrId' => $userdata[0]['usrId']));
			$this->db->from('cajas');
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0){
				$result = $query->result_array();
				$response['cajaId'] = $result[0]['cajaId'];
				$caja = $result[0];
			} else {
				$response['cajaId'] = -1;
				$response['caja'] = null;
			}

			switch ($data['id']) {
				case '1':
					$query = $this->db->get('configuracion');
					if ($query->num_rows() != 0)
					{
						$configuration = $query->result_array();
						$response['validez'] = $configuration[0];
					}
					$response['ordenes'] = array();

					break;
				
				case '2':
					$response['ventas'] = array(); 
					break;

				case '3':
					if($response['cajaId'] != -1){
						$response['caja'] = $caja;
						//calcular ventas
						$this->db->select('sum(ventasdetalle.artFinal * ventasdetalle.venCant) as suma', false);
						$this->db->from('ventasdetalle');
						$this->db->join('ventas', 'ventas.venId = ventasdetalle.venId');
						$this->db->where(array('ventas.cajaId'=>$response['cajaId'], 'ventas.venEstado' => 'AC'));
						$query = $this->db->get();
						$response['caja']['cajaImpVentas'] = $query->row()->suma == null ? '0.00' : $query->row()->suma;

						$query = $this->db->query('select r.medId, m.medDescripcion, sum(r.rcbImporte) as importe from recibos as r 
												  join ventas as v on v.venId = r.venId
												  join mediosdepago as m on m.medId = r.medId
												  where v.cajaId = '.$response['cajaId'].'
												  GROUP BY r.medId');

						$response['caja']['medios'] = $query->result_array();

					}
					$response['user'] = $userdata[0];
					break;
			}

			return $response;
		}
	}

	function getTotalVentas($data=null){
		$response = array();
		//Siempre preguntar si esta abierta la caja (para las opciones 1 y 2)
		//para el usuario logueado
		$userdata = $this->session->userdata('user_data');
		
		//verificar si hay cajas abiertas
		$this->db->select('*');
		$this->db->where(array('cajaCierre'=>null, 'usrId' => $userdata[0]['usrId']));
		$this->db->from('cajas');
		$query = $this->db->get();
		$result = $query->result_array();
		if(count($result) > 0){
			$result = $query->result_array();
			$response['cajaId'] = $result[0]['cajaId'];
			$caja = $result[0];
		} else {
			$response['cajaId'] = -1;
			$response['caja'] = null;
		}


		$this->db->order_by('venFecha', 'desc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('venFecha', $data['search']['value']);
		}
		$query= $this->db->get_where('ventas', array('cajaId' => $response['cajaId']));
		return $query->num_rows();
	}

	function Ventas_List_datatable($data=null){

		$response = array();
		//Siempre preguntar si esta abierta la caja (para las opciones 1 y 2)
		//para el usuario logueado
		$userdata = $this->session->userdata('user_data');
		
		//verificar si hay cajas abiertas
		$this->db->select('*');
		$this->db->where(array('cajaCierre'=>null, 'usrId' => $userdata[0]['usrId']));
		$this->db->from('cajas');
		$query = $this->db->get();
		$result = $query->result_array();
		if(count($result) > 0){
			$result = $query->result_array();
			$response['cajaId'] = $result[0]['cajaId'];
			$caja = $result[0];
		} else {
			$response['cajaId'] = -1;
			$response['caja'] = null;
		}


		$this->db->order_by('venFecha', 'desc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('venFecha', $data['search']['value']);
		}
		$query= $this->db->get_where('ventas', array('cajaId' => $response['cajaId']));

		if ($query->num_rows()!=0)
		{

			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function getOrder($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$idOrder = $data['id'];
			$data = array();
			$query = $this->db->get_where('ordendecompra',array('ocId'=>$idOrder));
			if ($query->num_rows() != 0)
			{
				$order = $query->result_array();
				$data['order'] = $order[0];

				$query = $this->db->get_where('ordendecompradetalle',array('ocId'=>$idOrder));
				if ($query->num_rows() != 0)
				{
					$orderD = $query->result_array();
					$data['orderdetalle'] = array();
					foreach ($orderD as $item) {
						$query= $this->db->get_where('articles',array('artId' => $item['artId']));
						if ($query->num_rows() != 0)
						{
							$art = $query->result_array();
							$item['artBarCode'] = $art[0]['artBarCode'];
						}

						$data['orderdetalle'][] = $item;		
					}
					
				}

				//Get Lista de Precios
				$query= $this->db->get_where('listadeprecios',array('lpId' => $data['order']['lpId']));
				if ($query->num_rows() != 0)
				{
					$lista = $query->result_array();
					$data['lista'] = $lista[0];
				}

				//Get Usuario
				$query= $this->db->get_where('sisusers',array('usrId' => $data['order']['usrId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['user'] = $user[0];
				}	
			}

			
			return $data;
		}
	}

	function getMPagos($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
			$total = $data['to'];

			$data = array();
			$data['idOrden'] = $id;
			$data['total']	= $total;
			$data['tmp']	= array();

			$query = $this->db->get_where('tipomediopago',array('tmpEstado'=>'AC'));
			if ($query->num_rows() != 0)
				{
					$tmp = $query->result_array();
					//$data['tmp'] = $tmp;
					$data['tmp'] = array();
					foreach ($tmp as $item) {
						$query = $this->db->get_where('mediosdepago',array('medEstado'=>'AC', 'tmpId' => $item['tmpId']));	
						if ($query->num_rows() != 0)
						{
							$tmpD = $query->result_array();
							$item['tmpD'] = $tmpD;
						} else { 
							$item['tmpD'] = array(); 
						}

						$data['tmp'][] = $item;	
					}
				}	
			return $data;
		}
	}

	function setSale($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$ocId = $data['id'];
			$pago = $data['pa'];

			//Datos del usuario
			$userdata = $this->session->userdata('user_data');
			$usrId = $userdata[0]['usrId'];

			//Datos de la caja 
			$this->db->select('*');
			$this->db->where(array('cajaCierre'=>null, 'usrId' => $usrId));
			$this->db->from('cajas');
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0){
				$result = $query->result_array();
				$cajaId = $result[0]['cajaId'];
			} else {
				return false;
			}

			//Datos de la orden
			$orden = $this->getOrder($data);

			$venta = array(
				'usrId'			=> $usrId,
				'cajaId'		=> $cajaId,
				'cliId'			=> $orden['order']['cliId']
				);

			$this->db->trans_start();
			if($this->db->insert('ventas', $venta) == false) {
				return false;
			} else {
				$idVenta = $this->db->insert_id();
				
				//Actualizar detalle
				foreach ($orden['orderdetalle'] as $a) {
					$insert = array(
							'venId' 		=> $idVenta,
							'artId' 		=> $a['artId'],
							'artCode' 		=> '',
							'artDescription'=> $a['artDescripcion'],
							'artCoste'		=> $a['artPCosto'],
							'artFinal'		=> $a['artPVenta'],
							'venCant'		=> $a['ocdCantidad']
						);

					if($this->db->insert('ventasdetalle', $insert) == false) {
						return false;
					}

					$insert = array(
							'artId' 		=> $a['artId'],
							'stkCant'		=> $a['ocdCantidad'] * -1,
							'stkOrigen'		=> 'VN'
						);

					if($this->db->insert('stock', $insert) == false) {
						return false;
					}
				}

				//Insertar medios de pago
				foreach ($pago as $item) {
					$insert = array(
							'venId'			=>	$idVenta,
							'medId'			=>	$item['mId'],
							'rcbImporte'	=>	$item['imp'],
							'rcbDesc1'		=>	$item['de1'],
							'rcbDesc2'		=>	$item['de2'],
							'rcbDesc3'		=>	$item['de3']
						);

					if($this->db->insert('recibos', $insert) == false) {
						return false;
					}
				}

				//Actualizar orden de compra
				$update = array('ocEstado' => 'FA', 'venId' => $idVenta);
				if($this->db->update('ordendecompra', $update, array('ocId'=>$ocId)) == false) {
					return false;
				}
			}
			$this->db->trans_complete();
		}

		return true;
	}
}
?>