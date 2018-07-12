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

		public function saber_dia($nombredia) {
			$dias = array('', 'Lun','Mar','Mie','Jue','Vie','Sab', 'Dom');
			$fecha = $dias[date('N', strtotime($nombredia))];
			return $fecha;
		}

		public function listaCPP($desde,$hasta){
			$this->db->select("SHA1(c.id) as 'hash_id',
				c.id as id,
				c.id_actividad as id_actividad,
				cpe.id as id_proyecto_empresa,
				ca.actividad as actividad,
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

				if(c.fecha!='0000-00-00',c.fecha,'') as 'fecha',
				if(c.fecha_aprobacion!='0000-00-00',c.fecha_aprobacion,'') as 'fecha_aprob',
				if(c.fecha_digitacion!='0000-00-00',c.fecha_digitacion,'') as 'fecha_dig',
				
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'usuario',
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

			if($desde!="" and $hasta!=""){
				$this->db->where("c.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}else{
				$this->db->where("c.fecha BETWEEN '".$inicio."' AND '".date("Y-m-d")."'");	
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

				if(c.fecha!='0000-00-00',c.fecha,'') as 'fecha',
				if(c.fecha_aprobacion!='0000-00-00',c.fecha_aprobacion,'') as 'fecha_aprob',
				if(c.fecha_digitacion!='0000-00-00',c.fecha_digitacion,'') as 'fecha_dig',
				
				CONCAT(us.primer_nombre,' ',us.apellido_paterno) as 'usuario',
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

	/***********VISTA MENSUAL**********/



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
		$this->db->select("SHA1(a.id) as 'hash_id', a.id as id, pe.proyecto_empresa as proyecto,
		 pe.id as id_proyecto_empresa, a.id_proyecto_tipo as proyecto_tipo,
		 a.actividad as actividad, a.unidad as unidad, a.valor as valor, a.porcentaje as porcentaje");
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

	/***********MANTENEDOR USUARIOS**********/







}