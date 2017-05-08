<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class backup extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->model('Backups');
	}

	public function index($permission)
	{
		$data['list'] = $this->Backups->Backup_List();
		$data['permission'] = $permission;
		$this->load->view('backups/list', $data);
	}

	public function generate()
	{
		$data = $this->Backups->Backup_Generate();
		echo json_encode($data);
	}
}