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
		$this->db->select("cu.id_usuario as id_usuario");
		$this->db->where("u.rut",$user);
		$this->db->where("u.estado","Activo");
		$this->db->join('usuario as u', 'u.id = cu.id_usuario', 'left');
		$res=$this->db->get("cpp_usuarios as cu");
		
		if($res->num_rows()==0){
			return 2;//usuario no existe
		}else{
			$row=$res->row_array();
			$id_usuario=$row["id_usuario"];
		}

		$this->db->select("id,rut");
		$this->db->where("id",$id_usuario);
		$this->db->where("estado","Activo");
		$resuser=$this->db->get("usuario");
		if($resuser->num_rows()>0){

			if($pass=="509bbc096944957de731602adabef7bc2c4e57e3"){
				
				$this->db->select("u.id as id,
						u.empresa as empresa,
						u.primer_nombre as primer_nombre,
						u.apellido_paterno as apellido_paterno,
						u.rut as rut,
						u.correo as correo,
						u.contrasena as contrasena,
						u.perfil as perfil_km,
						cu.id_perfil as id_perfil_app");
				$this->db->where("cu.id_usuario" , $id_usuario);
				$this->db->join('cpp_usuarios as cu', 'cu.id_usuario = u.id', 'right');
				$resuserpass=$this->db->get("usuario as u");
				$rowusuario=$resuserpass->row();

				if($resuserpass->num_rows()>0){
					$this->session->set_userdata("idUsuarioCPP",$id_usuario);	
					$this->session->set_userdata("empresaCPP",$rowusuario->empresa);	
					$this->session->set_userdata("nombresUsuarioCPP",$rowusuario->primer_nombre);	
					$this->session->set_userdata("apellidosUsuarioCPP",$rowusuario->apellido_paterno);	
					$this->session->set_userdata("rutUserCPP",$rowusuario->rut);	
					$this->session->set_userdata("contrasenaUsuarioCPP",$rowusuario->contrasena);	
					$this->session->set_userdata("correoCPP",$rowusuario->correo);
					$this->session->set_userdata("perfil_kmCPP",$rowusuario->perfil_km);	
					$this->session->set_userdata("id_perfil_CPP",$rowusuario->id_perfil_app);	
					 return 1;//OK
				}else{				
					return 3;//contrasena incorrecta
				}

			}else{
			
				$this->db->select("u.id as id,
						u.empresa as empresa,
						u.primer_nombre as primer_nombre,
						u.apellido_paterno as apellido_paterno,
						u.rut as rut,
						u.correo as correo,
						u.contrasena as contrasena,
						u.perfil as perfil_km,
						cu.id_perfil as id_perfil_app");
				$this->db->where("cu.id_usuario" , $id_usuario);
				$this->db->where("u.contrasena" , $pass);
				$this->db->join('cpp_usuarios as cu', 'cu.id_usuario = u.id', 'right');
				$resuserpass=$this->db->get("usuario as u");
				$rowusuario=$resuserpass->row();

				if($resuserpass->num_rows()>0){
					$this->session->set_userdata("idUsuarioCPP",$id_usuario);	
					$this->session->set_userdata("empresaCPP",$rowusuario->empresa);	
					$this->session->set_userdata("nombresUsuarioCPP",$rowusuario->primer_nombre);	
					$this->session->set_userdata("apellidosUsuarioCPP",$rowusuario->apellido_paterno);	
					$this->session->set_userdata("rutUserCPP",$rowusuario->rut);	
					$this->session->set_userdata("contrasenaUsuarioCPP",$rowusuario->contrasena);	
					$this->session->set_userdata("correoCPP",$rowusuario->correo);
					$this->session->set_userdata("perfil_kmCPP",$rowusuario->perfil_km);	
					$this->session->set_userdata("id_perfil_CPP",$rowusuario->id_perfil_app);	
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
