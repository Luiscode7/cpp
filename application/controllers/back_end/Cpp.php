<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CPP extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		$this->load->model("back_end/Iniciomodel");
		$this->load->model("back_end/CPPmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas(){
		$data=array("usuario"=>$this->session->userdata('nombresUsuario')." ".$this->session->userdata('apellidosUsuario'),
     	"fecha"=>date("Y-m-d G:i:s"),
    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
    	"ip"=>$this->input->ip_address(),
    	"pagina"=> "Splice movistar Producción"
    	);
    	$this->Iniciomodel->insertarVisita($data);
	}

	/*********CPP******/
		public function getCPPInicio(){
			$this->visitas();
			$fecha_anio_atras=date('Y-m-d', strtotime('-30 day', strtotime(date("Y-m-d"))));
	    	$fecha_hoy=date('Y-m-d');
		    $datos = array(
		       'fecha_anio_atras' => $fecha_anio_atras,	   
		       'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/inicio',$datos);
		}

		public function getCPPView(){
	    	$fecha_hoy=date('Y-m-d');
	    	$proyecto_empresa=$this->CPPmodel->listaProyectoEmpresa();
		    $datos = array(
		       'proyecto_empresa'=>$proyecto_empresa,
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
	        echo json_encode($this->CPPmodel->listaCPP($desde,$hasta));
		}

		public function formCPP(){
			sleep(1);
			if($this->input->is_ajax_request()){
				$id_cpp=$this->security->xss_clean(strip_tags($this->input->post("id_cpp")));
				$actividad=$this->security->xss_clean(strip_tags($this->input->post("actividad")));
				$id_usuario=$this->session->userdata("id");
				$cantidad=$this->security->xss_clean(strip_tags($this->input->post("cantidad")));
				$fecha_finalizacion=$this->security->xss_clean(strip_tags($this->input->post("fecha_finalizacion")));
				$proyecto_desc=$this->security->xss_clean(strip_tags($this->input->post("proyecto_desc")));
				$comentarios=$this->security->xss_clean(strip_tags($this->input->post("comentarios")));
				$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");

				
				if ($this->form_validation->run("formCPP") == FALSE){
					echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
				}else{	

					$data_insert=array("id_actividad"=>$actividad,
						"id_usuario"=>$id_usuario,
						"id_supervisor"=>0,
						"id_digitador"=>$id_usuario,
						"fecha"=>$fecha_finalizacion,
						"cantidad"=>$cantidad,
						"proyecto_descripcion"=>$proyecto_desc,
						"estado"=>"0",
						"comentarios"=>$comentarios,
						"fecha_aprobacion"=>"0000-00-00",
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
							"id_usuario"=>$id_usuario,
							"id_supervisor"=>0,
							"id_digitador"=>$id_usuario,
							"fecha"=>$fecha_finalizacion,
							"cantidad"=>$cantidad,
							"proyecto_descripcion"=>$proyecto_desc,
							"estado"=>"0",
							"comentarios"=>$comentarios,
							"fecha_aprobacion"=>"0000-00-00",
							"fecha_digitacion"=>date("Y-m-d"),
							"ultima_actualizacion"=>$ultima_actualizacion
					    );	

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
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
		    if($this->CPPmodel->eliminaActividad($hash)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
		    }
		}

		public function excel(){
			$especialidad=$this->uri->segment(4);
			if($especialidad==0){
				$especialidad="";
			}
			$desde=$this->uri->segment(2);
			$hasta=$this->uri->segment(3);	
	    	$nombre_especialidad=$this->Produccionmodel->getEspecialidadNombre($especialidad);

	    	$nombre="Informes-".utf8_decode(url_title($nombre_especialidad))."-".date("d-m-Y",strtotime($desde))."-".date("d-m-Y",strtotime($hasta)).".xls";
			header("Content-type: application/vnd.ms-excel;  charset=utf-8");
			header("Content-Disposition: attachment; filename=$nombre");
			?>
			<style type="text/css">
			.head{font-size:13px;height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
			td{font-size:12px;text-align:center;   vertical-align:middle;}
			</style>
			<?php
			
		 	$detalle=$this->Produccionmodel->getListaDetalleListAllEsp($especialidad,$desde,$hasta);
		 	?>
			
		    <h3>Detalle de actividades por trabajo</h3>

		        <table align='center' border="1"> 
			        <thead>
			        <tr style="background-color:#F9F9F9">
			            <th>N&uacute;mero &oacute;rden</th>   
		                <th>Estado</th>    
		                <th>Foto</th>    
		                <th>Especialidad</th>    
		                <th>Tipo</th>    
		                <th>PB</th>    
			            <th>Direcci&oacute;n</th>    
			            <th>Comuna</th>    
			            <th>Agencia</th>
			            <th>Central</th>
			            <th>Fecha de ejecuci&oacute;n</th>
			            <th>Hora inicio</th>
			            <th>Hora t&eacute;rmino</th>
			            <th>Maestro</th>
			            <th>Ayudante 1</th>
			            <th>Ayudante 2</th>  
			            <th class="head">C&oacute;digo MO</th>   
			            <th class="head">Descripci&oacute;n MO</th>   
			            <th class="head">Valor MO</th>   
			            <th class="head">U.M MO</th>   
			            <th class="head">Cantidad MO</th>   
			            <th class="head">PB	MO</th>   
			            <th class="head">C&oacute;digo UO</th>   
			            <th class="head">Descripci&oacute;n UO</th>   
			            <th class="head">U.M UO</th>   
			            <th class="head">Cantidad UO</th>  
			            <th>Digitador</th>
						
						<th class="head">N Cubicaci&oacute;n OEMC</th>   
			            <th class="head">N&uacute;mero OEMC</th>   
			            <th class="head">Fecha OEMC</th>   
						<th class="head">Agencia OEMC</th>   
				        <th class="head">Central OEMC</th>   
				 	    <th class="head">Supervisor OEMC</th>  
				 	    <th class="head">Valor MO OEMC</th>  
				 	    <th class="head">Valor UO OEMC</th>	
				 	    <th class="head">PB Total OEMC</th>  
				 	    <th class="head">Actividad OEMC</th>	

			            <th>Observaciones</th>    
		  			    <th>&Uacute;ltima actualizaci&oacute;n</th> 
			        </tr>
			        </thead>	
					<tbody>
			        <?php 
			        	if($detalle !=FALSE){
			      		foreach($detalle as $det){
			      			?>
			      			 <tr>
								 <td><?php echo utf8_decode("N° ".$det["numero_orden"]); ?></td>
								 <td><?php echo utf8_decode($det["estado_str"]); ?></td>
								 <td><?php echo utf8_decode($det["foto"]); ?></td>
								 <td><?php echo utf8_decode($det["especialidad"]); ?></td>
								 <td><?php echo utf8_decode($det["tipo_orden"]); ?></td>
								 <td><?php echo utf8_decode($det["totalpb"]); ?></td>
								 <td><?php echo utf8_decode($det["direccion"]); ?></td>
								 <td><?php echo utf8_decode($det["comuna"]); ?></td>
								 <td><?php echo utf8_decode($det["oficina_central"]); ?></td>
								 <td><?php echo utf8_decode($det["sucursal"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_ingreso"]); ?></td>
								 <td><?php echo utf8_decode($det["hora_inicio"]); ?></td>
								 <td><?php echo utf8_decode($det["hora_termino"]); ?></td>
								 <td><?php echo utf8_decode($det["maestro"]); ?></td>
								 <td><?php echo utf8_decode($det["ayudante1"]); ?></td>
								 <td><?php echo utf8_decode($det["ayudante2"]); ?></td>

								 <td><?php echo utf8_decode($det["codigo_mano_obra"]); ?></td>
								 <td width="300px;"><?php echo utf8_decode($det["nombre_mano_obra"]); ?></td>
								 <td><?php echo utf8_decode($det["puntos_baremos"]); ?></td>
								 <td><?php echo utf8_decode($det["unidad_medida_mano_obra"]); ?></td>
								 <td><?php echo utf8_decode($det["cantidad_mano_obra"]); ?></td>
								 <td><?php echo utf8_decode($det["totalpb"]); ?></td>
								 <td><?php echo utf8_decode($det["codigo_unidad_obra"]); ?></td>
								 <td><?php echo utf8_decode($det["nombre_unidad_obra"]); ?></td>
								 <td><?php echo utf8_decode($det["unidad_unidad_medida"]); ?></td>
								 <td><?php echo utf8_decode($det["cantidad_unidad_obra"]); ?></td>

								 <td><?php echo utf8_decode($det["digitador"]); ?></td>
								 
								 <td><?php echo utf8_decode($det["n_cubicacion_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["numero_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["fecha_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["central_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["sucursal_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["supervisor"]); ?></td>
								 <td><?php echo utf8_decode($det["puntos_mo_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["puntos_uo_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["pb_total_oemc"]); ?></td>
								 <td><?php echo utf8_decode($det["actividad_oemc"]); ?></td>

								 <td><?php echo utf8_decode($det["observacion"]); ?></td>
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
		    $datos = array(
		       'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/vista_mensual',$datos);
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
		    $datos = array(
		       'fecha_hoy' => $fecha_hoy
			);  
			$this->load->view('back_end/cpp/mantenedor_usuarios',$datos);
		}
}