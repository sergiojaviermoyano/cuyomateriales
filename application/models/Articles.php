<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Articles extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Articles_List(){

		$query= $this->db->get('articles');

		if ($query->num_rows()!=0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}

	function getTotalArticles($data){
		$this->db->order_by('artDescription', 'desc');
		if($data['search']['value']!=''){
			$this->db->like('artDescription', $data['search']['value']);
			$this->db->limit($data['length'],$data['start']);
		}
		$query= $this->db->get('articles');
		return $query->num_rows();
	}
	function Articles_List_datatable($data){
		$this->db->order_by('artDescription', 'desc');
		$this->db->limit($data['length'],$data['start']);
		if($data['search']['value']!=''){
			$this->db->like('artDescription', $data['search']['value']);
			$this->db->or_like('artBarCode', $data['search']['value']);
		}
		$query= $this->db->get('articles');

		if ($query->num_rows()!=0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function getArticle($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idArt = $data['id'];

			$data = array();

			//Datos del articulo
			$query= $this->db->get_where('articles',array('artId'=>$idArt));
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$data['article'] = $c[0];

			} else {
				$art = array();

				$art['artId'] = '';
				$art['artBarCode'] = '';
				$art['artDescription'] = '';
				$art['artCoste'] = '';
				$art['artMargin'] = '';
				$art['artMarginIsPorcent'] = '';
				$art['artIsByBox'] = '';
				$art['artCantBox'] = '';
				$art['artEstado'] = 'AC';
				$art['subrId']= '';
				$art['ivaId']= '';
				$art['artMinimo']= '';
				$art['artMedio']= '';
				$art['artMaximo']= '';
				$art['artSeFracciona']= '';
				$art['artModificaPrecio'] = '';

				$data['article'] = $art;
			}
			$data['article']['action'] = $action;

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;
			$data['action'] = $action;

			return $data;
		}
	}

	function setArticle($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id 	= $data['id'];
            $act 	= $data['act'];
            $name 	= $data['name'];
            $price 	= $data['price'];
            $margin = $data['marg'];
            $marginP = $data['margP'];
            $status = $data['status'];
            $box 	= $data['box'];
            $boxCant = $data['boxCant'];
            $code = $data['code'];
            $subrId 	=	$data['subrId'];
            $ivaId 	=	$data['ivaId'];
            $artMinimo 	=	$data['artMinimo'];
            $artMedio 	=	$data['artMedio'];
            $artMaximo 	=	$data['artMaximo'];
			$fraction 	= 	$data['fraction'];
			$modifica   = 	$data['modifica'];


			$data = array(
				   'artBarCode'						=> $code,
				   'artDescription' 				=> $name,
				   'artCoste'						=> $price,
				   'artMargin' 						=> $margin,
				   'artMarginIsPorcent' 			=> ($marginP === 'true'),
				   'artEstado' 						=> $status,
				   'artIsByBox'			 			=> ($box === 'true'),
				   'artCantBox'						=> (int)$boxCant,
				   'subrId'							=> $subrId,
				   'ivaId'							=> $ivaId,
				   'artMinimo'						=> $artMinimo,
				   'artMedio'						=> $artMedio,
				   'artMaximo'						=> $artMaximo,
				   'artSeFracciona'					=> ($fraction === 'true'),
				   'artModificaPrecio'				=> ($modifica === 'true')

				);

			switch($act){
				case 'Add':
					//Agregar Artículo
				/*
					$this->db->where('artBarCode',$code);
					$this->db->or_where('artDescription',$name);
					$check_article=$this->db->get('articles');

					if($check_article->num_rows()>0){
						return json_encode(array('result'=>'error','message'=>'El Código o la Descripcíon esta duplicado, ingrese otro valor'));
					}
					*/

					if($this->db->insert('articles', $data) == false) {
						//return json_encode(array('result'=>'error','message'=>''));
						return false;
					}
					break;

				 case 'Edit':
				 	//Actualizar Artículo
				 	if($this->db->update('articles', $data, array('artId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;

				 case 'Del':
				 	//Eliminar Artículo
				 	if($this->db->delete('articles', array('artId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;

			}
			return true;

		}
	}

	function searchByCode($data = null){
		$str = '';
		if($data != null){
			$str = $data['code'];
		}

		$articles = array();

		$this->db->select('*');
		$this->db->from('articles');
		$this->db->where(array('artBarCode'=>$str, 'artEstado'=>'AC'));
		$this->db->or_where(array('artDescription' => $str));
		$query = $this->db->get();
		if ($query->num_rows()!=0)
		{
			if($query->num_rows() > 1){
				//Multiples coincidencias
			} else {
				//Unica coincidencia
				$a = $query->result_array();
				$articles = $a[0];

				//Calcular precios
				$pUnit = $articles['artCoste'];
				if($articles['artIsByBox'] == 1){
					$pUnit = $articles['artCoste'] / $articles['artCantBox'];
					$articles['artCoste'] = $pUnit;
				}

				if($articles['artMarginIsPorcent'] == 1){
					$articles['pVenta'] = $pUnit + ($pUnit * ($articles['artMargin'] / 100));
				} else {
					$articles['pVenta'] = $pUnit + $articles['artMargin'];
				}

			}
			return $articles;
		}

		return $articles;
	}

	function searchByAll($data = null){
		$str = '';
		if($data != null){
			$str = $data['code'];
		}

		$art = array();

		$this->db->select('*, (select sum(stkCant) from stock where stock.artId = articles.artId) as stock');
		$this->db->from('articles');
		$this->db->where('artEstado','AC');
		if($str != ''){
			$this->db->like('artBarCode',$str);
			$this->db->or_like('artDescription',$str);
		}
		$query = $this->db->get();
		if ($query->num_rows()!=0)
		{
			$proccess = $query->result_array();
			foreach($proccess as $a){
				$articles = $a;

				//Calcular precios
				$pUnit = $articles['artCoste'];
				if($articles['artIsByBox'] == 1){
					$pUnit = $articles['artCoste'] / $articles['artCantBox'];
				}

				if($articles['artMarginIsPorcent'] == 1){
					$articles['pVenta'] = $pUnit + ($pUnit * ($articles['artMargin'] / 100));
				} else {
					$articles['pVenta'] = $pUnit + $articles['artMargin'];
				}

				//$articles['stock'] = $this->stock($articles['artId']);

				$art[] = $articles;
			}
		}

		return $art;
	}

	public function update_prices_by_rubro($data){


		if(isset($data['artMarginIsPorcent'])){
			$this->db->set('artCoste','artCoste + ( (artCoste*'.$data['incrementValue'].') /100)',FALSE);
		}else{
			$this->db->set('artCoste','artCoste +'.$data['incrementValue'].'',FALSE);
		}


		if($data['subrId']==''){
			$this->db->select('subrId')->where('rubId',$data['rubId'])->from('subrubros');
			$subQuery =  $this->db->get_compiled_select();
			$this->db->where("subrId IN (".$subQuery.")", NULL, FALSE);
		}else{
			$this->db->where('subrId',$data['subrId']);
		}

		if($this->db->update("articles")){
			return true;
		}else{
			return false;
		}
	}

	public function stock($artId)
    {
        $query = $this->db->query('CALL stockArt('.$artId.')');
        return $query->result();
	}
	
	public function exportar($data){
		$margin = $data['marg'];
		$this->db->from('rubros');
		$this->db->order_by('rubDescripcion', 'asc');
		$query = $this->db->get(); 
		
		if ($query->num_rows()!=0)
		{
			$data = $query->result_array();
			$rubros = array();
			foreach($data as $rub){
				$this->db->select('*')->where('rubId',$rub['rubId'])->from('subrubros')->order_by('subrDescripcion', 'asc');
				$query = $this->db->get(); 
				$aux = array();
				$aux['rubro'] = $rub;
				$aux['subrubros'] = array();
				$subr = $query->result_array();
				$subrubros = array();
				foreach($subr as $sub){
					$this->db->select('*')->where(array('subrId'=>$sub['subrId'], 'artEstado'=>'AC'))->from('articles')->order_by('artDescription', 'asc');
					$query = $this->db->get(); 
					$aux2 = array();
					$aux2['subrubro'] = $sub;
					$aux2['articles'] = $query->result_array();
					$aux['subrubros'][] = $aux2;
				}

				$rubros[] = $aux;
			}

			$html = '<table width="100%" style="font-family: Source Sans Pro ,sans-serif; font-size: 12px;">';
			$html .= '	<tr>
										<td style="text-align: center; width: 50%; border-bottom: 2px solid #3c3c3c !important;">
										<img <img src="./assets/images/logoEmpresa.png" width="200px"><br>
											25 De Mayo 595 - Caucete - San Juan<br>
											IVA Responsable Inscripto<br>
											Tel: 0264 - 4961482
										</td>
										<td style="border-bottom: 2px solid #3c3c3c !important; border-left: 2px solid #3c3c3c !important; padding-left: 10px;">
											CUIT: <b>20349167736</b><br>
											Ingresos Brutos: <b>000-118245-5</b><br>
											Inicio Actividades: <b>14/06/2011</b>
										</td>
						</tr>';
			$html .= '</table>';

			foreach($rubros as $r){
				$escribirRubro = false;
				$yaEscribioRubro = false;
				$escribirSubrubro = false;
				$escribirArticle = false;

				if(count($r['subrubros']) > 0){
					$escribirRubro = true;
					$htmlRubro = '<br><b>'.$r['rubro']['rubDescripcion'].'</b><br>';				

					foreach ($r['subrubros'] as $sr) {
						if(count($sr['articles']) > 0){
							$escribirSubrubro = true;
						}

						if($escribirSubrubro == true && $yaEscribioRubro == false){
							$html .= $htmlRubro;
							$yaEscribioRubro = true;
						}

						if(count($sr['articles']) > 0){
							$html .= '=== '.$sr['subrubro']['subrDescripcion'].'=== <br>';
							//$html .= '------->'.count($sr['articles']).'<br>';
						}

						 foreach ($sr['articles'] as $ar) {
						 	//$html .= '<tr>';
						 	//$html .= '<td>'.$ar['artBarCode'].'</td>';
							//$html .= '<td>'.$ar['artDescription'].'</td>';
							$pUnit = $ar['artCoste'];
							if($ar['artIsByBox'] == 1){
								$pUnit = $ar['artCoste'] / $ar['artCantBox'];
							}

							if($ar['artMarginIsPorcent'] == 1){
								$pUnit = $pUnit + ($pUnit * ($ar['artMargin'] / 100));
							} else {
								$pUnit = $pUnit + $ar['artMargin'];
							}
							$pUnit = $pUnit + ($pUnit * ($margin / 100));
							$html .= str_pad($ar['artDescription'], 50,'.').'    $'.number_format ( $pUnit , 2 , '.', ',' );				
						 	//$html .= '</tr>';
							$html .= '<br>';
						 }
						 //$html .= '</table>';

						$escribirSubrubro = false;
					}
				}
				

			}

			//se incluye la libreria de dompdf
			require_once("assets/plugin/HTMLtoPDF/dompdf/dompdf_config.inc.php");
			//se crea una nueva instancia al DOMPDF
			$dompdf = new DOMPDF();
			//se carga el codigo html
			$dompdf->load_html(utf8_decode($html));
			//aumentamos memoria del servidor si es necesario
			ini_set("memory_limit","3000M");
			//Tamaño de la página y orientación
			$dompdf->set_paper('a4','portrait');
			//lanzamos a render
			$dompdf->render();
			//guardamos a PDF
			//$dompdf->stream("TrabajosPedndientes.pdf");
			$output = $dompdf->output();
			file_put_contents('assets/reports/listado.pdf', $output);

			//Eliminar archivos viejos ---------------
			$dir = opendir('assets/reports/');
			while($f = readdir($dir))
			{
				if((time()-filemtime('assets/reports/'.$f) > 3600*24*1) and !(is_dir('assets/reports/'.$f)))
				unlink('assets/reports/'.$f);
			}
			closedir($dir);
			//----------------------------------------

			return $rubros;
			
		}
		else
		{
			return false;
		}
	}
}
?>
