<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Backups extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Backup_List(){

		$query= $this->db->get('sisgroups');
		//var_dump($query);
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}

	function Backup_Generate(){
		$this->load->dbutil();

		// Crea una copia de seguridad de toda la base de datos y la asigna a una variable
		$copia_de_seguridad = &$this->dbutil->backup(); 

		// Carga el asistente de archivos y escribe el archivo en su servidor
		$this->load->helper('file');
		write_file('assets/backs/db-backup-last.gz', $copia_de_seguridad); 

		// Carga el asistente de descarga y envía el archivo a su escritorio
		//$this->load->helper('download');
		//force_download('copia_de_seguridad.gz', $copia_de_seguridad);

		return true;
	}
}
?>