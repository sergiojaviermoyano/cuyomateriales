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

	function getArticles($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$date = $data['day'];
			$date = explode('-', $date);
			$date = $date[2].'-'.$date[1].'-'.$date[0];

			$query = $this->db->query(" select d.artCode, d.artDescription, sum(d.venCant) as ventas from ventas as v
										join ventasdetalle as d on d.venId = v.venId 
										where DATE(venFecha) = '".$date."'  
										GROUP BY d.artCode");

			//echo $this->db->last_query();
			return $query->result_array();
		}
	}

	function getSales__($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$date = $data['day'];
			$date = explode('-', $date);
			$date = $date[2].'-'.$date[1].'-'.$date[0];

			$query = $this->db->query(" select v.venId, v.usrId, v.cliId, (select (sum( d.venCant * d.artFinal) + o.redondeo) from ventasdetalle as d where d.venId = v.venId) as importe , o.ocId, o.ocObservacion, u.usrNick, l.lpDescripcion
										from ventas as v
										join ordendecompra as o on o.venId = v.venId
										join sisusers as u on u.usrId = v.usrId
										join listadeprecios as l on l.lpId = o.lpId 
										where DATE(venFecha) = '".$date."' and v.venEstado = 'AC' and o.ocEsPresupuesto = 0 
										GROUP BY v.venId
										Order by l.lpId ");
			return $query->result_array();
		}
	}

	function getSales__2($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$date = $data['day'];
			$date = explode('-', $date);
			$date = $date[2].'-'.$date[1].'-'.$date[0];

			$query = $this->db->query("
				select 
				sum(od.artPVenta * od.ocdCantidad) as ventas, 
				sum(od.artPCosto * od.ocdCantidad) as costo, 
				sum(oc.redondeo) as redondeo, 
				oc.ocDescuento as descuento, 
				l.lpDescripcion,
				u.usrNick,
				CONCAT(cli.cliApellido, ' ', cli.cliNombre) as cliente,
				oc.ocId
				from ordendecompradetalle as od 
				join ordendecompra as oc on oc.ocId = od.ocId
				join listadeprecios as l on l.lpId = oc.lpId 
				join sisusers as u on u.usrId = oc.usrId
				join clientes as cli on cli.cliId = oc.cliId
				where DATE(oc.ocFecha) = '".$date."' and oc.ocEstado in ('AC','EN') and oc.ocEsPresupuesto = 0 
				GROUP BY oc.ocId
				Order by oc.ocFecha");
			return $query->result_array();
		}
	}

