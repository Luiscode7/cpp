<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CPP extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		$this->load->model("back_end/Iniciomodel");
		$this->load->model("back_end/CPPmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
		
	}

	public function checkLogin(){
		if($this->session->userdata('idUsuarioCPP')==""){
			echo json_encode(array('res'=>"sess"));exit;
		}
	}


	public function visitas(){
		$data=array("usuario"=>$this->session->userdata('nombresUsuarioCPP')." ".$this->session->userdata('apellidosUsuarioCPP'),
     	"fecha"=>date("Y-m-d G:i:s"),
    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
    	"ip"=>$this->input->ip_address(),
    	"pagina"=> "CPP"
    	);
    	$this->Iniciomodel->insertarVisita($data);
	}

	/*********CPP******/
		public function getCPPInicio(){
			$this->visitas();
	    	$fecha_hoy=date('Y-m-d');
		    $datos = array(
		       'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/inicio',$datos);
		}

		public function getCPPView(){
	    	$fecha_hoy=date('Y-m-d');
			$fecha_anio_atras=date('Y-m-d', strtotime('-60 day', strtotime(date("Y-m-d"))));
	    	$proyecto_empresa=$this->CPPmodel->listaProyectoEmpresa();
		    $datos = array(
		       'proyecto_empresa'=>$proyecto_empresa,
		       'fecha_anio_atras' => $fecha_anio_atras,	 
		       'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/cpp',$datos);
		}

		public function getTiposPorPe(){
			$pe=$this->security->xss_clean(strip_tags($this->input->get_post("pe")));
		    echo $this->CPPmodel->getTiposPorPe($pe);exit;
		}

		public function getActividadesPorTipo(){
			$pt=$this->security->xss_clean(strip_tags($this->input->get_post("pt")));
		    echo $this->CPPmodel->getActividadesPorTipo($pt);exit;
		}

		public function listaCPP(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			$accion=$this->security->xss_clean(strip_tags($this->input->get_post("accion")));
	        echo json_encode($this->CPPmodel->listaCPP($desde,$hasta,$accion));
		}

		public function getUsuariosSel2CPP(){
			$id_usuario=$this->security->xss_clean(strip_tags($this->input->get_post("idUsuarioCPP")));
		    echo $this->CPPmodel->getUsuariosSel2CPP($id_usuario);exit;
		}

		public function formCPP(){
			sleep(1);
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$id_cpp=$this->security->xss_clean(strip_tags($this->input->post("id_cpp")));
				$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
				$ejecutor=$this->security->xss_clean(strip_tags($this->input->post("ejecutor")));
				$cantidad=$this->security->xss_clean(strip_tags($this->input->post("cantidad")));
				$fecha_inicio=$this->security->xss_clean(strip_tags($this->input->post("fecha_inicio")));
				$hora_inicio=$this->security->xss_clean(strip_tags($this->input->post("hora_inicio")));
				$fecha_finalizacion=$this->security->xss_clean(strip_tags($this->input->post("fecha_finalizacion")));
				$hora_finalizacion=$this->security->xss_clean(strip_tags($this->input->post("hora_finalizacion")));
				$proyecto_desc=$this->security->xss_clean(strip_tags($this->input->post("proyecto_desc")));
				$estado=$this->security->xss_clean(strip_tags($this->input->post("estado")));
				$comentarios=$this->security->xss_clean(strip_tags($this->input->post("comentarios")));
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuarioCPP")." ".$this->session->userdata("apellidosUsuarioCPP");

				if ($this->form_validation->run("formCPP") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if(!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $hora_inicio)){
		     			 echo json_encode(array("res" => "error" , "msg" => "Formato incorrecto para la hora de inicio, intente nuevamente."));exit;
					}
					if(!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $hora_finalizacion)){
		   			   echo json_encode(array("res" => "error" , "msg" => "Formato incorrecto para la hora de término, intente nuevamente."));exit;
					}

					$data_insert=array("id_actividad"=>$actividad,
						"id_usuario"=>$ejecutor,
						"id_supervisor"=>0,
						"id_digitador"=>$this->session->userdata('idUsuarioCPP'),
						"fecha_inicio"=>$fecha_inicio,
						"hora_inicio"=>$hora_inicio,
						"fecha_termino"=>$fecha_finalizacion,
						"hora_termino"=>$hora_finalizacion,
						"cantidad"=>$cantidad,
						"proyecto_descripcion"=>$proyecto_desc,
						"estado"=>"0",
						"comentarios"=>$comentarios,
						"fecha_aprobacion"=>"0000-00-00",
						"hora_aprobacion"=>"00:00:00",
						"fecha_digitacion"=>date("Y-m-d"),
						"ultima_actualizacion"=>$ultima_actualizacion
					);	

					if($id_cpp==""){

						if($this->CPPmodel->formCPP($data_insert)){
							echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{

						$data_mod=array("id_actividad"=>$actividad,
							"fecha_inicio"=>$fecha_inicio,
							"hora_inicio"=>$hora_inicio,
							"fecha_termino"=>$fecha_finalizacion,
							"hora_termino"=>$hora_finalizacion,
							"cantidad"=>$cantidad,
							"proyecto_descripcion"=>$proyecto_desc,
							"comentarios"=>$comentarios,
							"ultima_actualizacion"=>$ultima_actualizacion
					    );	

						$estado_db=$this->CPPmodel->getEstadoCpp($id_cpp);

						if($estado_db==0 and $estado==1){
							$data_mod["id_supervisor"]=$this->session->userdata("idUsuarioCPP");
							$data_mod["fecha_aprobacion"]=date("Y-m-d");
							$data_mod["hora_aprobacion"]=date("G:i:s");
							$data_mod["estado"]=$estado;
						}elseif($estado_db==1 and $estado==1){
							$data_mod["id_supervisor"]=$this->session->userdata("idUsuarioCPP");
							$data_mod["fecha_aprobacion"]=date("Y-m-d");
							$data_mod["hora_aprobacion"]=date("G:i:s");
							$data_mod["estado"]=$estado;
						}else{
							//$data_mod["estado"]="0";;
						}

			   			if($this->CPPmodel->modFormCPP($id_cpp,$data_mod)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					    }
					}
	    		}	
			}
		}

		public function getDataAct(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->CPPmodel->getDataAct($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaActividad(){
			$this->checkLogin();
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		    if($this->CPPmodel->eliminaActividad($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function getUmPorActividad(){
			$ac=$this->security->xss_clean(strip_tags($this->input->post("ac")));
			$data=$this->CPPmodel->getUmPorActividad($ac);
		    if($data!=FALSE){
		  		echo json_encode(array("res" => "ok" ,"dato" => $data));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas cargando la unidad de medida, intente nuevamente."));
		    }
		}

		public function excelTareas(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);	
	    	$nombre="reporte-tareas-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
			</style>
		    <h3>Detalle de tareas</h3>
		        <table align='center' border="1"> 
			        <thead>
			        <tr style="background-color:#F9F9F9">
			          <th>Estado</th>
			          <th>Ejecutor</th>  
			          <th>Fecha inicio</th>
			          <th>Hora inicio</th>
			          <th>Fecha fin.</th>
			          <th>Hora fin.</th>
			          <th>Duraci&oacute;n</th>
			          <th>Proyecto Empresa</th>  
			          <th>Proyecto Tipo</th>  
			          <th>Actividad</th>  
			          <th>Proyecto Descripci&oacute;n</th>
			          <th>Unidad</th>
			          <th>Valor</th>
			          <th>Cantidad</th>
			          <th>Supervisor</th> 
			          <th>Fecha Aprob</th>   
			          <th>Hora Aprob</th>   
			          <th>Digitador</th>
			          <th>Fecha digitaci&oacute;n</th>
			          <th>Observaciones</th>    
			          <th>&Uacute;ltima actualizaci&oacute;n</th>    
			        </tr>
			        </thead>	
					<tbody>
			        <?php 
			        $detalle=$this->CPPmodel->listaCPP($desde,$hasta,"");
			        	if($detalle !=FALSE){
			      		foreach($detalle as $det){
			      			?>
			      			 <tr>
								 <td><?php echo utf8_decode($det["estado_str"]); ?></td>
								 <td><?php echo utf8_decode($det["ejecutor"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_inicio"]); ?></td>
								 <td><?php echo utf8_decode(substr($det["hora_inicio"], 0, -2)); ?></td>
								 <td><?php echo utf8_decode($det["fecha_termino"]); ?></td>
								 <td><?php echo utf8_decode(substr($det["hora_termino"], 0, -2)); ?></td>
								 <td><?php echo utf8_decode($det["duracion"]); ?></td>
								 <td><?php echo utf8_decode($det["proyecto_empresa"]); ?></td>
								 <td><?php echo utf8_decode($det["proyecto_tipo"]); ?></td>
								 <td><?php echo utf8_decode($det["actividad"]); ?></td>
								 <td><?php echo utf8_decode($det["proyecto_desc"]); ?></td>
								 <td><?php echo utf8_decode($det["unidad"]); ?></td>
								 <td><?php echo utf8_decode($det["valor"]); ?></td>
								 <td><?php echo utf8_decode($det["cantidad"]); ?></td>
								 <td><?php echo utf8_decode($det["supervisor"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_aprob"]); ?></td>
								 <td><?php echo utf8_decode($det["hora_aprob"]); ?></td>
								 <td><?php echo utf8_decode($det["digitador"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_dig"]); ?></td>
								 <td><?php echo utf8_decode($det["comentarios"]); ?></td>
								 <td><?php echo utf8_decode($det["ultima_actualizacion"]); ?></td>
							 </tr>
			      			<?php
			      		}
			      		}
			          ?>
			        </tbody>
		        </table>
		    <?php
		}

	/********VISTA MENSUAL*********/

		public function getVistaMensualView(){
	    	$fecha_hoy=date('Y-m-d');
	    	$desde=date('Y-m');
	    	$hasta=date('Y-m');
		    $datos = array(
		       'fecha_hoy' => $fecha_hoy,
		       'desde' => $desde,
		       'hasta' => $hasta
			);  
			$this->load->view('back_end/cpp/vista_mensual',$datos);
		}

		public function listaMes(){
			$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
			$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
			if($desde!=""){$desde=$desde."-01";}else{$desde="";}	
		    if($hasta!=""){$hasta=$hasta."-31";}else{$hasta="";}	
		    if($this->session->userdata('id_perfil_CPP')==4){
		    	$usuario=$this->session->userdata('idUsuarioCPP');
		    }else{
		  		$usuario=$this->security->xss_clean(strip_tags($this->input->get_post("usuario")));
		    }
			echo json_encode($this->CPPmodel->listaMes($desde,$hasta,$usuario));
		}

		public function excelMensual(){
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);	
			$usuario=$this->uri->segment(4);	
			if($desde!=""){$desde=$desde."-01";}else{$desde="";}	
		    if($hasta!=""){$hasta=$hasta."-31";}else{$hasta="";}	
		   
		    if($this->session->userdata('id_perfil_CPP')==4){
		    	$usuario=$this->session->userdata('idUsuarioCPP');
		    }else{
		  		$usuario=$usuario;
		    }
		   
		    $nombre_us=strtolower(url_title(convert_accented_characters($this->CPPmodel->getNombrePorId($usuario)),'-',TRUE));
	    	$nombre="reporte-tareas-".$nombre_us."-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;text-align:center;   vertical-align:middle;}
			</style>
		    <h3>Detalle de tareas</h3>
		        <table align='center' border="1"> 
			        <thead>
			        <tr style="background-color:#F9F9F9">
			          <th>D&iacute;a</th>
			          <th>Fecha</th>
			          <th>ID CPP</th>  
			          <th>Proyecto Empresa</th>  
			          <th>Actividad</th>  
			          <th>Unidad</th>  
			          <th>Cantidad</th>  
			          <th>Proyecto ID/Descripci&oacute;n</th>  
			          <th>Comentario</th>  
			          <th>Estado</th>  
			          <th>Fecha Inicio</th>  
			          <th>Hora Inicio</th>  
			          <th>Fecha T&eacute;rmino</th>  
			          <th>Hora T&eacute;rmino</th>  
			          <th>Fecha Aprobaci&oacute;n</th>  
			          <th>Hora Digitaci&oacute;n</th>  
			          <th>Fecha Digitaci&oacute;n</th>  
			          <th>&Uacute;ltima actualizaci&oacute;n</th>  
			        </tr>
			        </thead>	
					<tbody>
			        <?php 
			        $detalle=$this->CPPmodel->listaMes($desde,$hasta,$usuario);
			        	if($detalle !=FALSE){
			      		foreach($detalle as $det){
			      			?>
			      			 <tr>
								 <td><?php echo utf8_decode($det["dia"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_termino"]); ?></td>
								 <td><?php echo utf8_decode($det["id"]); ?></td>
								 <td><?php echo utf8_decode($det["proyecto_empresa"]); ?></td>
								 <td><?php echo utf8_decode($det["actividad"]); ?></td>
								 <td><?php echo utf8_decode($det["unidad"]); ?></td>
								 <td><?php echo utf8_decode($det["cantidad"]); ?></td>
								 <td><?php echo utf8_decode($det["proyecto_desc"]); ?></td>
								 <td><?php echo utf8_decode($det["comentarios"]); ?></td>
								 <td><?php echo utf8_decode($det["estado_str"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_inicio"]); ?></td>
								 <td><?php echo utf8_decode(substr($det["hora_inicio"], 0, -2)); ?></td>
								 <td><?php echo utf8_decode($det["fecha_termino"]); ?></td>
								 <td><?php echo utf8_decode(substr($det["hora_termino"], 0, -2)); ?></td>
								 <td><?php echo utf8_decode($det["fecha_aprob"]); ?></td>
								 <td><?php echo utf8_decode(substr($det["hora_aprob"], 0, -2)); ?></td>
								 <td><?php echo utf8_decode($det["fecha_dig"]); ?></td>
								 <td><?php echo utf8_decode($det["ultima_actualizacion"]); ?></td>
							 </tr>
			      			<?php
			      		}
			      		}
			          ?>
			        </tbody>
		        </table>
		    <?php
		}


	/********MANTENEDOR ACTIVIDADES*********/

		public function getMantActView(){
			$fecha_hoy=date('Y-m-d');
			$proyecto_empresa=$this->CPPmodel->listaProyectoEmpresa();
			$datos = array(
				'proyecto_empresa'=>$proyecto_empresa,
			   'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/mantenedor_actividades',$datos);
		}


		public function formActividad(){
			sleep(1);
			if($this->input->is_ajax_request()){
				$id_actividad=$this->security->xss_clean(strip_tags($this->input->post("id_actividad")));
				$id_proyecto_tipo=$this->security->xss_clean(strip_tags($this->input->post("proyecto_tipo")));
				$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
				$valor=$this->security->xss_clean(strip_tags($this->input->post("valor")));
				$unidad=$this->security->xss_clean(strip_tags($this->input->post("unidad")));
				$porcentaje=$this->security->xss_clean(strip_tags($this->input->post("porcentaje")));
				
				if ($this->form_validation->run("formActividad") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data_insert=array(
						"id_proyecto_tipo"=>$id_proyecto_tipo,
						"actividad"=>$actividad,
						"unidad"=>$unidad,
						"valor"=>$valor,
						"porcentaje"=>$porcentaje
					);

					if($id_actividad==""){
						if($this->CPPmodel->checkActividad($actividad)){
							echo json_encode(array('res'=>"error", 'msg' => "Esta actividad ya se encuentra asignada a la aplicación."));exit;	
						}

						if($this->CPPmodel->formActividad($data_insert)){
							echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{
						$data_mod=array(
							"id_proyecto_tipo"=>$id_proyecto_tipo,
							"actividad"=>$actividad,
							"unidad"=>$unidad,
							"valor"=>$valor,
							"porcentaje"=>$porcentaje
					    );	

			   			if($this->CPPmodel->modFormActividad($id_actividad,$data_mod)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					    }
					}
	    		}	
			}
		}

		public function getDataActividad(){
			if($this->input->is_ajax_request()){
				sleep(1);
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->CPPmodel->getDataActividad($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function listaActividad(){
			$empresa=$this->security->xss_clean(strip_tags($this->input->get_post("empresa")));
			echo json_encode($this->CPPmodel->listaActividad($empresa));
		}

		public function deleteActividad(){
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		    if($this->CPPmodel->deleteActividad($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function excelInforme(){
			$empresa=$this->uri->segment(2);

			if($empresa=="0"){
				$empresa="";
			}
			
			$dato=$this->CPPmodel->listaActividad($empresa);

			$nombre="Lista de Actividades-".".xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");

			?>
			<style type="text/css">
			.head{height: 30px; background-color:#0CC243;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
			td{text-align:center;   vertical-align:middle;}
			</style>
			<table align='center' border="1"> 
	        <thead>
	        <tr style="background-color:#F9F9F9">
	              <th class="head">Proyecto Empresa</th>
				  <th class="head">Proyecto Tipo</th>
                  <th class="head">Actividad</th>
                  <th class="head">Unidad</th>
                  <th class="head">Valor</th>
                  <th class="head">Porcentaje</th>
	        </tr>
	        </thead>	
			<tbody>
	        <?php 
	        	if($dato !=FALSE){
	      		foreach($dato as $sop){
	      			?>
	      			 <tr>
						 <td><?php echo utf8_decode($sop["proyecto"]); ?></td>
						 <td><?php echo utf8_decode($sop["proyecto_tipo"]); ?></td>
						 <td><?php echo utf8_decode($sop["actividad"]); ?></td>
						 <td><?php echo utf8_decode($sop["unidad"]); ?></td>
						 <td><?php echo utf8_decode($sop["valor"]); ?></td>
						 <td><?php echo utf8_decode($sop["porcentaje"]); ?></td>
					 </tr>
	      			<?php
	      		}
	      		}
	          ?>
	        </tbody>
        </table>
	    <?php

		}


	/********MANTENEDOR USUARIOS *********/

		public function getMantUsView(){
	    	$fecha_hoy=date('Y-m-d');
	    	$perfiles=$this->CPPmodel->getPerfiles();
	    	$idUsuarioCPP=$this->session->userdata('idUsuarioCPP');
			$id_perfil_CPP=$this->session->userdata('id_perfil_CPP');
			$supervisores=$this->CPPmodel->getSupervisores();
		    $datos = array(
		       'fecha_hoy' => $fecha_hoy,
		       'idUsuarioCPP' => $idUsuarioCPP,
		       'id_perfil_CPP' => $id_perfil_CPP,
		       'perfiles' => $perfiles,
		       'supervisores' => $supervisores
			);  
			$this->load->view('back_end/cpp/mantenedor_usuarios',$datos);
		}

		public function getUsuariosSel2(){
		    echo $this->CPPmodel->getUsuariosSel2();exit;
		}

		public function listaMantUsuarios(){
			echo json_encode($this->CPPmodel->listaMantUsuarios());exit;
		}

		public function formMantUs(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$id_mant_us=$this->security->xss_clean(strip_tags($this->input->post("id_mant_us")));
				$usuario=$this->security->xss_clean(strip_tags($this->input->post("usuario")));
				$perfil=$this->security->xss_clean(strip_tags($this->input->post("perfil")));
				$supervisor=$this->security->xss_clean(strip_tags($this->input->post("supervisor")));
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuarioCPP")." ".$this->session->userdata("apellidosUsuarioCPP");

				if ($this->form_validation->run("formMantUs") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					if($perfil==4){
						if($supervisor==""){
							echo json_encode(array('res'=>"error", 'msg' => "Debe asignarle un supervisor a este usuario."));exit;	
						}
					}else{
						$supervisor=0;
					}

					$data_insert=array("id_usuario"=>$usuario,
						"id_perfil"=>$perfil,
						"id_supervisor"=>$supervisor,
						"ultima_actualizacion"=>$ultima_actualizacion
					);	

					if($id_mant_us==""){
						if($this->CPPmodel->checkUsuario($usuario)){
							echo json_encode(array('res'=>"error", 'msg' => "Este usuario ya se encuentra asignado a la aplicación."));exit;	
						}

						if($this->CPPmodel->formMantUs($data_insert)){
							echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
						}else{
							echo json_encode(array('res'=>"ok", 'msg' => ERROR_MSG));exit;
						}
				   
					}else{

						if($perfil==4){
							if($supervisor==""){
								echo json_encode(array('res'=>"error", 'msg' => "Debe asignarle un supervisor a este usuario."));exit;	
							}
						}else{
							$supervisor=0;
						}

						if($this->CPPmodel->checkUsuarioMod($id_mant_us,$usuario)){
							echo json_encode(array('res'=>"error", 'msg' => "Este usuario ya se encuentra asignado a la aplicación."));exit;	
						}
						$data_mod=array("id_usuario"=>$usuario,
							"id_perfil"=>$perfil,
							"id_supervisor"=>$supervisor,
							"ultima_actualizacion"=>$ultima_actualizacion
					    );	

			   			if($this->CPPmodel->modFormMantUs($id_mant_us,$data_mod)){
							echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
					    }
					}
	    		}	
			}
		}

		public function getDataMantUs(){
			if($this->input->is_ajax_request()){
				$this->checkLogin();
				$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
				$data=$this->CPPmodel->getDataMantUs($hash);
				if($data){
					echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
				}else{
					echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				}	
			}else{
				exit('No direct script access allowed');
			}
		}

		public function eliminaUsuario(){
			$this->checkLogin();
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		    if($this->CPPmodel->eliminaUsuario($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function excelusuarios(){
	    	$nombre="reporte-usuarios.xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
				.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
				td{font-size:12px;vertical-align:middle;}
			</style>
		    <h3>Usuarios CPP</h3>
		        <table align='center' border="1"> 
			        <thead>
			        <tr style="background-color:#F9F9F9">
			          <th>Usuario</th>
			          <th>RUT</th>
			          <th>Cargo</th>
			          <th>Empresa</th>
			          <th>Perfil</th>
			          <th>Supervisor</th>
			          <th>&Uacute;ltima actualizaci&oacute;n</th>
			        </tr>
			        </thead>	
					<tbody>
			        <?php 
			        $usuarios=$this->CPPmodel->listaMantUsuarios();
			        	if($usuarios !=FALSE){
			      		foreach($usuarios as $us){
			      			?>
			      			 <tr>
								 <td><?php echo utf8_decode($us["usuario"]); ?></td>
								 <td><?php echo utf8_decode($us["rut"]); ?></td>
								 <td><?php echo utf8_decode($us["cargo"]); ?></td>
								 <td><?php echo utf8_decode($us["empresa"]); ?></td>
								 <td><?php echo utf8_decode($us["perfil"]); ?></td>
								 <td><?php echo utf8_decode($us["supervisor"]); ?></td>
								 <td><?php echo utf8_decode($us["ultima_actualizacion"]); ?></td>
							
							 </tr>
			      			<?php
			      		}
			      		}
			          ?>
			        </tbody>
		        </table>
		    <?php
		}





		
}