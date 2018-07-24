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

			$pass=sha1($this->security->xss_clean(strip_tags($this->input->post("pass"))));
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

	public function resetPass(){
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

	}

	function updatePass(){

	}*/

	/*public function changepass(){
		if(!$this->session->userdata("rutUserCPP")){
			redirect("login");
		}
		$datos = array(
        'titulo' => "Cambiar Contrase&ntilde;a",
        'contenido' => "home/changepass",
	    'header'=>"index"
       	);  
		$this->load->view('plantillas/plantilla_front_end',$datos);
	}

}