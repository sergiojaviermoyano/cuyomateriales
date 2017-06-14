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
		//$this->db->order_by('ocFecha', 'desc');
		$this->db->order_by('ocFecha', 'desc');

		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
			$this->db->limit($data['length'],$data['start']);
		}
		$query= $this->db->get('ordendecompra');
		return $query->num_rows();
	}

	function Orders_List_datatable($data=null){


		$this->db->order_by('ocFecha', 'desc');
		//$this->db->limit($data['length'],$data['start']);
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('ocObservacion', $data['search']['value']);
		}
		$query= $this->db->get('ordendecompra');
		//echo ($this->db->last_query()."<br>");

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
				$Order['cliId'] = '';
				$Order['redondeo'] = '0';
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
		/*
		id : idOrder,
        act: acOrder,
        obser:  $('#ocObservacion').val(),
        cliId:  $('#cliId').val(),
        lpId:   $('#lpId').val(),
        art:    sale
        */

    if($data == null)
		{
			return false;
		}
		else
		{
			$action = 	$data['act'];
			$id = 		$data['id'];
			$obser = 	$data['obser'];
			$cliId = 	$data['cliId'];
			$lpId =		$data['lpId'];
			$arts = 	$data['art'];
			$redondeo = 	$data['redondeo'];


			//Datos del vendedor
			$userdata = $this->session->userdata('user_data');
			$usrId = $userdata[0]['usrId'];

			$data = array(
				'ocObservacion'	=>$obser,
				'usrId'			=>$usrId,
				'lpId'			=>$lpId,
				'cliId'			=>$cliId,
				'redondeo'  =>$redondeo
				);

			switch ($action) {
				case 'Add':
				case 'Pre':

					$this->db->trans_start(); // Query will be rolled back
					$data['ocEsPresupuesto']=($action=='Pre')?1:0;

					if($this->db->insert('ordendecompra', $data) == false) {
						return false;
					} else {
						$idOrder = $this->db->insert_id();

						foreach ($arts as $a) {
							$insert = array(
									'ocId'	 		=> $idOrder,
									'artId' 		=> $a['artId'],
									'artDescripcion'=> $a['artDescription'],
									'artPCosto'		=> $a['artCoste'],
									'artPVenta'		=> $a['artFinal'],
									'ocdCantidad'	=> $a['venCant']
								);

							if($this->db->insert('ordendecompradetalle', $insert) == false) {
								return false;
							}
						}
					}
					$this->db->trans_complete();
					break;
				case 'Edit':{
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
								//'artCode' 		=> '',
								'artDescripcion'=> $a['artDescription'],
								'artPCosto'		=> $a['artCoste'],
								'artPVenta'		=> $a['artFinal'],
								'ocdCantidad'	=> $a['venCant']
							);

							if($this->db->insert('ordendecompradetalle', $insert) == false) {
							 return false;
						 }
					 }
				 }

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
			$html = '<table width="100%">';
			$html .= '	<tr>
							<th width="50%">
								<h1 style="color: #FE642E">CUYO</h1>
								<h3 style="color: #FE642E">MATERIALES PARA LA CONSTRUCCIÓN</h3>
								Calle 25 de Mayo 595 - Caucete - San Juan<br>
								Tel: 154670159<br>
								E-mail: luisgallardo@hotmail.com
							</th>
							<th style="vertical-align: top">
								Documento no válido como factura.<br>
								'.($result['order']['ocEsPresupuesto'] ? '<h3>Presupuesto</h3> <br>' : '') .'
								<br>Número de Orden: <b>0000-'.$ordId.'</b><br>
								Vendedor: <b>'.$data['user']['usrName'].' '.$data['user']['usrLastName'].'</b><br>
								Fecha: <b>'.date("d-m-Y H:i", strtotime($result['order']['ocFecha'])).'</b><br>
							</th>
						</tr>';
			$html .= '	<tr><td colspan="2"><hr></td></tr>';
			$html .= '	<tr><td colspan="2">
							Cliente: <b>'.$data['cliente']['cliApellido'].' '.$data['cliente']['cliNombre'].'</b><br>
							Lista de Precio: <b>'.$data['lista']['lpDescripcion'].'</b>
						</td></tr>';
			$html .= '	<tr><td colspan="2"><hr></td></tr>';
			$html .= '	<tr><td colspan="2">';

			$html .= '<table width="100%">';
			$html .= '<tr>
						<th>Artículo</th>
						<th>Precio</th>
						<th>Cantidad</th>
						<th>Total</th>
					</tr>';
			$total = 0;

			foreach ($result['detalleCompra'] as $art) {
				$html .= '<tr>';
				$html .= '<td>('.$art['artId'].')'.$art['artDescripcion'].'</td>';
				$html .= '<td style="text-align: right">'.number_format($art['artPVenta'], 2, ',', '.').'</td>';
				$html .= '<td style="text-align: right">'.$art['ocdCantidad'].'</td>';
				$coste = $art['artPVenta'] * $art['ocdCantidad'];
				$total += $coste;
				$html .= '<td style="text-align: right">'.number_format($coste, 2, ',', '.').'</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="4" style="padding-top: 5px"><hr> </td>';
				$html .= '</tr>';
			}
			$html .= '<tr><td><h5>Total</h5></td>';
			$html .= '<td colspan="3" style="text-align: right"><h3>'.number_format($total, 2, ',', '.').'</h3></td></tr>';
			$html .= '</table>';


			$html .= '	</td></tr>';
			$html .= '</table>';
			/*
			$html  = '<label>Nro: </label><b>'.$ordId.'</b><br>';
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
			*/

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
	/*
	function setProvider($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
            $act = $data['act'];
            $id = $data['id'];
            $act = $data['act'];
            $nom = $data['nom'];
            $ape = $data['ape'];
            $rz = $data['rz'];
            $tp = $data['tp'];
            $doc = $data['doc'];
            $dom = $data['dom'];
            $mai = $data['mai'];
            $est = $data['est'];
            $tel = $data['tel'];

			$data = array(
				   'prvNombre' 			=> $nom,
				   'prvApellido' 		=> $ape,
				   'prvRazonSocial' 	=> $rz,
				   'docId' 				=> $tp,
				   'prvDocumento' 		=> $doc,
				   'prvDomicilio'		=> $dom,
				   'prvMail'			=> $mai,
				   'prvEstado'			=> $est,
				   'prvTelefono'		=> $tel
				);

			switch($act){
				case 'Add':
					//Agregar Proveedor
					if($this->db->insert('proveedores', $data) == false) {
						return false;
					}
					break;

				case 'Edit':
				 	//Actualizar Proveedor
				 	if($this->db->update('proveedores', $data, array('prvId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;

				case 'Del':
				 	//Eliminar Proveedor
				 	if($this->db->delete('proveedores', array('prvId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
			}
			return true;

		}
	}
	*/

}
?>
