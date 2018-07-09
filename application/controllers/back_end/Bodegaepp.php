<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bodegaepp extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Bodegaeppmodel");
		$this->load->model("back_end/Iniciomodel");
		/*if($_SERVER["HTTP_HOST"]!="localhost"){
			if(!$this->session->userdata("rutUser")){redirect("../unlogin");}
		}*/
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function visitas(){
		$data=array("usuario"=>$this->session->userdata('nombresUsuario')." ".$this->session->userdata('apellidosUsuario'),
     	"fecha"=>date("Y-m-d G:i:s"),
    	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
    	"ip"=>$this->input->ip_address(),
    	"pagina"=> "Splice movistar Bodega Uniformes"
    	);
    	$this->Iniciomodel->insertarVisita($data);
	}

	public function getFerreteriaView(){
		$this->visitas();
		$datos=array();
		$this->load->view('back_end/bodega_epp_ropa/ferreteria',$datos);
	}

/*********DETALLE MOVIMIENTOS******/
	
    public function getDetalleMovimientos(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('Y-m-d');
	    	$lista_tipo_mov=$this->Bodegaeppmodel->getTipoMovListFull();

			$datos=array(	
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
		        'lista_tipo_mov' => $lista_tipo_mov,
		    );
			$this->load->view('back_end/bodega_epp_ropa/detalle_movimientos',$datos);
		}
	}

	public function getDetalleMovimientosList(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$tipo_mov=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
		$usuario=$this->security->xss_clean(strip_tags($this->input->get_post("usuario")));

		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
		echo json_encode($this->Bodegaeppmodel->getDetalleMovimientosList($desde,$hasta,$tipo_mov,$usuario));
	}
	
	public function eliminarMovimientos(){
		if($this->Bodegaeppmodel->eliminarMovimientos()){
	      echo json_encode(array("res" => "ok" , "msg" => "Registros eliminados correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando los registro, intente nuevamente."));
	    }
	}

	public function excel_detalle_mov(){
		$desde=$this->uri->segment(2);
		$hasta=$this->uri->segment(3);
		$tipo_mov=$this->uri->segment(4);
		$usuario=$this->uri->segment(5);

		if($tipo_mov=="-"){$tipo_mov="";}
		if($usuario=="-"){$usuario="";}
		$data=$this->Bodegaeppmodel->getDetalleMovimientosList($desde,$hasta,$tipo_mov,$usuario);

		$tipo_mov_str=$this->Bodegaeppmodel->getTipoMovStr($tipo_mov);

		$nombre="detalle_movimientos_".utf8_decode($tipo_mov_str)."_".$desde."_".$hasta.".xls";
		header("Content-type: application/vnd.ms-excel;  charset=utf-8");
		header("Content-Disposition: attachment; filename=$nombre");
		?>
		<style type="text/css">
		.head{height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
		td{text-align:center;   vertical-align:middle;}
		</style>
		<table align='center' border="1"> 
	        <thead>
	        <tr style="background-color:#F9F9F9">
	              <th class="head">Tipo Movimiento</th>    
	              <th class="head">Numero documento</th>    
	              <th class="head">Nombre</th>    
	              <th class="head">Bodega </th>    
	              <th class="head">C&oacute;digo</th>    
	              <th class="head">Descripci&oacute;n</th>    
	              <th class="head">Cantidad</th>    
	              <th class="head">Fecha</th>    
	              <th class="head">Hora</th>    
	              <th class="head">Observaciones</th>    
	        </tr>
	        </thead>	
			<tbody>
	        <?php 
	        	if($data !=FALSE){
	      		foreach($data as $dato){
	      			?>
	      			 <tr>
						 <td><?php echo utf8_decode($dato["tipo_mov"]); ?></td>
						 <td><?php echo $dato["num_doc"]; ?></td>
						 <td><?php echo utf8_decode($dato["nombre_completo"]); ?></td>
						 <td><?php echo $dato["bodega"]; ?></td>
						 <td><?php echo $dato["codigo"]; ?></td>
						 <td><?php echo utf8_decode($dato["descripcion"]); ?></td>
						 <td><?php echo $dato["cantidad"]; ?></td>
						 <td><?php echo $dato["fecha"]; ?></td>
						 <td><?php echo $dato["hora"]; ?></td>
						 <td><?php echo utf8_decode($dato["obs"]); ?></td>
					 </tr>
	      			<?php
	      		}
	      		}
	          ?>
	        </tbody>
        </table>
    <?php
	}


/*********CONSULTA MOVIMIENTOS******/
	
    public function getConsultaMovimientos(){
		if($this->input->is_ajax_request()){
			$fecha_anio_atras=date('Y-m-d', strtotime('-365 day', strtotime(date("d-m-Y"))));
	    	$fecha_hoy=date('Y-m-d');
			$datos=array(	
				'fecha_anio_atras' => $fecha_anio_atras,	   
		        'fecha_hoy' => $fecha_hoy,
		    );
			$this->load->view('back_end/bodega_epp_ropa/stock_terreno',$datos);
		}
	}

	public function getConsultaMovimientosList(){
		$desde=$this->security->xss_clean(strip_tags($this->input->get_post("desde")));
		$hasta=$this->security->xss_clean(strip_tags($this->input->get_post("hasta")));
		$usuario=$this->security->xss_clean(strip_tags($this->input->get_post("usuario")));
		if($desde!=""){$desde=date("Y-m-d",strtotime($desde));}else{$desde="";}
		if($hasta!=""){$hasta=date("Y-m-d",strtotime($hasta));}else{$hasta="";}	
		echo json_encode($this->Bodegaeppmodel->getConsultaMovimientosList($desde,$hasta,$usuario));
	}

	public function getUsuariosSelcm(){
       echo $this->Bodegaeppmodel->getUsuariosSelcm();exit;
	}


/*********MOVIMIENTO MATERIALES ******/	

	public function getMovimientoMaterialesView(){
		$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('Y-m-d');
		$lista_tipo_mov=$this->Bodegaeppmodel->getTipoMovList();
		$datos=array(	
			'fecha_anio_atras' => $fecha_anio_atras,	   
	        'fecha_hoy' => $fecha_hoy,
	        "lista_tipo_mov" => $lista_tipo_mov
	    );
		$this->load->view('back_end/bodega_epp_ropa/movimiento_materiales',$datos);
	}

	public function getUsuariosSel(){
	   $tipo_mov=$this->security->xss_clean(strip_tags($this->input->get_post("tipo_mov")));
       echo $this->Bodegaeppmodel->getUsuariosSel($tipo_mov);exit;
	}

	public function getMaterialesSel(){
       echo $this->Bodegaeppmodel->getMaterialesSel();exit;
	}

	public function formMovMateriales(){
		if($this->input->is_ajax_request()){
			$numero=$this->security->xss_clean(strip_tags($this->input->post("numero_mov")));
			$fecha=$this->security->xss_clean(strip_tags($this->input->post("fecha_mov")));
			$hora=date("G:i:s");
			$tipo_mov=$this->security->xss_clean(strip_tags($this->input->post("tipo_mov")));
			$usuario_mov=$this->security->xss_clean(strip_tags($this->input->post("usuario_mov")));
			$observaciones=$this->security->xss_clean(strip_tags($this->input->post("observaciones_mov")));

			if($this->Bodegaeppmodel->checkDocumento($numero)){
				echo json_encode(array('res'=>"error", 'msg' => "Ya existe una guía con este número"));exit;
			}
			if ($this->form_validation->run("formMovMateriales") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	

				if($tipo_mov==3){//SI ES DEVOLUCION
					
					$data=array("id_tipo_movimiento"=>$tipo_mov,
							"numero_documento"=>$numero,
							"fecha"=>$fecha,
							"hora"=>$hora,
							"rut_tecnico"=>$usuario_mov,
							"rut_usuario"=>$this->session->userdata('rutUsuario'),
							"observaciones"=>$observaciones,
							"estado"=>"0",
					);	

					$insert_id=$this->Bodegaeppmodel->formMovMateriales($data);
					if($insert_id!=FALSE){
	    				echo json_encode(array('res'=>"ok", 'msg' => OK_MSG, 'insert_id' => $insert_id,'tipo' => "dev",'vista' => $this->getListaDetalleMov($insert_id,0,$usuario_mov)));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				    }

				}else{

					$data=array("id_tipo_movimiento"=>$tipo_mov,
							"numero_documento"=>$numero,
							"fecha"=>$fecha,
							"hora"=>$hora,
							"rut_tecnico"=>$usuario_mov,
							"rut_usuario"=>$this->session->userdata('rutUsuario'),
							"observaciones"=>$observaciones,
							"estado"=>"0",
					);	
					$insert_id=$this->Bodegaeppmodel->formMovMateriales($data);
					if($insert_id!=FALSE){
	    				echo json_encode(array('res'=>"ok", 'msg' => OK_MSG, 'insert_id' => $insert_id,'rut_usuario'=>$usuario_mov));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				    }

				}
			
			}	

		}else{
			exit('No direct script access allowed');
		}
	}

	public function formDetMateriales(){
		if($this->input->is_ajax_request()){
			$id_mov=$this->security->xss_clean(strip_tags($this->input->post("id_mov")));
			$rut_usuario=$this->security->xss_clean(strip_tags($this->input->post("rut_usuario")));
			$material=$this->security->xss_clean(strip_tags($this->input->post("materiales_mov")));
			$cantidad=$this->security->xss_clean(strip_tags($this->input->post("cantidad_det_mov")));
			if ($this->form_validation->run("formDetMateriales") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	
				$data=array("id_bod_mov_mat"=>$id_mov,
						"id_material"=>$material,
						"cantidad"=>$cantidad
			    );

				$tipo_mov=$this->Bodegaeppmodel->getTipoMov($id_mov);
				if($tipo_mov!=FALSE){
					if($this->checkStock($tipo_mov,$material,$cantidad)){
						if($this->Bodegaeppmodel->formDetMateriales($data)){
							echo json_encode(array('res'=>"ok", 'msg' => OK_MSG, 'vista' => $this->getListaDetalleMov($id_mov,1,$rut_usuario)));exit;
						}else{
							echo json_encode(array('res'=>"error", 'msg' => "Valor no válido."));exit;
						}
					}else{
						echo json_encode(array('res'=>"error", 'msg' => "Valor no válido."));exit;
					}			
				}
			
			}	

		}else{
			exit('No direct script access allowed');
		}
	}

	public function checkStock($tipo,$mat,$cantidad){
		if($tipo==2){//si es salida de material
			$stock=$this->Bodegaeppmodel->checkStock($mat);//devuelve el stock del material, o falso si no se a ingresado
			//echo $stock;
			if($stock>0){//si existe stock ingresado para este material
				if($stock!=FALSE){//si es mayor a cero
					if($stock>=$cantidad){//se compara el stock actual con el que esta saliendo, si es mayor
						return TRUE;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => "El stock es menor al solicitado : ".$stock." actual en bodega."));exit;
					}
				}else{
					echo json_encode(array('res'=>"error", 'msg' => "Este material no tiene stock ingresado para bodega."));exit;
				}
			}else{
					echo json_encode(array('res'=>"error", 'msg' => "Este material tiene stock 0."));exit;
			}
		}else{
			return TRUE;//continua para ingresar detalle.
		}
		
	}

	public function getStockGereralBodega(){
		$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('Y-m-d');
		$datos = array(
	       'fecha_anio_atras' => $fecha_anio_atras,	   
	       'fecha_hoy' => $fecha_hoy
		);  
		$this->load->view('back_end/bodega_epp_ropa/stock_bodega', $datos);
	}

	public function getStockGeneralList(){
		echo json_encode($this->Bodegaeppmodel->getStockGeneralList());
	}

	public function getListaDetalleMov($id_mov,$tipo,$rut){
		$datos=array("tipo" => $tipo,"id_mov" => $id_mov, "rut" => $rut, "fecha_hoy" => date("Y-m-d"));
		return $this->load->view('back_end/bodega_epp_ropa/lista_detalle_mov_mat', $datos, TRUE);
	}

	public function getListaDetalleMovList(){
		$id_mov=$this->security->xss_clean(strip_tags($this->input->get_post("id_mov")));
		$tipo=$this->security->xss_clean(strip_tags($this->input->get_post("tipo")));
		$rut=$this->security->xss_clean(strip_tags($this->input->get_post("rut")));
		echo json_encode($this->Bodegaeppmodel->getListaDetalleMovList($id_mov,$tipo,$rut));
	}

	public function eliminaDetMod(){
		$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
		/*$id_bod_mov_mat=$this->Bodegaeppmodel->getIdMovMatGuia($id);
		$id_material=$this->Bodegaeppmodel->getMaterialIdDetalle($id);*/
	    if($this->Bodegaeppmodel->eliminaDetMod($id)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function eliminaDetModMat(){
		$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
		/*$id_bod_mov_mat=$this->Bodegaeppmodel->getIdMovMatGuia($id);
		$id_material=$this->Bodegaeppmodel->getMaterialIdDetalle($id);*/
	    if($this->Bodegaeppmodel->eliminaDetModMat($id)){
	      echo json_encode(array("res" => "ok" , "msg" => "Registro eliminado correctamente."));
	    }else{
	      echo json_encode(array("res" => "error" , "msg" => "Problemas eliminando el registro, intente nuevamente."));
	    }
	}

	public function devuelveCantidad(){
		$id_mov=$this->security->xss_clean(strip_tags($this->input->post("id_mov")));
		$cantidad=$this->security->xss_clean(strip_tags($this->input->post("c")));
		$tipo=$this->security->xss_clean(strip_tags($this->input->post("t")));
		$newc=$this->security->xss_clean(strip_tags($this->input->post("newc")));
		$material=$this->security->xss_clean(strip_tags($this->input->post("id_material")));
		$id_bod_mov_mat=$this->security->xss_clean(strip_tags($this->input->post("id_bod_mov_mat")));
		$rut_usuario=$this->security->xss_clean(strip_tags($this->input->post("rut_usuario")));

		if($newc<=$cantidad){
			 $data=array("id_bod_mov_mat" => $id_mov,
			 			 "id_material" => $material,
			 			 "cantidad" => $newc);
			 if($this->Bodegaeppmodel->devuelveCantidad($data)){
		      echo json_encode(array("res" => "ok" , "msg" => "Registro modificado correctamente."));
		    }else{
		      echo json_encode(array("res" => "error" , "msg" => "Problemas modificando el registro, intente nuevamente."));
		    }
		}else{
			echo json_encode(array("res" => "error" , "msg" => "La cantidad no puede ser mayor al stock."));
		}
	}

	public function truncate(){
		$this->Bodegaeppmodel->truncate();
	}

	public function getOrdenByNum(){
		//sleep(2);
		$num_doc=$this->security->xss_clean(strip_tags($this->input->post("num_doc")));
		$id_os=$this->Bodegaeppmodel->getIdMovByNumDoc($num_doc);
		$rut=$this->Bodegaeppmodel->getRutIdMov($id_os);
		$tipo=$this->Bodegaeppmodel->getTipoMovFromIdMov($id_os);
		$data=$this->Bodegaeppmodel->getDataOsById($id_os);
		if($tipo==3){
			if($data){
		        echo json_encode(array("res" => "ok" , "data" => $data,'tipo' => "dev", "vista" => $this->getListaDetalleMov($id_os,0,$rut)));exit;
			}else{
		        echo json_encode(array("res" => "error" , "msg" => ""));exit;
			}

		}else{

			if($data){
		        echo json_encode(array("res" => "ok" , "data" => $data,'tipo' => "", "vista" => $this->getListaDetalleMov($id_os,1,$rut)));exit;
			}else{
		        echo json_encode(array("res" => "error" , "msg" => ""));exit;
			}
		}

	}

	public function imprimeGuia(){
		//sleep(1);
		$id=$this->security->xss_clean(strip_tags($this->input->get_post("id")));
		$tipo=$this->Bodegaeppmodel->getTipoMovFromIdMov($id);
		$rut=$this->Bodegaeppmodel->getRutIdMov($id);;
		$num_doc=$this->Bodegaeppmodel->geNumDocById($id);
		$nombre="guia_despacho_".$num_doc.".pdf";
		$this->load->library('html2pdf');
        $this->createFolder();
        $this->html2pdf->folder('./impresiones_EPP/guias_despacho/');
        $this->html2pdf->filename($nombre);
        $this->html2pdf->paper('a4', 'portrait');
        $data = array(
	       'cabecera' => $this->Bodegaeppmodel->getDataOsById($id),
	       'listado' => $this->Bodegaeppmodel->getListaDetalleMovList($id,$tipo,$rut),
        );
        $this->html2pdf->html(utf8_decode($this->load->view('back_end/bodega_epp_ropa/guia_despacho_pdf', $data, true)));        
        $route = base_url("impresiones_EPP/guias_despacho/".$nombre); 
        if($this->html2pdf->create('save')) {
          echo $nombre;
        }else{
          return FALSE;
        }
	}

	private function createFolder(){
        if(!is_dir("./impresiones_EPP/guias_despacho")) {
            mkdir("./impresiones_EPP/guias_despacho", 0777);
        }
    }


/***********MANTENEDOR MATERIALES*******************/

	public function getMantenedorMateriales(){
		$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('Y-m-d');
    	$tipos_epp=$this->Bodegaeppmodel->getTiposEpp();
		$datos = array(
	       'fecha_anio_atras' => $fecha_anio_atras,	   
	       'fecha_hoy' => $fecha_hoy,
	       'tipos_epp' => $tipos_epp
		);  
		$this->load->view('back_end/bodega_epp_ropa/mantenedor_materiales',$datos);
	}

	public function getListaMateriales(){
		$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
		if($estado!=""){
			$estado = ($estado == "0") ? "VERDADERO" : "FALSO";
		}
		echo json_encode($this->Bodegaeppmodel->getListaMateriales($estado));
	}

	public function getMaterialesSelect2(){
       echo $this->Bodegaeppmodel->getMaterialesSelect2();exit;
	}


	/*public function csv(){
	    $tipos=$this->Bodegaeppmodel->getTiposEPP();

        $handle = fopen("./Listado_epp.csv", "r");
        $cont=0;
        while (($data = fgetcsv($handle, 9999, ";")) !== FALSE) {
	        
	        if(!empty($data[1])){
	 	        $tipo=$data[1];

       			foreach($tipos as $t){
       				$tipo_bd=$t["tipo"];

       				if($tipo_bd==$tipo){
       					$id_tipo=$t["id"];
       						
						$data_insert=array("codigo"=>$data[0],
							"tipo"=>$id_tipo,
							"descripcion"=>$data[2],
							"estado_bodega"=>"VERDADERO",
							"ultima_actualizacion"=>"Sistema");

						if($this->Bodegaeppmodel->insertaMaterial($data_insert)){
							$cont++;
						}
						$data_insert=array();
       					
       				}
	       		}

	        }
        }
        echo "<br>";
        echo "filas insertadas :".$cont;
	}*/

    public function nuevoMaterial(){
		if($this->input->is_ajax_request()){
			sleep(1);
			$hash=$this->security->xss_clean(strip_tags($this->input->post("hash")));
			$tipo=$this->security->xss_clean(strip_tags($this->input->post("tipo")));
			$codigo=$this->security->xss_clean(strip_tags($this->input->post("codigo")));
			$descripcion_mat=$this->security->xss_clean(strip_tags($this->input->post("descripcion_mat")));
			$estadob_mat=$this->security->xss_clean(strip_tags($this->input->post("estadob_mat")));

			$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");
			
			if ($this->form_validation->run("nuevoMaterialEPP") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	
				$data=array("codigo"=>$codigo,
						"tipo"=>$tipo,
						"descripcion"=>$descripcion_mat,
						"estado_bodega"=>$estadob_mat,
						"ultima_actualizacion"=>$ultima_actualizacion
					  );	

				if($hash==""){
					if($this->Bodegaeppmodel->nuevoMaterial($data)){
	    			echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				    }
				}else{
					if($this->Bodegaeppmodel->modMaterial($hash,$data)){
	    			echo json_encode(array('res'=>"ok", 'msg' => MOD_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				    }
				}
			}	

		}else{
			exit('No direct script access allowed');
		}
	}

	public function getDataMat(){
		if($this->input->is_ajax_request()){
			sleep(1);
			$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
			$data=$this->Bodegaeppmodel->getDataMat($id);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}


	public function excelMat(){
		$estado=$this->uri->segment(2);
		if($estado!="-"){
			$estado = ($estado == "0") ? "VERDADERO" : "FALSO";
		}else{
			$estado="";
		}
		$data=$this->Bodegaeppmodel->getListaMateriales($estado);
		$nombre="Materiales_EPP".date("Y-m-d").".xls";
		header("Content-type: application/vnd.ms-excel;  charset=utf-8");
		header("Content-Disposition: attachment; filename=$nombre");
		?>
		<style type="text/css">
		.head{height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
		td{text-align:center;   vertical-align:middle;}
		</style>
		<table align='center' border="1"> 
	        <thead>
	             <th class="head">C&oacute;digo</th>    
	             <th class="head">Tipo</th>    
	             <th class="head">Descripci&oacute;n</th>
	             <th class="head">Estado</th>
	             <th class="head">&Uacute;ltima actualizaci&oacute;n</th>
	        </tr>
	        </thead>	
			<tbody>
	        <?php 
	        	if($data !=FALSE){
	      		foreach($data as $dato){
	      			?>
	      			 <tr>
						 <td><?php echo $dato["codigo"]; ?></td>
						 <td><?php echo utf8_decode($dato["tipo"]); ?></td>
						 <td><?php echo $dato["descripcion"]; ?></td>
						 <td><?php echo $dato["estado"]; ?></td>
						 <td><?php echo $dato["ultima_actualizacion"]; ?></td>
			
					 </tr>
	      			<?php
	      		}
	      		}
	          ?>
	        </tbody>
        </table>
    <?php
	}














	public function cerrarGuia(){
		if($this->input->is_ajax_request()){
			$id=$this->security->xss_clean(strip_tags($this->input->post("id")));
			$data=array("estado" => "1");
			if($this->Bodegaeppmodel->cerrarGuia($id,$data)){
				echo json_encode(array('res'=>"ok", 'msg' => 'Guía cerrada con éxito.'));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	
	public function excel_stock_b(){
		$data=$this->Bodegaeppmodel->getStockGeneralList();
		$nombre="stock_general_".date("Y-m-d").".xls";
		header("Content-type: application/vnd.ms-excel;  charset=utf-8");
		header("Content-Disposition: attachment; filename=$nombre");
		?>
		<style type="text/css">
		.head{height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
		td{text-align:center;   vertical-align:middle;}
		</style>
		<table align='center' border="1"> 
	        <thead>
	        <tr style="background-color:#F9F9F9">
	            <th class="head">C&oacute;digo</th>    
	            <th class="head">Descripci&oacute;n</th>    
	            <th class="head">Ingreso</th>    
	            <th class="head">Salida</th> 
	            <th class="head">Ingreso por devoluci&oacute;n</th> 
	            <!-- <th class="head">Consumo</th>  -->
	            <th class="head">Stock en bodega</th> 
	            <th class="head">Stock en terreno</th> 
	        </tr>
	        </thead>	
			<tbody>
	        <?php 
	        	if($data !=FALSE){
	      		foreach($data as $dato){
	      			?>
	      			 <tr>
						 <td><?php echo $dato["codigo"]; ?></td>
						 <td><?php echo utf8_decode($dato["descripcion"]); ?></td>
						 <td><?php echo $dato["ingreso_vtr"]; ?></td>
						 <td><?php echo $dato["salida_as"]; ?></td>
						 <td><?php echo $dato["ingreso_dev"]; ?></td>
						 <!-- <td><?php echo $dato["consumo"]; ?></td> -->
						 <td><?php echo $dato["stock_bodega"]; ?></td>
						 <td><?php echo $dato["stock_terreno"]; ?></td>
					 </tr>
	      			<?php
	      		}
	      		}
	          ?>
	        </tbody>
        </table>
    <?php
	}

	public function excel_cons_stock(){
		$desde=$this->uri->segment(2);
		$hasta=$this->uri->segment(3);
		$rut=$this->uri->segment(4);
		$data=$this->Bodegaeppmodel->getConsultaMovimientosList($desde,$hasta,$rut);
		$nombre="consulta_movimientos_".$rut."_".$desde."_".$hasta.".xls";
		header("Content-type: application/vnd.ms-excel;  charset=utf-8");
		header("Content-Disposition: attachment; filename=$nombre");
		?>
		<style type="text/css">
		.head{height: 30px; background-color:#1D7189;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
		td{text-align:center;   vertical-align:middle;}
		</style>
		<table align='center' border="1"> 
	        <thead>
	        <tr style="background-color:#F9F9F9">
	            <th class="head">Nombre</th>    
	            <th class="head">Bodega</th>     
	            <th class="head">C&oacute;digo </th>    
	            <th class="head">Descripci&oacute;n</th>    
	            <th class="head">Entregado</th> 
	            <th class="head">Devuelto</th> 
	            <!-- <th class="head">Consumo</th>  -->
	            <th class="head">Stock Usuario</th> 
	        </tr>
	        </thead>	
			<tbody>
	        <?php 
	        	if($data !=FALSE){
	      		foreach($data as $dato){
	      			?>
	      			 <tr>
						 <td><?php echo utf8_decode($dato["nombre_completo"]); ?></td>
						 <td><?php echo $dato["bodega"]; ?></td>
						 <td><?php echo $dato["codigo"]; ?></td>
						 <td><?php echo utf8_decode($dato["descripcion"]); ?></td>
						 <td><?php echo $dato["salida_as"]; ?></td>
						 <td><?php echo $dato["ingreso_dev"]; ?></td>
						 <!-- <td><?php echo $dato["consumo"]; ?></td> -->
						 <td><?php echo $dato["stock_terreno"]; ?></td>
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