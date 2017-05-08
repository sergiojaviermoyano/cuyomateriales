<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Reports extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getIn($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$from 	= $data['from'];
			$to 	= $data['to'];			

			$from 	= explode('-', $from);
			$from 	= $from[2].'-'.$from[1].'-'.$from[0];

			$to 	= explode('-', $to);
			$to 	= $to[2].'-'.$to[1].'-'.$to[0];
			$to 	= date('Y-m-d', strtotime($to.'+1 day'));

			$this->db->select_sum('crdHaber');
			$this->db->where('crdDate >=', $from);
			$this->db->where('crdDate <=', $to); 
			$query = $this->db->get('admcredits');

			$total = 0;
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$total = $c[0];
			} 

			$html = '<html>
					<head>
					    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
					    <html lang="sv">
					</head>
					<body>';
			$this->db->select('admcredits.*, admcustomers.cliName, admcustomers.cliLastName');
			$this->db->from('admcredits');
			$this->db->join('admcustomers', ' admcustomers.cliId = admcredits.cliId');
			$this->db->where('crdDate >=', $from);
			$this->db->where('crdDate <=', $to); 
			$this->db->where('crdHaber >', 0); 
			$query = $this->db->get();

			if ($query->num_rows() != 0){
					$html .= '<table style="border: 1px solid; width: 100%;">';
					$html .= '<tr style="text-align: center;"><th>Número</th><th>Descripción</th><th>Fecha</th><th>Importe</th><th>Cliente</th></tr>';
					$html .= '<tr><td colspan="5"><hr></td></tr>';
					foreach ($query->result() as $i) {
						$html .= '<tr>';
						$html .= '<td style="width: 100px">'.str_pad($i->crdId, 10, "0", STR_PAD_LEFT).'</td>';
						$html .= '<td>'.$i->crdDescription.'</td>';
						$html .= '<td  style="width: 150px">'.$i->crdDate.'</td>';
						$html .= '<td style="text-align: right; width: 100px;">'.$i->crdHaber.'</td>';
						$html .= '<td>'.$i->cliLastName.', '.$i->cliName.'</td>';
						$html .= '</tr>';
						$html .= '<tr><td colspan="5"><hr></td></tr>';
					}
					$html .= '</table></body></html>';
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
			$dompdf->set_paper('a4', 'landscape');
			//lanzamos a render
			$dompdf->render();
			//guardamos a PDF
			//$dompdf->stream("TrabajosPedndientes.pdf");
			$output = $dompdf->output();
			file_put_contents('assets/reports/reporteDeIngresos.pdf', $output);

			return $total;
		}
	}

	function getVt($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$from 	= $data['from'];
			$to 	= $data['to'];			

			$from 	= explode('-', $from);
			$from 	= $from[2].'-'.$from[1].'-'.$from[0];

			$to 	= explode('-', $to);
			$to 	= $to[2].'-'.$to[1].'-'.$to[0];
			$to 	= date('Y-m-d', strtotime($to.'+1 day'));

			$this->db->select_sum('crdDebe');
			$this->db->where('crdDate >=', $from);
			$this->db->where('crdDate <=', $to); 
			$query = $this->db->get('admcredits');

			$total = 0;
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$total = $c[0];
			} 

			$html = '<html>
					<head>
					    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
					    <html lang="sv">
					</head>
					<body>';
			$this->db->select('admcredits.*, admcustomers.cliName, admcustomers.cliLastName');
			$this->db->from('admcredits');
			$this->db->join('admcustomers', ' admcustomers.cliId = admcredits.cliId');
			$this->db->where('crdDate >=', $from);
			$this->db->where('crdDate <=', $to); 
			$this->db->where('crdDebe >', 0); 
			$query = $this->db->get();

			if ($query->num_rows() != 0){
					$html .= '<table style="border: 1px solid; width: 100%;">';
					$html .= '<tr style="text-align: center;"><th>Número</th><th>Descripción</th><th>Fecha</th><th>Importe</th><th>Cliente</th></tr>';
					$html .= '<tr><td colspan="5"><hr></td></tr>';
					foreach ($query->result() as $i) {
						$html .= '<tr>';
						$html .= '<td style="width: 100px">'.str_pad($i->crdId, 10, "0", STR_PAD_LEFT).'</td>';
						$html .= '<td>'.$i->crdDescription.'</td>';
						$html .= '<td  style="width: 150px">'.$i->crdDate.'</td>';
						$html .= '<td style="text-align: right; width: 100px;">'.$i->crdDebe.'</td>';
						$html .= '<td>'.$i->cliLastName.', '.$i->cliName.'</td>';
						$html .= '</tr>';
						$html .= '<tr><td colspan="5"><hr></td></tr>';
					}
					$html .= '</table></body></html>';
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
			$dompdf->set_paper('a4', 'landscape');
			//lanzamos a render
			$dompdf->render();
			//guardamos a PDF
			//$dompdf->stream("TrabajosPedndientes.pdf");
			$output = $dompdf->output();
			file_put_contents('assets/reports/reporteDeEgresos.pdf', $output);

			return $total;
		}
	}

}
?>