function printBox($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$fecha = $data['date'];
			$fechaDeReporte = $fecha;
			$fecha = explode('-', $fecha); //

			#Resumen de ventas
			$query = $this->db->query('
				select sum(od.artPVenta * od.ocdCantidad) as ventas, sum(od.artPCosto * od.ocdCantidad) as costo, sum(oc.redondeo) as redondeo, (select sum(ocDescuento) from ordendecompra where DATE(ocFecha) = \''.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].'\' and ocEstado in (\'AC\',\'EN\') and ocEsPresupuesto = 0 and lpId=oc.lpId ) as descuento, l.lpDescripcion
				from ordendecompradetalle as od 
				join ordendecompra as oc on oc.ocId = od.ocId
				join listadeprecios as l on l.lpId = oc.lpId 
				where DATE(oc.ocFecha) = \''.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].'\' and oc.ocEstado in (\'AC\',\'EN\') and oc.ocEsPresupuesto = 0 
				GROUP BY l.lpId
				Order by l.lpId

				');
			$data['ventas'] = $query->result_array();

			#Cobranzas de cuenta corriente 
			$query = $this->db->query('
										Select ct.*, cli.cliNombre, cli.cliApellido, u.usrNick 
										from cuentacorrientecliente as ct
										join clientes as cli on cli.cliId = ct.cliId 
										join sisusers as u on u.usrId = ct.usrId 
										where DATE(cctepFecha)  = \''.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].'\' and cctepHaber > 0
									');
			$data['cobranza'] = $query->result_array();

			#Datos del usuario
			$userdata = $this->session->userdata('user_data');
			$data['user'] = $userdata[0];

			#Datos de las ventas
			$data['detail'] = $this->getSales__2(array('day' => $fechaDeReporte));

			#Reporte
			$hoy = getdate();
			$html = '<table width="100%" style="font-family:courier; font-size: 12px;">';
			$html .= '	<tr>
							<td style="font-family: Open Sans; font-size: 25px; text-align: center;"><b>CUYO MATERIALES</b></td>
							<td style="width: 50%; text-align: right">
								<strong>Resumen de Caja</strong> <br>
								Fecha: <b>'.$fechaDeReporte.'</b><br>
								Fecha y Hora de Impresión: <b>'.$hoy['mday'].'-'.$hoy['mon'].'-'.$hoy['year'].' '.$hoy['hours'].':'.$hoy['minutes'].'</b><br>
								Usuario: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
							</td>
						</tr>';
			$html .= '	<tr><td colspan="2"><hr></td></tr>';
			$html .= '	<tr><td colspan="2">';

			$html .= '<table width="100%"><tr><th></th><th>Medio de Pago</th><th>Importe</th><th>Costo</th><th>Redondeo</th><th>Descuento</th></tr>';
			$ventass = 0;
			$costo = 0;
			$redondeo = 0;
			$descuento = 0;
			foreach ($data['ventas'] as $ventas) {
				$html .= '<tr><td></td>';
				$html .= '<td>'.$ventas['lpDescripcion'].'</td>';
				$html .= '<td style="text-align: right">'.number_format($ventas['ventas'], 2, ',', '.').'</td>';$ventass += $ventas['ventas'];
				$html .= '<td style="text-align: right">'.number_format($ventas['costo'], 2, ',', '.').'</td>';$costo += $ventas['costo'];
				$html .= '<td style="text-align: right">'.number_format($ventas['redondeo'], 2, ',', '.').'</td>';$redondeo += $ventas['redondeo'];
				$html .= '<td style="text-align: right">'.number_format($ventas['descuento'], 2, ',', '.').'</td></tr>';$descuento += $ventas['descuento'];
			}
				$html .= '<tr><th colspan="7"><hr></th></tr>';
				$html .= '<tr><th style="text-align: right">Total: </th>';
				$html .= '<td>-</td>';
				$html .= '<th style="text-align: right">'.number_format($ventass, 2, ',', '.').'</th>';
				$html .= '<th style="text-align: right">'.number_format($costo, 2, ',', '.').'</th>';
				$html .= '<th style="text-align: right">'.number_format($redondeo, 2, ',', '.').'</th>';
				$html .= '<th style="text-align: right">'.number_format($descuento, 2, ',', '.').'</th></tr>';

			$html .= '<tr><th colspan="2"><br><br><hr></th><th colspan="2" style="text-align:center"><br><br>Ventas</th><th colspan="2"><br><br><hr></th></tr>';
			$html .= '</table>';

			$html .= '		</td></tr>';
			$html .= '	</table>'; //<td colspan="2">

			$html .= '<table width="100%" style="font-family:courier; font-size: 12px;">';
			$html .= '<tr>
						<th>Orden</th>
						<th>Cliente</th>
						<th>Importe</th>
						<th>Lista de Precio</th>
						<th>Usuario</th>
					</tr>';

        	foreach ($data['detail'] as $item) {
        		$html .=  '<tr>';
                $html .=  '<td>'.$item['ocId'].'</td>';
        		$html .=  '<td>'.$item['cliente'].'</td>';
        		$html .=  '<td style="text-align: right">'.number_format($item['ventas'], 2, ',', '.').'</td>';
                $html .=  '<td style="text-align: right">'.$item['lpDescripcion'].'</td>';
        		$html .=  '<td style="text-align: right">'.$item['usrNick'].'</td>';
        		$html .=  '</tr>';

                //$total += $item['importe'];
        	}
			$html .= '</table>';

			#region cobranzas cuenta corriente 
			if(count($data['cobranza']) > 0){
				$html .= '<table width="100%" style="font-family:courier; font-size: 12px;">';
				$html .= '<tr><th><br><br><hr></th><th colspan="2" style="text-align:center"><br><br>Cobranza de Cuenta Corriente</th> <th><br><br><hr></th></tr>';
				$html .= '<tr>
							<th>Recibo N°</th>
							<th>Concepto</th>
							<th>Importe</th>
							<th>Usuario</th>
						</tr>';	
					
				foreach ($data['cobranza'] as $item) {
					#var_dump($item);
	        		$html .=  '<tr>';
	                $html .=  '<td style="text-align: center">'.str_pad($item['cctepId'], 6, "0", STR_PAD_LEFT).'</td>';
	        		$html .=  '<td>'.$item['cliApellido'].' '.$item['cliNombre'].' '.$item['cliApellido'].'</td>';
	        		$html .=  '<td style="text-align: right">'.number_format($item['cctepHaber'], 2, ',', '.').'</td>';
	        		$html .=  '<td style="text-align: right">'.$item['usrNick'].'</td>';
	        		$html .=  '</tr>';

                //$total += $item['importe'];
        		}
				$html .= '</table>';
			}

			//se incluye la libreria de dompdf
			require_once("assets/plugin/HTMLtoPDF/dompdf/dompdf_config.inc.php");
			//se crea una nueva instancia al DOMPDF
			$dompdf = new DOMPDF();
			//se carga el codigo html
			$dompdf->load_html(utf8_decode($html));
			//aumentamos memoria del servidor si es necesario
			ini_set("memory_limit","300M");
			//Tamaño de la página y orientación
			$dompdf->set_paper('a4','portrait');
			//lanzamos a render
			$dompdf->render();
			//guardamos a PDF
			//$dompdf->stream("TrabajosPedndientes.pdf");
			$output = $dompdf->output();
			file_put_contents('assets/reports/box/caja.pdf', $output);

			//Eliminar archivos viejos ---------------
			/*
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
				if((time()-filemtime('assets/reports/box/'.$f) > 3600*24*1) and !(is_dir('assets/reports/box/'.$f)))
				unlink('assets/reports/box/'.$f);
			}
			closedir($dir);
			*/
			//----------------------------------------

			return true;
		}
	}
	
	
}
?>