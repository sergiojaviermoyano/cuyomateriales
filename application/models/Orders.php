<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Orders extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Orders_List(){
		$this->db->order_by('ocFecha', 'desc');
		$query= $this->db->get('ordendecompra');
		if ($query->num_rows()!=0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function getTotalOrders($data=null){
		$this->db->select('ordendecompra.*');
		$this->db->order_by('ocFecha', 'desc');
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
			$this->db->or_like('ocId', $data['search']['value']);
			$this->db->or_like('Concat(cliApellido, \' \', cliNombre)', $data['search']['value']);
			$this->db->limit($data['length'],$data['start']);
		}
		$this->db->from('ordendecompra');
		$this->db->join('clientes', 'clientes.cliId = ordendecompra.cliId');
		$query= $this->db->get();
		return $query->num_rows();
		
	}

	function Orders_List_datatable($data=null){


		$this->db->select('ordendecompra.*, clientes.cliNombre, clientes.cliApellido');
		$this->db->order_by('ocFecha', 'desc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
			$this->db->or_like('ocId', $data['search']['value']);
			$this->db->or_like('Concat(cliApellido, \' \', cliNombre)', $data['search']['value']);
		}
		
		$this->db->from('ordendecompra');
		$this->db->join('clientes', 'clientes.cliId = ordendecompra.cliId');
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

	function getTotalOrdersSales($data=null){
		$this->db->order_by('ocFecha', 'asc');
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
			$this->db->limit($data['length'],$data['start']);
		}
		$query= $this->db->get_where('ordendecompra', array('ocEstado' => 'AC', 'ocEsPresupuesto' => false));
		return $query->num_rows();
	}

	function Orders_List_datatable_sales($data=null){
		$this->db->order_by('ocFecha', 'asc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
		}
		$query= $this->db->get_where('ordendecompra', array('ocEstado' => 'AC', 'ocEsPresupuesto' => false));
		if ($query->num_rows()!=0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function getTotalOrdersPresu($data=null){
		$this->db->order_by('ocFecha', 'asc');
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
			$this->db->limit($data['length'],$data['start']);
		}
		$query= $this->db->get_where('ordendecompra', array('ocEstado' => 'AC', 'ocEsPresupuesto' => true));
		return $query->num_rows();
	}

	function Orders_List_datatable_presu($data=null){
		$this->db->order_by('ocFecha', 'asc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
		}
		$query= $this->db->get_where('ordendecompra', array('ocEstado' => 'AC', 'ocEsPresupuesto' => true));
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
			$action = $data['act'];
			$idOrder = $data['id'];
			$data = array();
			//Datos del Ordern
			$query= $this->db->get_where('ordendecompra',array('ocId'=>$idOrder));
			if ($query->num_rows() > 0)
			{
				$order = $query->result_array();
				$data['order'] = $order[0];
				$this->db->select("ocd.*, a.artBarCode");
				$this->db->from('ordendecompradetalle ocd');
				$this->db->join('articles a','a.artId=ocd.artId');
				$this->db->where('ocId',$idOrder);
				$query = $this->db->get();
				$detalleCompra=($query->num_rows()>0)?$query->result_array():array();
				$data['detalleCompra']=$detalleCompra;

			} else {
				$Order = array();
				$this->db->select_max('ocId');
 				$query = $this->db->get('ordendecompra');
 				$id = $query->result_array();
				$Order['ocId'] = $id[0]['ocId'] + 1;
				$Order['ocObservacion'] = '';
				$Order['ocFecha'] = '';
				$Order['ocEstado'] = '';
				$Order['ocEsPresupuesto'] = '';
				$Order['usrId'] = '';
				$Order['lpId'] = '';
				$Order['cliId'] = '1';
				$Order['redondeo'] = '0';
				$Order['ocDescuento'] = 0;
				$data['order'] = $Order;
				$data['detalleCompra']=array();
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;
			$data['act'] = $action;

			return $data;
		}
	}

	function setOrder($data = null){
	    if($data == null)
			{
				return false;
			}
			else
			{
				$action = 	$data['act'];
				$id = 		$data['id'];
				$obser = 	$data['obser'];
				$cliId = 	$data['cliId_'];
				$lpId =		$data['lpId'];
				$arts = 	$data['art'];
				$redondeo = 	$data['redondeo'];
				$ocEsPresupuesto = $data['dejadeserpresupuesto'];
				$usr = $data['usrAutDesc'];
				$desc = $data['descuento'] == '' ? null : $data['descuento'];

				//Datos del vendedor
				$userdata = $this->session->userdata('user_data');
				$usrId = $userdata[0]['usrId'];

				$data = array(
					'ocObservacion'	=>$obser,
					'usrId'			=>$usrId,
					'lpId'			=>$lpId,
					'cliId'			=>$cliId,
					'redondeo'  	=>$redondeo,
					'ocDescuento'	=>$desc,
					'cliId'			=>$cliId
					);

				if($usr != '' && $usr != null){
					$data['usrAutDesc'] =$usr;
				}

				switch ($action) {
					case 'Add':
					case 'Pre':
					case 'Ent':
						$this->db->trans_start(); // Query will be rolled back
						if($id <= 0){
							if($action == 'Ent') $data['ocEstado'] = 'EN';
							//Es un registro nuevo
							$data['ocEsPresupuesto']=($action=='Pre')?1:0;
							if($this->db->insert('ordendecompra', $data) == false) {
								return false;
							} else {
								$idOrder = $this->db->insert_id();
								$id = $idOrder;
								foreach ($arts as $a) {
									$insert = array(
											'ocId'	 		=> $idOrder,
											'artId' 		=> $a['artId'],
											'artDescripcion'=> $a['artDescription'],
											'artPCosto'		=> $a['artCoste'],
											'artPVenta'		=> $a['artFinal'],
											'ocdCantidad'	=> $a['venCant'],
											'artPVentaOriginal'=> $a['artOriginal'],
											'artDescuento' 	=> $a['artDescuento']
										);

									if($this->db->insert('ordendecompradetalle', $insert) == false) {
										return false;
									}
								}

								//Entregar
								$this->entregarOC($idOrder);
							}
						} else {
							//Es solo entregar una oc y cambiar estado
							$update = array(
											'ocEstado' 			=> 'EN',
											'ocEsPresupuesto' 	=> 0
											);
							if($this->db->update('ordendecompra', $update, array('ocId'=>$id)) == false) {
					 			return false;
					 		} else {
					 			//Entregar
					 			$this->entregarOC($id);
					 		}
						}
						$this->setCtaCte($id);
						$this->db->trans_complete();
						break;
					case 'Edit':{
						$this->db->trans_start();
						if($ocEsPresupuesto == 'true')
							$data['ocEsPresupuesto'] = 0;
						if($this->db->update('ordendecompra', $data, array('ocId'=>$id)) == false) {
					 		return false;
					 	}
						if($this->db->delete('ordendecompradetalle', array('ocId'=>$id)) == false) {
					 		return false;
					 	}	else {
							$idOrder = $id;

							foreach ($arts as $a) {
								$insert = array(
									'ocId'	 		=> $idOrder,
									'artId' 		=> $a['artId'],
									'artDescripcion'=> $a['artDescription'],
									'artPCosto'		=> $a['artCoste'],
									'artPVenta'		=> $a['artFinal'],
									'ocdCantidad'	=> $a['venCant'],
									'artPVentaOriginal'=> $a['artOriginal'],
									'artDescuento' 	=> $a['artDescuento']
								);

								if($this->db->insert('ordendecompradetalle', $insert) == false) {
									return false;
							 	}
						 	}
							if($ocEsPresupuesto == 'true'){
							 	//Entregar
								$this->entregarOC($id);
							}
						}
					$this->setCtaCte($id);
					$this->db->trans_complete();
					break;
					}
					default:
						# code...
						break;
				}
				return true;
			}
	}

	function printOrder($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$data['act'] = 'Print';
			$result = $this->getOrder($data);

			//Datos del Cliente
			$query= $this->db->get_where('clientes',array('cliId' => $result['order']['cliId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['cliente'] = $user[0];
				}

			//Datos del Vendedor
			$query= $this->db->get_where('sisusers',array('usrId' => $result['order']['usrId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['user'] = $user[0];
				}

			//Lista de Precio
			$query= $this->db->get_where('listadeprecios',array('lpId' => $result['order']['lpId']));
				if ($query->num_rows() != 0)
				{
					$lista = $query->result_array();
					$data['lista'] = $lista[0];
				}

			$ordId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);
			$html = '
					<html>
						<head>
							<style>
								/** Define the margins of your page **/
								@page {
									margin: 260px 25px 60px 25px;
								}

								header {
									position: fixed;
									top: -230px;
									left: 0px;
									right: 0px;
									height: 350px;
									text-align: center;
								}

								footer {
									position: fixed; 
									bottom: -15px; 
									left: 0px; 
									right: 0px;
									height: 90px; 

									/** Extra personal styles **/
									background-color: #FAF2F3;
									color: black;
									text-align: center;
									line-height: 35px;
									font-family: Source Sans Pro ,sans-serif; font-size: 12px;
									box-shadow: 2px 2px 5px #999;
								}

								main {
									
								}
							</style>
						</head>
						<body>
							<header>
								<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">
									<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											<center>Documento no válido como factura</center><br>
											'.($result['order']['ocEsPresupuesto'] ? '<strong>PRESUPUESTO</strong> <br>' : '') .'
											Número de Orden: <b>0000-'.$ordId.'</b><br>
											Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
											Fecha: <b>'.date("d-m-Y H:i", strtotime($result['order']['ocFecha'])).'</b><br><br>
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
									</tr>
									<tr><td colspan="2">
											Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
											Domicilio: <b>'.$data['cliente']['cliDomicilio'].'</b>  tel: <b>'.($data['cliente']['cliTelefono'] == '' ? '-': $data['cliente']['cliTelefono']).'</b><br>
											Lista de Precio: <b>'.$data['lista']['lpDescripcion'].'</b>';
					if($result['order']['ocObservacion'] != ''){
						$html .= '<br><b style="font-size: 20px"><i>'.$result['order']['ocObservacion'].' </i></b>';
					}
			$html .= '					</td>
									</tr>
								</table>
							</header>
					
							<footer>
								<i style="font-size:20px"><b>MUCHAS GRACIAS POR SU COMPRA!!!!</b>
								Envíos al <b>2645419445</b></i><br>
								<b style="font-size:15px">El cliente deberá retirar la mercadería en un plazo no mayor a 10 días.</b>
							</footer>
					';
			$html .= '<main>';
			if(count($result['detalleCompra']) > 17){
				$html .= '<p >';
				$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important; page-break-after: always;">';
			}
			else{
				$html .= '<p style="page-break-after: never;">';
				$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important;">';
			}

				$html .= '	<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
										<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
										<th style="background-color: #D1CECD;">Precio</th>
										<th style="background-color: #D1CECD;">Desc. %</th>
										<th style="background-color: #D1CECD;">Cantidad</th>
										<th style="background-color: #D1CECD;">Total </th>
									</tr>';	
			
			$total = 0;
			for ($i=0; $i<17 ;$i++){
				if(isset($result['detalleCompra'][$i])){
					$art = $result['detalleCompra'][$i];
					$html .= '<tr>';
					$html .= '<td>'.$art['artBarCode'].'</td>';
					$html .= '<td>'.$art['artDescripcion'].'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artDescuento'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
					if($art['artDescuento'] <= 0)
						$coste = $art['artPVenta'] * $art['ocdCantidad'];
					else
						$coste = ($art['artPVenta'] - ($art['artPVenta'] * ($art['artDescuento'] / 100))) * $art['ocdCantidad'];
					$total += $coste;
					$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td colspan="6" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
					$html .= '</tr>';
				}
			}
			
			if(count($result['detalleCompra']) <= 17){
					if($result['order']['ocDescuento'] <= 0){
						$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
					}else{
						$html .= '<tr><td colspan="6" style="text-align: right">Sub Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
						$html .= '<tr><td colspan="6" style="text-align: right">Descuento  <strong style="font-size: 20px">$ '.number_format($result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
						$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total - $result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
					}
			} 
			$html .= '</table>';
			$html .='</p>';

			if(count($result['detalleCompra']) > 17){
			 	$html .= '<p style="page-break-after: never;">';
			 	$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important; page-break-after: never;">';
				$html .= '	<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
								<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
								<th style="background-color: #D1CECD;">Precio</th>
								<th style="background-color: #D1CECD;">Desc. %</th>
								<th style="background-color: #D1CECD;">Cantidad</th>
								<th style="background-color: #D1CECD;">Total </th>
							</tr>';	
				for ($i=17; $i<34 ; $i++){
					if(isset($result['detalleCompra'][$i])){
					$art = $result['detalleCompra'][$i];
					$html .= '<tr>';
					$html .= '<td>'.$art['artBarCode'].'</td>';
					$html .= '<td>'.$art['artDescripcion'].'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artDescuento'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
					if($art['artDescuento'] <= 0)
						$coste = $art['artPVenta'] * $art['ocdCantidad'];
					else
						$coste = ($art['artPVenta'] - ($art['artPVenta'] * ($art['artDescuento'] / 100))) * $art['ocdCantidad'];
					$total += $coste;
					$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td colspan="6" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
					$html .= '</tr>';
					}
				}
				
				if($result['order']['ocDescuento'] <= 0){
					$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
				}else{
					$html .= '<tr><td colspan="6" style="text-align: right">Sub Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
					$html .= '<tr><td colspan="6" style="text-align: right">Descuento  <strong style="font-size: 20px">$ '.number_format($result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
					$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total - $result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
				}
			 	$html .= '</table>';
			 	$html .= '</p>';
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
			file_put_contents('assets/reports/'.$ordId.'.pdf', $output);

			//Eliminar archivos viejos ---------------
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
				if((time()-filemtime('assets/reports/'.$f) > 3600*24*1) and !(is_dir('assets/reports/'.$f)))
				unlink('assets/reports/'.$f);
			}
			closedir($dir);
			//----------------------------------------
			return $ordId.'.pdf';
		}
	}

	function entregarOC($idOc){
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
			//Si falla cambiar a estado 'AC'
			$update = array(
							'ocEstado' 			=> 'AC'
							);
			if($this->db->update('ordendecompra', $update, array('ocId'=>$idOc)) == false) {
	 			return false;
	 		}
		}

		//Datos de la orden
		$query= $this->db->get_where('ordendecompra',array('ocId'=>$idOc));
		$data = array();
		if ($query->num_rows() > 0)
		{
			$order = $query->result_array();
			$data['order'] = $order[0];
			$this->db->select("ocd.*, a.*");
			$this->db->from('ordendecompradetalle ocd');
			$this->db->join('articles a','a.artId=ocd.artId');
			$this->db->where('ocId',$idOc);
			$query = $this->db->get();
			$detalleCompra=($query->num_rows()>0)?$query->result_array():array();
			$data['detalleCompra']=$detalleCompra;

			//---------------
			$venta = array(
				'usrId'			=> $usrId,
				'cajaId'		=> $cajaId,
				'cliId'			=> $data['order']['cliId']
				);

			$this->db->trans_start();
			if($this->db->insert('ventas', $venta) == false) {
				return false;
			} else {
				$idVenta = $this->db->insert_id();
				
				//Actualizar detalle
				$ventaImporte = 0;
				foreach ($data['detalleCompra'] as $a) {
					$insert = array(
							'venId' 		=> $idVenta,
							'artId' 		=> $a['artId'],
							'artCode' 		=> $a['artBarCode'],
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

					$ventaImporte += $a['artPVenta'] * $a['ocdCantidad'];
				}

				//Insertar medio de pago como efectivo
			 	$insert = array(
			 			'venId'			=>	$idVenta,
			 			'medId'			=>	1,
			 			'rcbImporte'	=>	$ventaImporte,
			 			'rcbDesc1'		=>	'',
			 			'rcbDesc2'		=>	'',
			 			'rcbDesc3'		=>	''
			 		);

			 	if($this->db->insert('recibos', $insert) == false) {
			 		return false;
			 	}

				//Actualizar orden de compra
				$update = array('venId' => $idVenta);
				if($this->db->update('ordendecompra', $update, array('ocId'=>$idOc)) == false) {
				 	return false;
				}
			}
			$this->db->trans_complete();
			//---------------
		}
	}

	function setCtaCte($idOc){
		$data = array(
			'act' 	=> 'View',
			'id'	=> $idOc
		);
		$order = $this->getOrder($data);

		#Datos del rubro
		$query= $this->db->get_where('listadeprecios',array('lpId'=>$order['order']['lpId']));
		if ($query->num_rows() != 0)
		{
			$c = $query->result_array();
			$data['lista'] = $c[0];

			if($data['lista']['lpDescripcion'] == 'Cuenta Corriente' || $data['lista']['lpDescripcion'] == 'Cuenta Corriente Esp.'){
				if($order['order']['ocEsPresupuesto'] == 0){
					//Calcular importe 
					$total = 0;
					foreach ($order['detalleCompra'] as $item) {
						$total += $item['artPVenta'] * $item['ocdCantidad'];
					}
					$total = $total + $order['order']['redondeo'] - $order['order']['ocDescuento'];
					$query = $this->db->get_where('cuentacorrientecliente', array('cctepRef' => $idOc, 'cctepTipo' => 'VN'));
					if ($query->num_rows() > 0){
						//Actualizar movimiento porque se edito la orden 
						$update = array(
							'cctepDebe'		=> $total
						);
						if($this->db->update('cuentacorrientecliente', $update, array('cctepRef' => $idOc, 'cctepTipo' => 'VN')) == false) {
					 		return false;
					 	}
					} else {
						//Insertar movimiento porque se inserto la orden
						$insert = array(
							'cctepConcepto' => 'Venta Orden N° '.$idOc,
							'cctepRef'		=> $idOc,
							'cctepTipo'		=> 'VN',
							'cctepDebe'		=> $total,
							'cliId'			=> $order['order']['cliId'],
							'usrId'			=> $order['order']['usrId']
						);

						if($this->db->insert('cuentacorrientecliente', $insert) == false) {
							return false;
						}
					}
				}
			} else {
				return; 
			}
		} else {
			return;
		}
	}

	function printRemito($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			//Orden
			$data['act'] = 'Print';
			$result = $this->getOrder($data);

			if($this->db->update('ordendecompra', array('ocImpresiones' => 1), array('ocId' => $data['id'])) == false) {
				return false;
			}

			//Datos del Cliente
			$query= $this->db->get_where('clientes',array('cliId' => $result['order']['cliId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['cliente'] = $user[0];
				}

			//Datos del Vendedor
			$query= $this->db->get_where('sisusers',array('usrId' => $result['order']['usrId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['user'] = $user[0];
				}

			//Lista de Precio
			$query= $this->db->get_where('listadeprecios',array('lpId' => $result['order']['lpId']));
				if ($query->num_rows() != 0)
				{
					$lista = $query->result_array();
					$data['lista'] = $lista[0];
				}
            
			//----------------------------------------------------

			$ordId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);
			$html = '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; margin-top: -250px;">';
			$html .= '	<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											<center>Documento no válido como factura</center><br>
											<b>REMITO</b><br>
											Número de Orden: <b>0000-'.$ordId.'</b><br>
											Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
											Fecha: <b>'.date("d-m-Y H:i", strtotime($result['order']['ocFecha'])).'</b><br><br>
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
									</tr>';
			$html .= '	<tr><td colspan="2">
							Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
							Domicilio: <b>'.$data['cliente']['cliDomicilio'].'</b>  tel: <b>'.($data['cliente']['cliTelefono'] == '' ? '-': $data['cliente']['cliTelefono']).'</b><br>
							';
						if($result['order']['ocObservacion'] != ''){
							$html .= '<br><br><b style="font-size: 20px"><i>'.$result['order']['ocObservacion'].' </i></b>';
						}
			$html .= '		</td></tr></table>';
			//$html .= '<table width="100%" style="font-family:courier; font-size: 12px;">'
			//$html .= '	<tr><td colspan="2">';

			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important;">';
			$html .= '<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
									<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
									<th style="background-color: #D1CECD;">Cantidad</th>
								</tr>';

			foreach ($result['detalleCompra'] as $art) {
				$html .= '<tr>';
				$html .= '<td>'.$art['artBarCode'].'</td>';
				$html .= '<td>'.$art['artDescripcion'].'</td>';
				$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';				
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="5" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
				$html .= '</tr>';
			}

			$html .= '</table>';

			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">';
			$html .= '<tr>';
			$html .= '<td style="text-align: right"><br><br>Firma:.............................................</td></tr>';
			$html .= '<tr><td  style="text-align: right"><br><br>Aclaración:........................................</td></tr>';
			$html .= '</table>';
			
			//Pagare -------------------------------------------------------------------------------------------------------------
			//$html .= '<div style="page-break-before: always;"></div>';
			//$html .= '<table></tr><td><b>Hola mundo!</b></td></tr></table>';
			
			//Orden -------------------------------------------------------------------------------------------------------------
			$html .= '<style> .page_break { page-break-before: always; } </style>';
			$html .= '<div class="page_break">';


			$ordId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);

			//--------
			$html .= '
					<html>
						<head>
							<style>
								/** Define the margins of your page **/
								@page {
									margin: 260px 25px 60px 25px;
								}

								header {
									position: fixed;
									top: -230px;
									left: 0px;
									right: 0px;
									height: 350px;
									text-align: center;
								}

								footer {
									position: fixed; 
									bottom: -15px; 
									left: 0px; 
									right: 0px;
									height: 90px; 

									/** Extra personal styles **/
									background-color: #FAF2F3;
									color: black;
									text-align: center;
									line-height: 35px;
									font-family: Source Sans Pro ,sans-serif; font-size: 12px;
									box-shadow: 2px 2px 5px #999;
								}

								main {
									
								}
							</style>
						</head>
						<body>
							<header>
								<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">
									<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											<center>Documento no válido como factura</center><br>
											'.($result['order']['ocEsPresupuesto'] ? '<strong>PRESUPUESTO</strong> <br>' : '') .'
											Número de Orden: <b>0000-'.$ordId.'</b><br>
											Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
											Fecha: <b>'.date("d-m-Y H:i", strtotime($result['order']['ocFecha'])).'</b><br><br>
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
									</tr>
									<tr><td colspan="2">
											Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
											Domicilio: <b>'.$data['cliente']['cliDomicilio'].'</b>  tel: <b>'.($data['cliente']['cliTelefono'] == '' ? '-': $data['cliente']['cliTelefono']).'</b><br>
											Lista de Precio: <b>'.$data['lista']['lpDescripcion'].'</b>';
					if($result['order']['ocObservacion'] != ''){
						$html .= '<br><b style="font-size: 20px"><i>'.$result['order']['ocObservacion'].' </i></b>';
					}
			$html .= '					</td>
									</tr>
								</table>
							</header>
					
							<footer>
								<i style="font-size:20px"><b>MUCHAS GRACIAS POR SU COMPRA!!!!</b>
								Envíos al <b>2645419445</b></i><br>
								<b style="font-size:15px">El cliente deberá retirar la mercadería en un plazo no mayor a 10 días.</b>
							</footer>
					';
			$html .= '<main>';
			$html .= '<main>';
			if(count($result['detalleCompra']) > 17){
				$html .= '<p >';
				$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important; page-break-after: always;">';
			}
			else{
				$html .= '<p style="page-break-after: never;">';
				$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important;">';
			}

				$html .= '	<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
										<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
										<th style="background-color: #D1CECD;">Precio</th>
										<th style="background-color: #D1CECD;">Desc. %</th>
										<th style="background-color: #D1CECD;">Cantidad</th>
										<th style="background-color: #D1CECD;">Total </th>
									</tr>';	
			
			$total = 0;
			for ($i=0; $i<17 ;$i++){
				if(isset($result['detalleCompra'][$i])){
					$art = $result['detalleCompra'][$i];
					$html .= '<tr>';
					$html .= '<td>'.$art['artBarCode'].'</td>';
					$html .= '<td>'.$art['artDescripcion'].'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artDescuento'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
					if($art['artDescuento'] <= 0)
						$coste = $art['artPVenta'] * $art['ocdCantidad'];
					else
						$coste = ($art['artPVenta'] - ($art['artPVenta'] * ($art['artDescuento'] / 100))) * $art['ocdCantidad'];
					$total += $coste;
					$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td colspan="6" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
					$html .= '</tr>';
				}
			}
			
			if(count($result['detalleCompra']) <= 17){
				$total_pagare = $total - $result['order']['ocDescuento'];
					if($result['order']['ocDescuento'] <= 0){
						$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
					}else{
						$html .= '<tr><td colspan="6" style="text-align: right">Sub Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
						$html .= '<tr><td colspan="6" style="text-align: right">Descuento  <strong style="font-size: 20px">$ '.number_format($result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
						$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total - $result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
					}
			} 
			$html .= '</table>';
			$html .='</p>';

			if(count($result['detalleCompra']) > 17){
			 	$html .= '<p style="page-break-after: never;">';
			 	$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important; page-break-after: never;">';
				$html .= '	<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
								<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
								<th style="background-color: #D1CECD;">Precio</th>
								<th style="background-color: #D1CECD;">Desc. %</th>
								<th style="background-color: #D1CECD;">Cantidad</th>
								<th style="background-color: #D1CECD;">Total </th>
							</tr>';	
				for ($i=17; $i<34 ; $i++){
					if(isset($result['detalleCompra'][$i])){
					$art = $result['detalleCompra'][$i];
					$html .= '<tr>';
					$html .= '<td>'.$art['artBarCode'].'</td>';
					$html .= '<td>'.$art['artDescripcion'].'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.number_format($art['artDescuento'], 2, ',', '.').'</td>';
					$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
					if($art['artDescuento'] <= 0)
						$coste = $art['artPVenta'] * $art['ocdCantidad'];
					else
						$coste = ($art['artPVenta'] - ($art['artPVenta'] * ($art['artDescuento'] / 100))) * $art['ocdCantidad'];
					$total += $coste;
					$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td colspan="6" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
					$html .= '</tr>';
					}
				}
				
				$total_pagare = $total - $result['order']['ocDescuento'];
				if($result['order']['ocDescuento'] <= 0){
					$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
				}else{
					$html .= '<tr><td colspan="6" style="text-align: right">Sub Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
					$html .= '<tr><td colspan="6" style="text-align: right">Descuento  <strong style="font-size: 20px">$ '.number_format($result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
					$html .= '<tr><td colspan="6" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total - $result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
				}
			 	$html .= '</table>';
			 	$html .= '</p>';

			}
			$html .= '</div>';
			//-------------------------------------------------------------------
			//Pagaré----------------------------------------------
			$this->load->model('CuentaCorrientes');
			if($data['lista']['lpDescripcion'] == 'Cuenta Corriente'){
				$html .= '<style> .page_break { page-break-before: always; } </style>';
				$html .= '<div class="page_break">';
				$fecha_actual = date("d-m-Y");
				$vencimiento = date("d-m-Y",strtotime($fecha_actual."+ 30 days"));
				$numero = date("m",strtotime($vencimiento));
				switch ($numero) {
					case '01': $mesVencimiento = 'Enero';break;
					case '02': $mesVencimiento = 'Febrero';break;
					case '03': $mesVencimiento = 'Marzo';break;
					case '04': $mesVencimiento = 'Abril';break;
					case '05': $mesVencimiento = 'Mayo';break;
					case '06': $mesVencimiento = 'Junio';break;
					case '07': $mesVencimiento = 'Julio';break;
					case '08': $mesVencimiento = 'Agosto';break;
					case '09': $mesVencimiento = 'Septiembre';break;
					case '10': $mesVencimiento = 'Octubre';break;
					case '11': $mesVencimiento = 'Noviembre';break;
					case '12': $mesVencimiento = 'Diciembre';break;
					default: $mesVencimiento = 'Non'; break;
				}
				$numero = date("m",strtotime($fecha_actual));
				switch ($numero) {
					case '01': $mesActual = 'Enero';break;
					case '02': $mesActual = 'Febrero';break;
					case '03': $mesActual = 'Marzo';break;
					case '04': $mesActual = 'Abril';break;
					case '05': $mesActual = 'Mayo';break;
					case '06': $mesActual = 'Junio';break;
					case '07': $mesActual = 'Julio';break;
					case '08': $mesActual = 'Agosto';break;
					case '09': $mesActual = 'Septiembre';break;
					case '10': $mesActual = 'Octubre';break;
					case '11': $mesActual = 'Noviembre';break;
					case '12': $mesActual = 'Diciembre';break;
					default: $mesActual = 'Non'; break;
				}
				
				$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 16px; margin-top: -200px; border: 1px solid #3c3c3c !important; padding: 10px;">';
				$html .= '	<tr>
								<td style="text-align: right ; width:100%;">
									Vence el <b> '.date("d",strtotime($vencimiento)).' </b> de <b> '.$mesVencimiento.' </b> del <b> '.date("Y",strtotime($vencimiento)).' </b><br>
									<strong style="font-size: 25px;"> Por $ '.number_format($total_pagare, 2, ',', '.').'</strong>';
				$html .= '		</td>
							</tr>
							<tr>
								<td style="text-align: right ; width:100%;">
									<br>
									Caucete - San Juan '.date("d",strtotime($fecha_actual)).' de '.$mesActual.' del '.date("Y",strtotime($fecha_actual)).'
								</td>
							</tr>
							<tr>
								<td style="text-align: justify ;">
									<br>
									Pagaré <b> Sin Protesto </b> (Art. 50 - D. Ley 5965/63)
									A el Señor <b> Gallardo Delgado Lucas Ezequiel </b> o a su orden la cantidad 
									de <b> $ '.number_format($total_pagare, 2, ',', '.').' (pesos '.$this->CuentaCorrientes->convertNumber($total_pagare).') </b><br>
									Por igual valor recibido en <b> Materiales de construcción </b> a mi entera 
									satisfacción.
								</td>
							</tr>
							<tr>
								<td style="text-align: left ;">
									<br>
									Pagadero en <b>Caucete - San Juan</b><br>
									Firmante <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
									Calle <b>'.$data['cliente']['cliDomicilio'].'</b>  <b>'.($data['cliente']['cliTelefono'] == '' ? ' - ': 'tel: '.$data['cliente']['cliTelefono']).'</b><br>
									Localidad <b></b><br>
								</td>
							</tr>
						</table>';	

				$html .= '</div>';
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
			file_put_contents('assets/reports/'.$ordId.'.pdf', $output);

			//Eliminar archivos viejos ---------------
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
				if((time()-filemtime('assets/reports/'.$f) > 3600*24*1) and !(is_dir('assets/reports/'.$f)))
				unlink('assets/reports/'.$f);
			}
			closedir($dir);
			//----------------------------------------
			return $ordId.'.pdf';
		}
	}

	function printRemitoSinFecha($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			//Orden
			$data['act'] = 'Print';
			$result = $this->getOrder($data);

			if($this->db->update('ordendecompra', array('ocImpresiones' => 1), array('ocId' => $data['id'])) == false) {
				return false;
			}

			//Datos del Cliente
			$query= $this->db->get_where('clientes',array('cliId' => $result['order']['cliId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['cliente'] = $user[0];
				}

			//Datos del Vendedor
			$query= $this->db->get_where('sisusers',array('usrId' => $result['order']['usrId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['user'] = $user[0];
				}

			//Lista de Precio
			$query= $this->db->get_where('listadeprecios',array('lpId' => $result['order']['lpId']));
				if ($query->num_rows() != 0)
				{
					$lista = $query->result_array();
					$data['lista'] = $lista[0];
				}

			$ordId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);
			$html = '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">';
			$html .= '	<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											<center>Documento no válido como factura</center><br>
											<b>REMITO</b><br>
											Número de Orden: <b>0000-'.$ordId.'</b><br>
											Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
											Fecha: <b>________________________</b><br><br>
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
									</tr>';
			$html .= '	<tr><td colspan="2">
							Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
							Domicilio: <b>'.$data['cliente']['cliDomicilio'].'</b>  tel: <b>'.($data['cliente']['cliTelefono'] == '' ? '-': $data['cliente']['cliTelefono']).'</b><br>
							';
						if($result['order']['ocObservacion'] != ''){
							$html .= '<br><br><b style="font-size: 20px"><i>'.$result['order']['ocObservacion'].' </i></b>';
						}
			$html .= '		</td></tr></table>';
			//$html .= '<table width="100%" style="font-family:courier; font-size: 12px;">'
			//$html .= '	<tr><td colspan="2">';

			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important;">';
			$html .= '<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
									<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
									<th style="background-color: #D1CECD;">Cantidad</th>
								</tr>';

			foreach ($result['detalleCompra'] as $art) {
				$html .= '<tr>';
				$html .= '<td>'.$art['artBarCode'].'</td>';
				$html .= '<td>'.$art['artDescripcion'].'</td>';
				$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';				
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="5" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
				$html .= '</tr>';
			}

			$html .= '</table>';

			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">';
			$html .= '<tr>';
			$html .= '<td style="text-align: right"><br><br>Firma:.............................................</td></tr>';
			$html .= '<tr><td  style="text-align: right"><br><br>Aclaración:........................................</td></tr>';
			$html .= '</table>';
			
			
			//Orden 
			$html .= '<style> .page_break { page-break-before: always; } </style>';
			$html .= '<div class="page_break">';
			//$data['act'] = 'Print';
			/*
			$result = $this->getOrder($data);

			//Datos del Cliente
			$query= $this->db->get_where('clientes',array('cliId' => $result['order']['cliId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['cliente'] = $user[0];
				}

			//Datos del Vendedor
			$query= $this->db->get_where('sisusers',array('usrId' => $result['order']['usrId']));
				if ($query->num_rows() != 0)
				{
					$user = $query->result_array();
					$data['user'] = $user[0];
				}

			//Lista de Precio
			$query= $this->db->get_where('listadeprecios',array('lpId' => $result['order']['lpId']));
				if ($query->num_rows() != 0)
				{
					$lista = $query->result_array();
					$data['lista'] = $lista[0];
				}
			*/
			$ordId = str_pad($data['id'], 10, "0", STR_PAD_LEFT);
			
			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">';
			$html .= '	<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											<center>Documento no válido como factura</center><br>
											'.($result['order']['ocEsPresupuesto'] ? '<strong>PRESUPUESTO</strong> <br>' : '') .'
											Número de Orden: <b>0000-'.$ordId.'</b><br>
											Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
											Fecha: <b>_______________________</b><br><br>
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
									</tr>';
			$html .= '	<tr><td colspan="2">
							Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].' ('.$data['cliente']['cliDocumento'].')</b><br>
							Domicilio: <b>'.$data['cliente']['cliDomicilio'].'</b>  tel: <b>'.($data['cliente']['cliTelefono'] == '' ? '-': $data['cliente']['cliTelefono']).'</b><br>
							Lista de Precio: <b>'.$data['lista']['lpDescripcion'].'</b>';
						if($result['order']['ocObservacion'] != ''){
							$html .= '<br><br><b style="font-size: 20px"><i>'.$result['order']['ocObservacion'].' </i></b>';
						}
			$html .= '		</td></tr></table>';
			//$html .= '<table width="100%" style="font-family:courier; font-size: 12px;">'
			//$html .= '	<tr><td colspan="2">';

			$html .= '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px; border-top: 2px solid #3c3c3c !important;">';
			$html .= '<tr style="background-color: #FAFAFA; border-bottom: 2px solid #3c3c3c !important;">
									<th colspan="2" style="background-color: #D1CECD;">Artículo</th>
									<th style="background-color: #D1CECD;">Precio</th>
									<th style="background-color: #D1CECD;">Cantidad</th>
									<th style="background-color: #D1CECD;">Total</th>
								</tr>';
			$total = 0;

			foreach ($result['detalleCompra'] as $art) {
				$html .= '<tr>';
				$html .= '<td>'.$art['artBarCode'].'</td>';
				$html .= '<td>'.$art['artDescripcion'].'</td>';
				$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
				$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
				$coste = $art['artPVenta'] * $art['ocdCantidad'];
				$total += $coste;
				$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="5" style="padding-top: 5px"><hr style="border: 1px solid #D8D8D8;"> </td>';
				$html .= '</tr>';
			}
			//$total += $result['order']['redondeo'];
			//$html .= '<tr><td><h5>Redondeo</h5></td>';
			//$html .= '<td colspan="3" style="text-align: right"><h5>'.($result['order']['redondeo'] >= 0 ? '+' : '').''.number_format($result['order']['redondeo'], 2, ',', '.').'</h5></td></tr>';
			if($result['order']['ocDescuento'] <= 0){
				$html .= '<tr><td colspan="5" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
			}else{
				$html .= '<tr><td colspan="5" style="text-align: right">Sub Total  <strong style="font-size: 20px">$ '.number_format($total, 2, ',', '.').'</strong></td></tr>';
				$html .= '<tr><td colspan="5" style="text-align: right">Descuento  <strong style="font-size: 20px">$ '.number_format($result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
				$html .= '<tr><td colspan="5" style="text-align: right">Total  <strong style="font-size: 20px">$ '.number_format($total - $result['order']['ocDescuento'], 2, ',', '.').'</strong></td></tr>';
			}
			$html .= '</table>';
			$html .= '</div>';

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
			file_put_contents('assets/reports/'.$ordId.'.pdf', $output);

			//Eliminar archivos viejos ---------------
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
				if((time()-filemtime('assets/reports/'.$f) > 3600*24*1) and !(is_dir('assets/reports/'.$f)))
				unlink('assets/reports/'.$f);
			}
			closedir($dir);
			//----------------------------------------
			return $ordId.'.pdf';
		}
	}
}
?>
