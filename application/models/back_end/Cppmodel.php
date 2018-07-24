<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CPPmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
		if($this->db->insert('visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/***********CPP**********/

		public function listaCPP($desde,$hasta,$accion){
			$this->db->select("SHA1(c.id) as 'hash_id',
				c.id as id,
				c.id_actividad as id_actividad,
				cpe.id as id_proyecto_empresa,
				ca.actividad as actividad,
				ca.unidad as unidad,
				ca.valor as valor,
				cpe.proyecto_empresa as proyecto_empresa,
				ca.id_proyecto_tipo as id_proyecto_tipo,
				cpt.tipo as proyecto_tipo,
				c.id_usuario as id_usuario,
				c.id_supervisor as id_supervisor,
				c.id_digitador as id_digitador,
				c.cantidad as cantidad,
				c.proyecto_descripcion as proyecto_desc,
				c.estado as estado,

				CASE 
		          WHEN c.estado = '0' THEN 'Pend. superv.'
		          WHEN c.estado = '1' THEN 'Aprob. superv.'
		        END AS estado_str,
				c.comentarios as comentarios,
				
				hour(TIMEDIFF(CONCAT(c.fecha_termino,' ',c.hora_termino),CONCAT(c.fecha_inicio,' ',c.hora_inicio))) as 'duracion',

				if(c.fecha_inicio!='0000-00-00',c.fecha_inicio,'') as 'fecha_inicio',
				if(c.hora_inicio!='00:00:00',c.hora_inicio,'') as 'hora_inicio',
				if(c.fecha_termino!='0000-00-00',c.fecha_termino,'') as 'fecha_termino',
				if(c.hora_termino!='00:00:00',c.hora_termino,'') as 'hora_termino',
				if(c.fecha_aprobacion!='0000-00-00',c.fecha_aprobacion,'') as 'fecha_aprob',
				if(c.hora_aprobacion!='00:00:00',c.hora_aprobacion,'') as 'hora_aprob',
				if(c.fecha_digitacion!='0000-00-00',c.fecha_digitacion,'') as 'fecha_dig',
				
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'ejecutor',
				CONCAT(uss.primer_nombre,' ',uss.apellido_paterno) as 'supervisor',
				CONCAT(usss.primer_nombre,' ',usss.apellido_paterno) as 'digitador',
				c.ultima_actualizacion as ultima_actualizacion
				",false);
			$this->db->from('cpp as c');
		    $this->db->join('cpp_actividades as ca', 'ca.id = c.id_actividad', 'left');
		    $this->db->join('cpp_proyecto_tipo as cpt', 'cpt.id = ca.id_proyecto_tipo', 'left');
			$this->db->join('cpp_proyecto_empresa as cpe', 'cpe.id = cpt.id_proyecto_empresa', 'left');
			$this->db->join('cpp_usuarios as u1', 'c.id_usuario = u1.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u2', 'c.id_supervisor = u2.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u3', 'c.id_digitador = u3.id_usuario', 'left');
			$this->db->join('usuario as us', 'us.id = u1.id_usuario', 'left');
			$this->db->join('usuario as uss', 'uss.id = u2.id_usuario', 'left');
			$this->db->join('usuario as usss', 'usss.id = u3.id_usuario', 'left');

			if($desde!="" and $hasta!=""){
				$this->db->where("c.fecha_termino BETWEEN '".$desde."' AND '".$hasta."'");	
			}else{
				$this->db->where("c.fecha_termino BETWEEN '".$inicio."' AND '".date("Y-m-d")."'");	
			}

			//ACCION 1 = TODOS LOS REGISTROS

			if($accion==2){//PERSONAL DE SUPERVISOR
				$this->db->where('(u1.id_supervisor='.$this->session->userdata("idUsuarioCPP")." 
					or u1.id_supervisor2=".$this->session->userdata("idUsuarioCPP")."
					or us.id=".$this->session->userdata("idUsuarioCPP").")");
			}

			if($accion==3){//REGISTROS DEL USUARIO
				$this->db->where('c.id_usuario', $this->session->userdata("idUsuarioCPP"));
			}

			$this->db->order_by('c.id', 'desc');
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataAct($hash){
			$this->db->select("SHA1(c.id) as 'hash_id',
				c.id as id,
				c.id_actividad as id_actividad,
				cpe.id as id_proyecto_empresa,
				ca.actividad as actividad,
				ca.unidad as unidad,
				cpe.proyecto_empresa as proyecto_empresa,
				ca.id_proyecto_tipo as id_proyecto_tipo,
				cpt.tipo as proyecto_tipo,
				c.id_usuario as id_usuario,
				c.id_supervisor as id_supervisor,
				c.id_digitador as id_digitador,
				c.cantidad as cantidad,
				c.proyecto_descripcion as proyecto_desc,
				c.estado as estado,

				CASE 
		          WHEN c.estado = '0' THEN 'Pendiente supervisor'
		          WHEN c.estado = '1' THEN 'Aprobado supervisor'
		        END AS estado_str,
				c.comentarios as comentarios,
				'duracion' as duracion,
	
				if(c.fecha_inicio!='0000-00-00',c.fecha_inicio,'') as 'fecha_inicio',
				if(c.hora_inicio!='00:00:00',c.hora_inicio,'') as 'hora_inicio',
				if(c.fecha_termino!='0000-00-00',c.fecha_termino,'') as 'fecha_termino',
				if(c.hora_termino!='00:00:00',c.hora_termino,'') as 'hora_termino',
				if(c.fecha_aprobacion!='0000-00-00',c.fecha_aprobacion,'') as 'fecha_aprob',
				if(c.hora_aprobacion!='00:00:00',c.hora_aprobacion,'') as 'hora_aprob',
				if(c.fecha_digitacion!='0000-00-00',c.fecha_digitacion,'') as 'fecha_dig',
				
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'ejecutor',
				CONCAT(uss.primer_nombre,' ',uss.apellido_paterno) as 'supervisor',
				CONCAT(usss.primer_nombre,' ',usss.apellido_paterno) as 'digitador',
				c.ultima_actualizacion as ultima_actualizacion
				");
			$this->db->from('cpp as c');
		    $this->db->join('cpp_actividades as ca', 'ca.id = c.id_actividad', 'left');

		    $this->db->join('cpp_proyecto_tipo as cpt', 'cpt.id = ca.id_proyecto_tipo', 'left');

			$this->db->join('cpp_proyecto_empresa as cpe', 'cpe.id = cpt.id_proyecto_empresa', 'left');
			
			$this->db->join('cpp_usuarios as u1', 'c.id_usuario = u1.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u2', 'c.id_supervisor = u2.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u3', 'c.id_digitador = u3.id_usuario', 'left');
			
			$this->db->join('usuario as us', 'us.id = u1.id_usuario', 'left');
			$this->db->join('usuario as uss', 'uss.id = u2.id_usuario', 'left');
			$this->db->join('usuario as usss', 'usss.id = u3.id_usuario', 'left');
			$this->db->where('sha1(c.id)', $hash);
		
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
		}

		public function getEstadoCpp($id_cpp){
			$this->db->select('estado');
			$this->db->where('sha1(id)', $id_cpp);
			$res=$this->db->get('cpp');
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["estado"];
			}
			return FALSE;
		}

		public function listaProyectoEmpresa(){
			$this->db->select('id,proyecto_empresa as nombre');
			$this->db->order_by('id', 'asc');
			$res=$this->db->get('cpp_proyecto_empresa');
			return $res->result_array();
		}

		public function getTiposPorPe($pe){
			if($pe!=""){
				$this->db->where('id_proyecto_empresa', $pe);
			}
			$this->db->order_by('tipo', 'asc');
			$res=$this->db->get('cpp_proyecto_tipo');

			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["tipo"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
		}

		public function getActividadesPorTipo($pt){
			if($pt!=""){
				$this->db->where('id_proyecto_tipo', $pt);
			}
			$this->db->order_by('actividad', 'asc');
			$res=$this->db->get('cpp_actividades');

			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["actividad"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
		}

		


		public function eliminaActividad($hash){
		  $this->db->where('sha1(id)', $hash);
		  $this ->db->delete('cpp');
		  return TRUE;
		}

		public function checkNumeroOrden($numero){
			$this->db->where('numero_orden', $numero);
			$res=$this->db->get('movistar_informes_de_avance');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}


		public function formCPP($data){
			if($this->db->insert('cpp', $data)){
				$insert_id = $this->db->insert_id();
				return $insert_id;
			}
			return FALSE;
		}

		public function modFormCPP($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('cpp', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function getUmPorActividad($ac){
			$this->db->select('unidad');
			$this->db->where('id', $ac);
			$res=$this->db->get('cpp_actividades');
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["unidad"];
			}else{
				return FALSE;
			}
			
		}

		public function getUsuariosSel2CPP($accion){
			$this->db->select('u.id as id,
				u.primer_nombre as primer_nombre,
				u.apellido_paterno as apellido_paterno,
				u.apellido_materno as apellido_materno,
				u.empresa as empresa');
			$this->db->where('estado', "Activo");

			//ACCION 1 = TODOS LOS REGISTROS

			if($accion==2){//PERSONAL DE SUPERVISOR
				$this->db->where('(cu.id_supervisor='.$this->session->userdata("idUsuarioCPP")." 
					or cu.id_supervisor2=".$this->session->userdata("idUsuarioCPP")."
					or u.id=".$this->session->userdata("idUsuarioCPP").")");
			}

			if($accion==3){//REGISTROS DEL USUARIO
				$this->db->where('cu.id_usuario', $this->session->userdata("idUsuarioCPP"));
			}

			$this->db->order_by('u.primer_nombre', 'asc');
			$this->db->join('usuario as u', 'u.id = cu.id_usuario', 'left');
			$res=$this->db->get("cpp_usuarios as cu");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["primer_nombre"]." ".$key["apellido_paterno"]." ".$key["apellido_materno"]." | ".$key["empresa"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

	/***********VISTA MENSUAL**********/

		public function listaMes($fecha,$usuario,$accion){
			$this->db->select("SHA1(c.id) as 'hash_id',
				c.id as id,
				c.id_actividad as id_actividad,
				cpe.id as id_proyecto_empresa,
				ca.actividad as actividad,
				ca.unidad as unidad,
				ca.valor as valor,
				cpe.proyecto_empresa as proyecto_empresa,
				ca.id_proyecto_tipo as id_proyecto_tipo,
				cpt.tipo as proyecto_tipo,
				c.id_usuario as id_usuario,
				c.id_supervisor as id_supervisor,
				c.id_digitador as id_digitador,
				c.cantidad as cantidad,
				c.proyecto_descripcion as proyecto_desc,
				c.estado as estado,

				CASE DATE_FORMAT(c.fecha_termino, '%W') 
				  WHEN 'Sunday' THEN 'Domingo' 
				  WHEN 'Monday' THEN 'Lunes'
				  WHEN 'Tuesday' THEN 'Martes'
				  WHEN 'Wednesday' THEN 'Miercoles'
				  WHEN 'Thursday' THEN 'Jueves'
				  WHEN 'Friday' THEN 'Viernes'
				  WHEN 'Saturday' THEN 'Sabado'
				END AS 'dia',

				CASE 
		          WHEN c.estado = '0' THEN 'Pend. superv.'
		          WHEN c.estado = '1' THEN 'Aprob. superv.'
		        END AS estado_str,
				c.comentarios as comentarios,

				if(c.fecha_inicio!='0000-00-00',DATE_FORMAT(c.fecha_inicio,'%d-%m-%Y'),'') as 'fecha_inicio',
				if(c.hora_inicio!='00:00:00',c.hora_inicio,'') as 'hora_inicio',
				if(c.fecha_termino!='0000-00-00',DATE_FORMAT(c.fecha_termino,'%d-%m-%Y'),'') as 'fecha_termino',
				if(c.hora_termino!='00:00:00',c.hora_termino,'') as 'hora_termino',
				if(c.fecha_aprobacion!='0000-00-00',DATE_FORMAT(c.fecha_aprobacion,'%d-%m-%Y'),'') as 'fecha_aprob',
				if(c.hora_aprobacion!='00:00:00',c.hora_aprobacion,'') as 'hora_aprob',
				if(c.fecha_digitacion!='0000-00-00',DATE_FORMAT(c.fecha_digitacion,'%d-%m-%Y'),'') as 'fecha_dig',
				
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'ejecutor',
				CONCAT(uss.primer_nombre,' ',uss.apellido_paterno) as 'supervisor',
				CONCAT(usss.primer_nombre,' ',usss.apellido_paterno) as 'digitador',
				c.ultima_actualizacion as ultima_actualizacion
				",false);
			$this->db->from('cpp as c');
		    $this->db->join('cpp_actividades as ca', 'ca.id = c.id_actividad', 'left');
		    $this->db->join('cpp_proyecto_tipo as cpt', 'cpt.id = ca.id_proyecto_tipo', 'left');
			$this->db->join('cpp_proyecto_empresa as cpe', 'cpe.id = cpt.id_proyecto_empresa', 'left');
			$this->db->join('cpp_usuarios as u1', 'c.id_usuario = u1.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u2', 'c.id_supervisor = u2.id_usuario', 'left');
			$this->db->join('cpp_usuarios as u3', 'c.id_digitador = u3.id_usuario', 'left');
			$this->db->join('usuario as us', 'us.id = u1.id_usuario', 'left');
			$this->db->join('usuario as uss', 'uss.id = u2.id_usuario', 'left');
			$this->db->join('usuario as usss', 'usss.id = u3.id_usuario', 'left');
			$this->db->where('MONTH(c.fecha_termino)=',date("m", strtotime($fecha)));
			$this->db->where('YEAR(c.fecha_termino)=',date("Y", strtotime($fecha)));

			if($usuario!=""){
				$this->db->where('u1.id_usuario', $usuario);
			}

			//ACCION 1 = TODOS LOS REGISTROS

			if($accion==2){//PERSONAL DE SUPERVISOR
				$this->db->where('(u1.id_supervisor='.$this->session->userdata("idUsuarioCPP")." 
					or u1.id_supervisor2=".$this->session->userdata("idUsuarioCPP")."
					or us.id=".$this->session->userdata("idUsuarioCPP").")");
			}

			if($accion==3){//REGISTROS DEL USUARIO
				$this->db->where('c.id_usuario', $this->session->userdata("idUsuarioCPP"));
			}

			$this->db->order_by('c.fecha_termino', 'desc');
			$this->db->order_by('c.hora_termino', 'desc');
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getNombrePorId($id){
			$this->db->select('primer_nombre,apellido_paterno,apellido_materno');
			$this->db->where('id', $id);
			$res=$this->db->get('usuario');
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["primer_nombre"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
			}else{
				return FALSE;
			}
			
		}


	/***********MANTENEDOR ACTIVIDADES**********/
		
		public function formActividad($data){
			if($this->db->insert('cpp_actividades', $data)){
				$insert_id = $this->db->insert_id();
					return $insert_id;
				}
				return FALSE;
		}

		public function modFormActividad($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('cpp_actividades', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaActividad($empresa){
			$this->db->select("SHA1(a.id) as 'hash_id',
			 a.id as id,
			 pe.proyecto_empresa as proyecto,
			 pe.id as id_proyecto_empresa,
			 pt.tipo as tipo_proyecto,
			 a.id_proyecto_tipo as proyecto_tipo,
			 a.actividad as actividad, 
			 a.unidad as unidad,
			 a.valor as valor, 
			 a.porcentaje as porcentaje");
			$this->db->from('cpp_actividades as a');
			$this->db->join('cpp_proyecto_tipo as pt', 'pt.id = a.id_proyecto_tipo');
			$this->db->join('cpp_proyecto_empresa as pe', 'pe.id = pt.id_proyecto_empresa');

			if($empresa != ""){
				$this->db->where("pe.id", $empresa);
			}
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function deleteActividad($hash){
			$this->db->where('sha1(id)', $hash);
			  $this ->db->delete('cpp_actividades');
			  return TRUE;
		}

		public function getDataActividad($hash){
			$this->db->select("SHA1(a.id) as 'hash_id', a.id as id, pe.proyecto_empresa as proyecto, 
			pe.id as id_proyecto_empresa, a.id_proyecto_tipo as id_proyecto_tipo, a.actividad as actividad,
			 a.unidad as unidad, a.valor as valor, a.porcentaje as porcentaje");
			$this->db->from('cpp_actividades as a');
			$this->db->join('cpp_proyecto_tipo as pt', 'pt.id = a.id_proyecto_tipo');
			$this->db->join('cpp_proyecto_empresa as pe', 'pe.id = pt.id_proyecto_empresa');
			$this->db->where('sha1(a.id)', $hash);
			$res=$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function checkActividad($id){
			$this->db->where('actividad', $id);
			$res=$this->db->get('cpp_actividades');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}



	/***********MANTENEDOR USUARIOS**********/

		public function getUsuariosSel2(){
			$this->db->select('id,rut,primer_nombre,segundo_nombre,apellido_paterno,apellido_materno,empresa');
			$this->db->where('estado', "Activo");
			$this->db->order_by('primer_nombre', 'asc');
			$res=$this->db->get("usuario");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["primer_nombre"]." ".$key["apellido_paterno"]." ".$key["apellido_materno"]." | ".$key["empresa"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function checkUsuario($id){
			$this->db->where('id_usuario', $id);
			$res=$this->db->get('cpp_usuarios');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function checkUsuarioMod($hash,$id){
			$this->db->where('sha1(id)', $hash);
			$this->db->where('id_usuario<>', $id);
			$res=$this->db->get('cpp_usuarios');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		
		public function getPerfiles(){
			$this->db->order_by('id', 'asc');
			$res=$this->db->get('cpp_usuarios_perfiles');
			return $res->result_array();
		}

		public function listaMantUsuarios(){
			$this->db->select("sha1(cu.id) as hash_id,
				cu.id as id_mant,
				u.id as id_usuario,
				us.id as id_supervisor,
				us2.id as id_supervisor2,
				u.rut as rut,
				c.cargo as cargo,
				u.empresa as empresa,
				CONCAT(u.primer_nombre,' ',u.apellido_paterno,' ',u.apellido_materno) as 'usuario',
				CONCAT(us.primer_nombre,' ',us.apellido_paterno,' ',us.apellido_materno) as 'supervisor',
				CONCAT(us2.primer_nombre,' ',us2.apellido_paterno,' ',us2.apellido_materno) as 'supervisor2',
				up.perfil as perfil,
				up.id as id_perfil,
				cu.ultima_actualizacion as ultima_actualizacion
				");

			$this->db->join('cpp_usuarios as cu', 'cu.id_usuario = u.id', 'right');
			$this->db->join('cpp_usuarios_perfiles as up', 'up.id = cu.id_perfil', 'left');
			$this->db->join('usuario as us', 'us.id = cu.id_supervisor', 'left');
			$this->db->join('usuario as us2', 'us2.id = cu.id_supervisor2', 'left');
			$this->db->join('mantenedor_cargo as c', 'u.id_cargo = c.id', 'left');

			if($this->session->userdata('idUsuarioCPP')!=432 and $this->session->userdata('idUsuarioCPP')!=824){
				$this->db->where('(cu.id_usuario<>432 and cu.id_usuario<>824)');
			}

			$res=$this->db->get('usuario as u');
			return $res->result_array();
		}

		public function formMantUs($data){
			if($this->db->insert('cpp_usuarios', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function modFormMantUs($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('cpp_usuarios', $data)){
				return TRUE;
			}
			return FALSE;
		}


		public function getDataMantUs($hash){
			$this->db->select("sha1(cu.id) as hash_id,
				cu.id as id_mant,
				u.id as id_usuario,
				us.id as id_supervisor,
				us2.id as id_supervisor2,
				u.rut as rut,
				u.empresa as empresa,
				CONCAT(u.primer_nombre,' ',u.apellido_paterno) as 'usuario',
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'supervisor',
				CONCAT(us2.primer_nombre,' ',us2.apellido_paterno) as 'supervisor2',
				up.perfil as perfil,
				up.id as id_perfil,
				cu.ultima_actualizacion as ultima_actualizacion
				");
			$this->db->join('cpp_usuarios as cu', 'cu.id_usuario = u.id', 'right');
			$this->db->join('cpp_usuarios_perfiles as up', 'up.id = cu.id_perfil', 'left');
			$this->db->join('usuario as us', 'us.id = cu.id_supervisor', 'left');
			$this->db->join('usuario as us2', 'us2.id = cu.id_supervisor2', 'left');
			$this->db->where('sha1(cu.id)', $hash);
			$res=$this->db->get('usuario as u');
			if($res->num_rows()>0){
				return $res->result_array();
			}
		}

		public function getSupervisores(){
			$this->db->select("CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'nombre',us.id as id");
			$this->db->join('usuario as us', 'us.id = u1.id_usuario', 'left');
			$this->db->where('u1.id_perfil', "3");
			$res=$this->db->get('cpp_usuarios as u1');
			return $res->result_array();
		}

		public function eliminaUsuario($hash){
		  $this->db->where('sha1(id)', $hash);
		  $this ->db->delete('cpp_usuarios');
		  return TRUE;
		}


		


}