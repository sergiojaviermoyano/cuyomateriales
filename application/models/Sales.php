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

					$this->db->select('*');
					$this->db->from('ordendecompra');
					$this->db->where(array('ocEstado' => 'AC'));
					$this->db->order_by('ocFecha', 'asc');
					$query = $this->db->get();
					$response['ordenes'] = $query->result_array();

					break;
				
				case '2':
					$this->db->select('*');
					$this->db->from('ventas');
					$this->db->where(array('cajaId' => $response['cajaId']));
					$this->db->order_by('venFecha', 'desc');
					$query = $this->db->get();
					$response['ventas'] = $query->result_array();
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

				/*
				mId:      $('#'+tmpId+'_medId').val(),
		        imp:      $('#'+tmpId+'_importe').val(),
		        tmp:      tmpId,
		        de1:      $('#'+tmpId+'_des1').val(),
		        de2:      $('#'+tmpId+'_des2').val(),
		        de3:      $('#'+tmpId+'_des3').val(),
		        */

				//Actualizar orden de compra
				$update = array('ocEstado' => 'FA', 'venId' => $idVenta);
				if($this->db->update('ordendecompra', $update, array('ocId'=>$ocId)) == false) {
					return false;
				}
			}
		}

		return true;
	}
	/*
	function Sale_List(){
		$data = array();
		//verificar si hay cajas abiertas
		$this->db->where('cajaCierre', null);
		$this->db->from('cajas');
		$data['openBox'] = 0;
		if($this->db->count_all_results() > 0){
			$data['openBox'] = 1;
			$this->db->select_max('cajaId');
			$query = $this->db->get('cajas');
			$result = $query->result_array();

			$cajaId = $result[0]['cajaId'];

			$this->db->select('ventas.*, sisusers.usrName, sisusers.usrLastName, sum(ventasdetalle.artFinal * ventasdetalle.venCant ) as ven');
			$this->db->from('ventas');
			$this->db->join('sisusers', ' sisusers.usrId = ventas.usrId');
			$this->db->join('ventasdetalle', ' ventasdetalle.venId = ventas.venId');
			$this->db->order_by('ventas.venId', 'desc');
			$this->db->group_by('ventas.venId');
			$this->db->where('ventas.cajaId', $cajaId);
			$query = $this->db->get();
			$data['data'] = $query->result_array();	
		}else{
			$data['data'] = array();
		}

		return $data;
	}
	
	function getSale($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			//$action = $data['act'];
			$id = $data['id'];

			$data = array();

			$this->db->select('ventas.*, sisusers.usrName, sisusers.usrLastName');
			$this->db->from('ventas');
			$this->db->join('sisusers', 'sisusers.usrId = ventas.usrId');
			$this->db->where(array('ventas.venId'=>$id));
			$query= $this->db->get();
			if ($query->num_rows() != 0)
			{
				$m = $query->result_array();
				$data['sale'] = $m[0];
			} else {
				$data['sale'] = array();
			}

			$this->db->select('*');
			$this->db->from('ventasdetalle');
			$this->db->where(array('ventasdetalle.venId'=>$id));
			$query= $this->db->get();
			if ($query->num_rows() != 0)
			{
				$data['detail'] = $query->result_array();
			} else {
				$data['detail'] = array();
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
			$userdata = $this->session->userdata('user_data');
			$usrId = $userdata[0]['usrId'];
			$this->db->where('cajaCierre', null);
			$this->db->from('cajas');
			$cajaId = 0;
			if($this->db->count_all_results() > 0){
				$this->db->select_max('cajaId');
				$query = $this->db->get('cajas');
				$result = $query->result_array();

				$cajaId = $result[0]['cajaId'];
			} else {
				return false;
			}

			$arts = $data['articles'];
			
			$data = array(
					   'venFecha'		 	=> date('Y-m-d H:i:s'),
					   'venEstado'			=> 'AC',
					   'usrId'				=> $usrId,
					   'cajaId'				=> $cajaId
					);

			if($this->db->insert('ventas', $data) == false) {
				return false;
			} else {
				$idVenta = $this->db->insert_id();

				foreach ($arts as $a) {
					$insert = array(
							'venId' 		=> $idVenta,
							'artId' 		=> $a['artId'],
							'artCode' 		=> $a['artCode'],
							'artDescription'=> $a['artDescription'],
							'artCoste'		=> $a['artCoste'],
							'artFinal'		=> $a['artFinal'],
							'venCant'		=> $a['venCant']
						);

					if($this->db->insert('ventasdetalle', $insert) == false) {
						return false;
					}

					$insert = array(
							'artId' 		=> $a['artId'],
							'stkCant'		=> $a['venCant'] * -1,
							'stkOrigen'		=> 'VN'
						);

					if($this->db->insert('stock', $insert) == false) {
						return false;
					}
				}
	
			}

			return true;

		}
	}

	function delSale($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$venId = $data['id'];

			//Buscar los articulos de la venta
			$this->db->select('*');
			$this->db->from('ventasdetalle');
			$this->db->where(array('ventasdetalle.venId'=>$venId));
			$query= $this->db->get();
			if ($query->num_rows() != 0)
			{
				$detail = $query->result_array();
			} else {
				$detail = array();
			}

			//Cancelar el movimiento de stock 
			foreach ($detail as $a) {
					$insert = array(
									'artId' 		=> $a['artId'],
									'stkCant'		=> $a['venCant'],
									'stkOrigen'		=> 'CV'
								);

					if($this->db->insert('stock', $insert) == false) {
						return false;
					}
			}
			
			//Anular Factura
			$data = array(
			   'venEstado'		=> 'AN'
			);
			if($this->db->update('ventas', $data, array('venId'=>$venId)) == false) {
				return false;
			}
		}

		return true;
	}

	function printSale($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$result = $this->getSale($data);
			

			$venId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);

			$html  = '<label>Nro: </label><b>'.$venId.'</b><br>';
			$date = new DateTime($result['sale']['venFecha']);
			$html .= '<label>Fecha: </label><b>'.$date->format('d-m-Y H:i:s').'</b><br>';
			$html .= '<label>Caja: </label><b>'.str_pad($result['sale']['cajaId'], 10, "0", STR_PAD_LEFT).'</b><br>';
			$html .= '<label>Vendedor: </label><b>'.$result['sale']['usrName'].', '.$result['sale']['usrLastName'].'</b><br>';
			$html .= '<hr>';
			$html .= '<table width="100%">';
			$html .= '<tr><th>Art.</th><th>Precio</th><th>Cant.</th><th>Tot.</th></tr>';
			$total = 0;
			foreach ($result['detail'] as $art) {
				$html .= '<tr>';
				$html .= '<td>'.$art['artCode'].'</td>';
				$html .= '<td style="text-align: right">'.number_format($art['artFinal'], 2, ',', '.').'</td>';
				$html .= '<td style="text-align: right">'.$art['venCant'].'</td>';
				$coste = $art['artFinal'] * $art['venCant'];
				$total += $coste;
				$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="4">'.$art['artDescription'].'</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="4" style="padding-top: 5px"> </td>';
				$html .= '</tr>';
			}
			$html .= '<tr><td><h5>Total</h5></td>';
			$html .= '<td colspan="3" style="text-align: right"><h4>'.number_format($total, 2, ',', '.').'</h4></td></tr>';
			$html .= '</table>';
			$html .= '<hr>';

			//se incluye la libreria de dompdf
			require_once("assets/plugin/HTMLtoPDF/dompdf/dompdf_config.inc.php");
			//se crea una nueva instancia al DOMPDF
			$dompdf = new DOMPDF();
			//se carga el codigo html
			$dompdf->load_html(utf8_decode($html));
			//aumentamos memoria del servidor si es necesario
			ini_set("memory_limit","300M"); 
			//Tamaño de la página y orientación 
			$dompdf->set_paper('a6', 'landscape');
			//lanzamos a render
			$dompdf->render();
			//guardamos a PDF
			//$dompdf->stream("TrabajosPedndientes.pdf");
			$output = $dompdf->output();
			file_put_contents('assets/reports/'.$venId.'.pdf', $output);

			//Eliminar archivos viejos ---------------
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
			 
			if((time()-filemtime('assets/reports/'.$f) > 3600*24*1) and !(is_dir('assets/reports/'.$f)))
			unlink('assets/reports/'.$f);
			}
			closedir($dir);
			//----------------------------------------
			return $venId.'.pdf';
		}
	}
	*/
}
?>