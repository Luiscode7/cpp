<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->uri->segment("1")==""){
			redirect("inicio");
		}
		$this->load->model("back_end/Iniciomodel");
		$this->load->helper(array('fechas','str'));
		$this->load->library('user_agent');
	}

	public function nojs(){	
		$this->load->view('back_end/nojs');
	}

    public function acceso(){
		if(!$this->session->userdata("rutUserCPP")){redirect("login");}
	}

	public function checkLogin(){
		if($this->session->userdata('idUsuarioCPP')==""){
			echo json_encode(array('res'=>"sess"));exit;
		}
	}

	public function index(){	
		$this->acceso();
    	$fecha_anio_atras=date('d-m-Y', strtotime('-360 day', strtotime(date("d-m-Y"))));
    	$fecha_hoy=date('Y-m-d');
	    $datos = array(
	       'titulo' => "CPP",
	       'contenido' => "inicio",
	       'fecha_anio_atras' => $fecha_anio_atras,	   
	       'fecha_hoy' => $fecha_hoy
		);  
		$this->load->view('plantillas/plantilla_back_end',$datos);
	}	

	public function login(){
		$datos = array(
        'titulo' => "Inicio de sesión",
       	);  
		$this->load->view('back_end/login',$datos);
	}

	public function loginProcess(){
		if($this->input->is_ajax_request()){
			$rut=$this->security->xss_clean(strip_tags($this->input->post("usuario")));
			$rut1=str_replace('.', '', $rut);
			$rut2=str_replace('.', '', $rut1);
			$usuario=str_replace('-', '', $rut2);

			$pass=sha1(trim($this->security->xss_clean(strip_tags($this->input->post("pass")))));
			if(empty($usuario) or empty($pass)){
				echo json_encode(array("res" => "error",  "msg" => "Debe ingresar los datos." ,"usuario" => $pass));exit;
			}

			if ($this->Iniciomodel->login($usuario,$pass)==1) {
				$dataInsert=array("usuario" => $this->session->userdata('nombresUsuario')." ".$this->session->userdata('apellidosUsuario'),
	        	"fecha"=>date("Y-m-d G:i:s"),
	        	"navegador"=>"navegador :".$this->agent->browser()."\nversion :".$this->agent->version()."\nos :".$this->agent-> platform()."\nmovil :".$this->agent->mobile(),
	        	"ip"=>$this->input->ip_address(),
	        	"pagina"=> "CPP"
	        	);

				echo json_encode(array("res" => "ok",  "msg" => "Ingresando al sistema..." ));

			}elseif($this->Iniciomodel->login($usuario,$pass)==2){
				echo json_encode(array("res" => "error", "msg" => "El usuario no existe en la base de datos","usuario" => $pass));
			}
			elseif($this->Iniciomodel->login($usuario,$pass)==3){
				echo json_encode(array("res" => "error", "msg" => "Contrase&ntilde;a Incorrecta."));
			}
		}else{
			exit("No direct script access allowed");
		}
	}

	public function unlogin(){
		$this->session->sess_destroy();
		redirect("");
	}

	/*public function resetPass(){
		if($this->input->is_ajax_request()){
			if($this->form_validation->run('resetPass') == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{
				$email=$this->security->xss_clean(strip_tags($this->input->post("correo")));
				$result=$this->Iniciomodel->getCorreo($email);
				if($result){
					$this->enviarCorreo($email, $result);
					$this->load->view('back_end/resetpass', array('correo' => $email));
				}else{
					$this->load->view('back_end/resetpass', array('error' => "El Email no está registrado."));
				}
			}
		}else{
			$this->load->view('back_end/resetpass');
		}  
		
	}


	public function enviarCorreo($email,$nombre){

					$this->load->library('email');
					$this->email->set_mailtype('html');
					$desdecorreo="luiseduardo.venegas7@gmail.com";
					$desdenombre="Luis Eduardo Venegas";
					$asunto="Recuperar contraseña de KM";
					$msg='<p>Estimado' . " " . $nombre . ',</p>';
					$msg .='<p>Ud a solicitado recuperar la contraseña de su correo KM.
						 por favor haga <strong><a href="' .base_url() . 'resetPass'.'">Click Aqui</a></strong> para cambiar su contraseña</p>';
					$config = array(
  					'charset'  => 'utf-8',
  					'priority' => '1',
					'wordwrap' => TRUE
					);

					$this->email->initialize($config);

					$this->email->from($desdecorreo, $desdenombre);
					$this->email->to($email);
					//$this->email->bcc("copiaoculta");
					$this->email->subject($asunto);
					$this->email->message($msg);
					//$this->email->attach($archivo);
					$res=$this->email->send();
					echo json_encode(array("res" => 1));
					exit;

	}*/

	public function formRecuperarPass(){

		if($this->input->is_ajax_request()){
			
			$rut=$this->security->xss_clean(strip_tags($this->input->post("rut_rec")));
			$rut1=str_replace('.', '', $rut);
			$rut2=str_replace('.', '', $rut1);
			$usuario=str_replace('-', '', $rut2);

			if($usuario==""){echo json_encode(array("res" => "error",  "msg" => "Debe ingresar el usuario."));exit;}

			if($this->Iniciomodel->existeUsuario($usuario)){

				$correo=$this->Iniciomodel->getCorreoPorRut($usuario);
				if($correo=!FALSE){
					$this->enviaCorreoRecuperacion($usuario);	
					echo json_encode(array("res" => "ok",  "msg" => "Nueva contraseña enviada al correo : ".$this->Iniciomodel->getCorreoPorRut($usuario)));exit;
				}else{
					echo json_encode(array("res" => "error",  "msg" => "Debe ingresar el usuario."));exit;
				}
			}else{
				echo json_encode(array("res" => "error",  "msg" => "Debe ingresar un usuario válido."));exit;
			}
			
		}else{
			exit("No direct script access allowed");
		}
	}

	public function enviaCorreoRecuperacion($usuario){
		$this->load->library('email');
	    $config = array (
          'mailtype' => 'html',
          'charset'  => 'utf-8',
          'priority' => '1',
          'wordwrap' => TRUE
           );
	    $this->email->initialize($config);
	    $hash=$this->Iniciomodel->getHashFromRut($usuario);
	    $prueba=FALSE;
	    $data=$this->Iniciomodel->getUserData($hash);

	    foreach($data as $key){

		    if($prueba){
				$correo = array('ricardo.hernandez.esp@gmail.com');
			}else{
				$correo = $key["correo"];
			}

			$pass=$this->generarPass();
			$passsha1=sha1($pass);
			$data_pass=array("contrasena"=>$passsha1);

			$datos=array("datos"=>$data,"pass"=>$pass);
			$html=$this->load->view('back_end/recuperar_pass',$datos,TRUE);
			$this->email->from("reportes@km-t.cl","CPP");
			$this->email->to($correo);	
			$this->email->subject("Recuperación de contraseña CPP , ".$key["nombre"]."");
			$this->email->message($html); 
			$resp=$this->email->send();

			if ($resp) {
				$id=$this->Iniciomodel->getIdPorHash($hash);
				$this->Iniciomodel->actualizaPass($id,$data_pass);
				return TRUE;
			}else{
				return FALSE;
			}
		}

	}

	public function generarPass(){
		$length=5;
		$uc=FALSE;
		$n=FALSE;
		$sc=FALSE;

		$source = 'abcdefghijklmnopqrstuvwxyz';
		$source = '1234567890';
		if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($n==1) $source .= '1234567890';
		if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
		if($length>0){
			$rstr = "";
			$source = str_split($source,1);
			for($i=1; $i<=$length; $i++){
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,count($source));
				$rstr .= $source[$num-1];
			}

		}
		return $rstr;
	}

	public function actualizarPassForm(){
		if($this->input->is_ajax_request()){
			$this->checkLogin();
			$id_usuario=$this->session->userdata('idUsuarioCPP');
			$pass=$this->security->xss_clean(strip_tags($this->input->post("pass_us")));
			if($pass==""){echo json_encode(array("res" => "error",  "msg" => "Debe ingresar la contraseña."));exit;}
			if(strlen($pass)<4){echo json_encode(array("res" => "error",  "msg" => "La contraseña debe tener mínimo 4 caracteres."));exit;}

			$data=array("contrasena" => sha1($pass));
				
			if($this->Iniciomodel->actualizaContrasena($id_usuario,$data)){
				echo json_encode(array("res" => "ok",  "msg" => "Contraseña actualizada correctamente"));exit;
			}else{
				echo json_encode(array("res" => "error",  "msg" => "Problemas actualizando la contraseña, intente más tarde."));exit;
			}
			
		}else{
			exit("No direct script access allowed");
		}
	}

}