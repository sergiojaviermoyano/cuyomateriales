<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Users extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function User_List(){

		$query= $this->db->get('sisusers');
		
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{
			return false;
		}
	}
	
	function getUser($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idUsr = $data['id'];

			$data = array();

			//Datos del usuario
			$query= $this->db->get_where('sisusers',array('usrId'=>$idUsr));
			if ($query->num_rows() != 0)
			{
				$u = $query->result_array();
				$data['user'] = $u[0];
			} else {
				$user = array();
				$user['usrNick'] = '';
				$user['usrName'] = '';
				$user['usrLastName'] = '';
				$user['usrComision'] = '';
				$user['usrPassword'] = '';
				$user['grpId'] = 1;

				$data['user'] = $user;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			//Grupos
			$query= $this->db->get('sisgroups');
			if ($query->num_rows() != 0)
			{
				$data['groups'] = $query->result_array();	
			}
			
			return $data;
		}
	}
	
	function setUser($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
			$act = $data['act'];
			$usr = $data['usr'];
			$name = $data['name'];
			$lnam = $data['lnam'];
			$com = $data['com'];
			$pas = $data['pas'];
			$grp = $data['grp'];

			if($act == 'Edit') {
				if($pas == '') {
					//No modificar la contraseña
					$data = array(
					   'usrNick' => $usr,
					   'usrName' => $name,
					   'usrLastName' => $lnam,
					   'usrComision' => $com,
					   'grpId' => $grp
					);
				} else {
					//Modificar la contraseña
					$data = array(
					   'usrNick' => $usr,
					   'usrName' => $name,
					   'usrLastName' => $lnam,
					   'usrComision' => $com,
					   'usrPassword' => md5($pas),
					   'grpId' => $grp
					);
				}
			} else {
				$data = array(
					   'usrNick' => $usr,
					   'usrName' => $name,
					   'usrLastName' => $lnam,
					   'usrComision' => $com,
					   'usrPassword' => md5($pas),
					   'grpId' => $grp
					);
			}

			switch($act){
				case 'Add':
					//Agregar Usuario 
					if($this->db->insert('sisusers', $data) == false) {
						return false;
					}else{
						return true;
					}
					break;

				 case 'Edit':
				 	//Actualizar usuario
				 	if($this->db->update('sisusers', $data, array('usrId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;

				 case 'Del':
				 	//Eliminar usuario
				 	if($this->db->delete('sisusers', array('usrId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
			}

			return true;

		}
	}

	function sessionStart_($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$usr = $data['usr'];
			$pas = md5($data['pas']);

			$data = array(
					'usrNick' => $usr,
					'usrPassword' => $pas
				);

			$query= $this->db->get_where('sisusers',$data);
			if ($query->num_rows() != 0)
			{
				$token = $this->randString(255);
				$data = $query->result_array();
				$data[0]['logged_in'] = true;
				$data[0]['usrToken'] = $token;
				$data[0]['token'] = $token;
				$this->session->set_userdata('user_data', $data);

				//Assiganar el token--------------------------------------------------------------
				//Cambiar el token
				$update = array(
					'usrToken'	=> $token
				);
				
				if($this->db->update('sisusers', $update, array('usrId' => $data[0]['usrId'])) == false){
					return false;
				}
				//---------------------------------------------------------------------------------

				$this->updateSession(false);

				return true;
			} else {
				return false;
			}
		}
	}

	function updateSession($firstTime){
		//Identificación del usuario logueado
		$userdata = $this->session->userdata('user_data');
		$token = $userdata[0]['token'];
		//-----------------------------------
		$ahora = date('Y-m-d H:i:s');
		//Validar tiempo de sesión abierto---
		if($firstTime == true) //No es la primera vez
		{
			//Validar horario última operación de la session abierta
			$query = $this->db->get_where('sisusers', array('usrToken' => $token));
			if($query->num_rows() != 0){
				$data = $query->result_array();
				
				$segundos = strtotime($ahora) - strtotime($data[0]['usrLastAcces']);

				if($segundos > $this->config->config['sess_expiration'])
				{
					//Expiro la session //Cambiar el token
					$update = array(
						'usrToken'	=> $this->randString(255)
					);

					if($this->db->update('sisusers', $update, array('usrToken' => $token)) == false){
						return false;
					}

					redirect('/user/lost');
				}
			}
			else
			{
				//Cambiar el token
				$update = array(
						'usrToken'	=> $this->randString(255)
					);

					if($this->db->update('sisusers', $update, array('usrToken' => $token)) == false){
						return false;
					}

				redirect('/user/lost');
			}
		}
		//-----------------------------------

		$update = array(
						'usrLastAcces'	=> $ahora
					);

		if($this->db->update('sisusers', $update, array('usrToken' => $token)) == false){
			return false;
		}
	}

	function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
	{
	    $str = '';
	    $count = strlen($charset);
	    while ($length--) {
	        $str .= $charset[mt_rand(0, $count-1)];
	    }
	    return $str;
	}

	function cerrarSession(){
		$userdata = $this->session->userdata('user_data');
		$token = $userdata[0]['token'];
		//Cambiar el token
		$update = array(
				'usrToken'	=> $this->randString(255)
			);

			if($this->db->update('sisusers', $update, array('usrToken' => $token)) == false){
				return false;
			}

		redirect('/');
	}
	
}
?>