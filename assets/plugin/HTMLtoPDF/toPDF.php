<?php
//se incluye la libreria de dompdf
require_once("dompdf/dompdf_config.inc.php");
//Obtenemos codigo HTML pasado por el form
if(get_magic_quotes_gpc() == 0) {
	$code = $_POST['code'];    
}else
{	
	$code = stripslashes($_POST['code']);
}
//se crea una nueva instancia al DOMPDF
$dompdf = new DOMPDF();
//se carga el codigo html
$dompdf->load_html('hola tarola');
//aumentamos memoria del servidor si es necesario
ini_set("memory_limit","32M"); 
//lanzamos a render
$dompdf->render();
//guardamos a PDF
$dompdf->stream("mipdf.pdf");
?>