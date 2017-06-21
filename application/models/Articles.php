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


			$data = array(
				   'artBarCode'						=> $code,
				   'artDescription' 				=> $name,
				   'artCoste'						=> $price,
				   'artMargin' 						=> $margin,
				   'artMarginIsPorcent' 			=> ($marginP === 'true'),
				   'artEstado' 						=> $status,
				   'artIsByBox'			 			=> ($box === 'true'),
				   'artCantBox'						=> (int)$boxCant,
				   'subrId'						=> $subrId,
				   'ivaId'						=> $ivaId,
				   'artMinimo'						=> $artMinimo,
				   'artMedio'						=> $artMedio,
				   'artMaximo'						=> $artMaximo,
				   'artSeFracciona'					=> ($fraction === 'true')

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

		$this->db->select('*');
		$this->db->from('articles');
		$this->db->where('artEstado','AC');
		if($str != ''){
			$this->db->like('artBarCode',$str);
			$this->db->or_like('artDescription',$str);
		}
		$query = $this->db->get();
		if ($query->num_rows()!=0)
		{
			foreach($query->result_array() as $a){
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
}
?>
