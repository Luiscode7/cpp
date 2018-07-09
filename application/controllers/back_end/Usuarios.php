<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("back_end/Usuariosmodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function getMantenedorUsuarios(){
		$datos=array(
			"perfiles"=>$this->Usuariosmodel->getPerfiles(),
			'areas' => $this->Usuariosmodel->getAreas(),
			'jefes' => $this->Usuariosmodel->getJefes(),
	        'cargos' => $this->Usuariosmodel->getCargos(),
	        'sucursales' => $this->Usuariosmodel->getSucursales(),
	        'proyectos' => $this->Usuariosmodel->getProyectos()
			);
		$this->load->view('back_end/usuarios/mantenedor_usuarios',$datos);
	}

	public function getUsuariosList(){
		$estado=$this->security->xss_clean(strip_tags($this->input->get_post("estado")));
		echo json_encode($this->Usuariosmodel->getUsuariosList($estado));
	}

	public function nuevoUsuario(){
		if($this->input->is_ajax_request()){
			$hash_rut=$this->security->xss_clean(strip_tags($this->input->post("hash_rut")));
			$rut=$this->security->xss_clean(strip_tags($this->input->post("rut")));
			$rut1=str_replace('.', '', $rut);
			$rut2=str_replace('.', '', $rut1);
			$rut=str_replace('-', '', $rut2);
			$primer_nombre=$this->security->xss_clean(strip_tags($this->input->post("primer_nombre")));
			$segundo_nombre=$this->security->xss_clean(strip_tags($this->input->post("segundo_nombre")));
			$apellido_paterno=$this->security->xss_clean(strip_tags($this->input->post("apellido_paterno")));
			$apellido_materno=$this->security->xss_clean(strip_tags($this->input->post("apellido_materno")));
			$correo=$this->security->xss_clean(strip_tags($this->input->post("correo")));
			$perfil=$this->security->xss_clean(strip_tags($this->input->post("perfil")));
			$estado=$this->security->xss_clean(strip_tags($this->input->post("estado")));
			$direccion=$this->security->xss_clean(strip_tags($this->input->post("direccion")));
			$comuna=$this->security->xss_clean(strip_tags($this->input->post("comuna")));
			$telefono=$this->security->xss_clean(strip_tags($this->input->post("telefono")));
			$celular=$this->security->xss_clean(strip_tags($this->input->post("celular")));
			$sexo=$this->security->xss_clean(strip_tags($this->input->post("sexo")));
			$area=$this->security->xss_clean(strip_tags($this->input->post("area")));
			$sucursal=$this->security->xss_clean(strip_tags($this->input->post("sucursal")));
			$cargo=$this->security->xss_clean(strip_tags($this->input->post("cargo")));
			$fecha_ingreso_km=$this->security->xss_clean(strip_tags($this->input->post("fecha_ingreso_km")));
			$fecha_nacimiento=$this->security->xss_clean(strip_tags($this->input->post("fecha_nacimiento")));
			$correo=$this->security->xss_clean(strip_tags($this->input->post("correo")));
			$celular_trabajo=$this->security->xss_clean(strip_tags($this->input->post("celular_trabajo")));
			$imagen="imagen";
			$jefe=$this->security->xss_clean(strip_tags($this->input->post("jefe")));
			$proyecto=$this->security->xss_clean(strip_tags($this->input->post("proyecto")));
			$contrasena=sha1($rut);
			$codigo=$this->security->xss_clean(strip_tags($this->input->post("codigo")));
			$nombres=$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");
			$fecha_mod=date("Y-m-d H:i");
			$fecha_actualizacion_datos=$fecha_mod." | ".$nombres;
			$contrasena_actualizada="";
			if($fecha_ingreso_km==""){$fecha_ingreso_km="0000-00-00";}		
			if($fecha_nacimiento==""){$fecha_nacimiento="0000-00-00";}	
			if($area==""){$area=0;}		
			if($sucursal==""){$sucursal=0;$ciudad="";}		
			if($sucursal==1 or $sucursal==2 or $sucursal==4 or $sucursal==7 or $sucursal==8 or $sucursal==10){$ciudad="Santiago";
			}elseif($sucursal==3){$ciudad="Coquimbo";
			}elseif($sucursal==11){$ciudad="La Serena";
			}elseif($sucursal==5){$ciudad="Talcahuano";
			}elseif($sucursal==6){$ciudad="";
			}elseif($sucursal==9){$ciudad="Calama";
			}else{$ciudad="Santiago";}
			if($cargo==""){$cargo=0;}		
			if($proyecto==""){$proyecto=0;}		
			if($jefe==""){$jefe=0;}	
			$ultima_actualizacion=date("Y-m-d G:i:s")." | ".$this->session->userdata("nombresUsuario")." ".$this->session->userdata("apellidosUsuario");
			
			if ($this->form_validation->run("nuevoUsuario") == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{	
				
				$data=array(
				"primer_nombre"=>$primer_nombre,
				"segundo_nombre"=>$segundo_nombre,
				"apellido_paterno"=>$apellido_paterno,
				"apellido_materno"=>$apellido_materno,
				"sexo"=>$sexo,"id_areakm"=>$area,
				"Direccion_domicilio"=>$direccion,
				"id_sucursal"=>$sucursal,
				"ciudad"=>$ciudad,
				"Comuna_domicilio"=>$comuna,
				"fono_domicilio"=>$telefono,
				"fono_celular"=>$celular,
				"correo"=>$correo,
				"id_cargo"=>$cargo,
				"fecha_nacimiento"=>$fecha_nacimiento,
				"fecha_ingreso_km"=>$fecha_ingreso_km,
				"celular_empresa"=>$celular_trabajo,
				"estado"=>$estado,
				"fecha_actualizacion_datos"=>$fecha_actualizacion_datos,
				"nombre_jefatura"=>$jefe,
				"id_proyecto"=>$proyecto,
				"contrasena"=>$contrasena,
				"contrasena_actualizada"=>$contrasena_actualizada,
				"fecha_salida_km"=>"0000-00-00",
				"cod_tecnico"=>$codigo,
				"perfil"=>$perfil);

				if($hash_rut==""){
					if($this->Usuariosmodel->existeRut($rut)){
						echo json_encode(array('res'=>"error", 'msg' => "Ya existe un usuario con este RUT."));exit;
					}
					$data["rut"]=$rut;
					if($correo!=""){
						if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
						  echo json_encode(array('res'=>"error", 'msg' => "Formato incorrecto para el correo."));exit;
						}
					}

					if(@$_FILES["userfile"]["name"]==""){
						$imagen2="";
					}else{
						$imagen=strtolower(url_title(convert_accented_characters($rut."-".date("H:i:s"))));
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						if($ext!="png" and $ext!="jpg" and $ext!="JPG"){echo json_encode(array('res'=>'error', 'msg' => 'Formato de imagen no soportado.'));exit;}
						if($ext=="JPG"){$imagen2=str_replace('JPG','',$imagen);$imagen2.='.jpg';}
						if($ext=="jpg"){$imagen2=str_replace('jpg','',$imagen);$imagen2.='.jpg';}
						if($ext=="jpeg"){$imagen2=str_replace('jpeg','',$imagen);$imagen2.='.jpg';}
						if($ext=="png"){$imagen2=str_replace('png','',$imagen);$imagen2.='.png';}

						$this->load->library('upload', $this->agrega_imagen($imagen2));
						if ($this->upload->do_upload()){	
					     	$this->load->library('image_lib', $this->agrega_miniatura("640","480",$imagen2));
							if (!$this->image_lib->resize()) {
								$imagen2="";
								echo json_encode(array('res'=>"error", 'msg' => $this->image_lib->display_errors()));exit;
							}else{
								$imagen2="fotosusuarios/".$imagen2;
							}
						}else{
							echo json_encode(array('res'=>"error", 'msg' => $this->upload->display_errors()));exit;
						}
					}
					$data["adjuntar_foto"]=$imagen2;
					$data["contrasena"]=sha1($rut);
					if($this->Usuariosmodel->nuevoUsuario($data)){
	    				echo json_encode(array('res'=>"ok", 'msg' => OK_MSG));exit;
					}else{
						echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
				    }

				}else{

					if($estado=="Activo"){
						$data["fecha_salida_km"]="0000-00-00";
					}else{
						$data["fecha_salida_km"]=$this->security->xss_clean(strip_tags($this->input->post("fecha_salida_km")));
					}

					if($correo!=""){
						if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
						  echo json_encode(array('res'=>"error", 'msg' => "Formato incorrecto para el correo."));exit;
						}
					}

					if(@$_FILES["userfile"]["name"]==""){

					}else{
						$imagen=strtolower(url_title(convert_accented_characters($rut."-".date("H:i:s"))));
						$path = $_FILES['userfile']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						if($ext!="png" and $ext!="jpg" and $ext!="JPG"){echo json_encode(array('res'=>'error', 'msg' => 'Formato de imagen no soportado.'));exit;}
						if($ext=="JPG"){$imagen2=str_replace('JPG','',$imagen);$imagen2.='.jpg';}
						if($ext=="jpg"){$imagen2=str_replace('jpg','',$imagen);$imagen2.='.jpg';}
						if($ext=="jpeg"){$imagen2=str_replace('jpeg','',$imagen);$imagen2.='.jpg';}
						if($ext=="png"){$imagen2=str_replace('png','',$imagen);$imagen2.='.png';}
						$data["adjuntar_foto"]=$imagen2;
						$this->load->library('upload', $this->agrega_imagen($imagen2));
						if ($this->upload->do_upload()){	
					     	$this->load->library('image_lib', $this->agrega_miniatura("640","480",$imagen2));
							if (!$this->image_lib->resize()) {
								echo json_encode(array('res'=>"error", 'msg' => strip_tags($this->image_lib->display_errors())));exit;
							}else{
								$imagen2="fotosusuarios/".$imagen2;
							}
						}else{
							echo json_encode(array('res'=>"error", 'msg' => strip_tags($this->upload->display_errors())));exit;
						}
					}

					
					//$data["contrasena"]=$this->security->xss_clean(strip_tags(sha1($this->input->post("contrasena"))));
					if($this->Usuariosmodel->modUsuario($hash_rut,$data)){
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

	public function agrega_imagen($imagen){
		$config['upload_path'] ='../mantenedor_usuarios/fotosusuarios';
		$config['allowed_types'] = 'jpg|png|JPG';
		$config['file_name'] = $imagen;
		$config['max_size']	= '5000'; 
		return $config;
	}	

	public function agrega_miniatura($width,$height,$nombre){
		$config["source_image"]='../mantenedor_usuarios/fotosusuarios/'.$nombre;
		$config['new_image'] = $nombre;
		$config["width"]=$width;
		$config["height"]=$height;
		$config["quality"]='100%';
		$config["maintain_ratio"]=TRUE;
		return $config;
	}

	public function getDataUsuario(){
		if($this->input->is_ajax_request()){
			sleep(1);
			$hash_rut=$this->security->xss_clean(strip_tags($this->input->post("hash_rut")));
			$data=$this->Usuariosmodel->getDataUsuario($hash_rut);
			if($data){
				echo json_encode(array('res'=>"ok", 'datos' => $data));exit;
			}else{
				echo json_encode(array('res'=>"error", 'msg' => ERROR_MSG));exit;
			}	
		}else{
			exit('No direct script access allowed');
		}
	}

	public function excel_us(){
		$estado=$this->uri->segment(2);
		if($estado=="1"){
          $estado="Activo";
        }else{
          $estado="No activo";
        }
		$nombre="reportedeusuarios_".date("d-m-Y").".xls";
        $usuarios = $this->Usuariosmodel->getUsuariosList($estado);
		header("Content-type: application/vnd.ms-excel;  charset=utf-8");
		header("Content-Disposition: attachment; filename=$nombre");
		?>
		<style type="text/css">
			.head{height: 30px; background-color:#1E748D;color:#fff; font-weight:bold;padding:10px;margin:10px;vertical-align:middle;}
	     	td{text-align:center;   vertical-align:middle;}
		</style>
		<table>	
		<tr style="background-color:#ccc;height:40px;text-align:left!important;font-weight:bold;">
	        <td class="head">Nombres</td>
	        <td class="head">Apellidos</td> 
	        <td class="head">Foto</td>
	        <td class="head">Rut</td>
	        <td class="head">Cargo</td>
	        <td class="head">Area KM</td>
	        <td class="head">Proyecto</td>
	        <td class="head">Jefatura</td>
	        <td class="head">C&oacute;digo</td>
	        <td class="head">Domicilio</td>
	        <td class="head">Comuna</td>
	        <td class="head">Ciudad</td>
	        <td class="head">Sucursal</td>
	        <td class="head">Fono</td>
	        <td class="head">Celular</td>
	        <td class="head">Celular Empresa</td>
	        <td class="head">Correo</td>
	        <td class="head">Fecha Nacimiento</td>
	        <td class="head">Fecha Ingreso</td>
	        <td class="head">Fecha Salida</td>
	        <td class="head">Perfil </td>
	        <td class="head">Estado</td>
	        <td class="head">&Uacute;ltima Actualizaci&oacute;n</td>
		</tr>
		<?php

			foreach($usuarios as $key){
			?>
			<tr>
		        <td><?php echo utf8_decode($key["nombres"]);?></td>
		        <td><?php echo utf8_decode($key["apellidos"]);?></td>
		        <td>
		        <?php 
		        if($key["foto"]!=""){
		           echo "Si";
		        }else{
		           echo "No";
		        }
		        ?>
		        </td>
		        <td><?php echo utf8_decode($key["rut"]);?></td>
		        <td><?php echo utf8_decode($key["cargo"]);?></td>
		        <td><?php echo utf8_decode($key["areakm"]);?></td>
		        <td><?php echo utf8_decode($key["proyecto"]);?></td>
		        <td><?php echo utf8_decode($key["jefe"]);?></td>
		        <td><?php echo utf8_decode($key["codigo"]);?></td>
		        <td><?php echo utf8_decode($key["direccion"]);?></td>
		        <td><?php echo utf8_decode($key["comuna"]);?></td>
		        <td><?php echo utf8_decode($key["ciudad"]);?></td>
		        <td><?php echo utf8_decode($key["sucursal"]);?></td>
		        <td><?php echo utf8_decode($key["telefono"]);?></td>
		        <td><?php echo utf8_decode($key["celular"]);?></td>
		        <td><?php echo utf8_decode($key["celular_empresa"]);?></td>
		        <td><?php echo utf8_decode($key["correo"]);?></td>
		        <td>
		        <?php 
		        if($key["fecha_nacimiento"]!="0000-00-00"){
		           echo $key["fecha_nacimiento"];
		        }else{
		           echo "";
		        }
		        ?>
		        </td>
		        <td>
		        <?php 
		        if($key["fecha_ingreso_km"]!="0000-00-00"){
		           echo $key["fecha_ingreso_km"];
		        }else{
		           echo "";
		        }
		        ?>
		        </td>
		         <td>
		        <?php 
		        if($key["fecha_salida_km"]!="0000-00-00"){
		           echo $key["fecha_salida_km"];
		        }else{
		           echo "";
		        }
		        ?>
		        </td>
		        <td><?php echo utf8_decode($key["perfil"]);?></td>
		        <td><?php echo utf8_decode($key["estado"]);?></td>
		        <td>
		        <?php 
		        if($key["fecha_actualizacion_datos"]!="0000-00-00"){
		           echo utf8_decode($key["fecha_actualizacion_datos"]);
		        }else{
		           echo "";
		        }
		        ?>
		        </td>
			</tr>
			<?php
			}
		?>
		</table>
		<?php
	}



}