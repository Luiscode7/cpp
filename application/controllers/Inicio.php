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

	public function resetpass(){
		if($this->input->is_ajax_request()){
			$email=$this->security->xss_clean(strip_tags($this->input->post("correo")));

			if($this->form_validation->run('resetpass') == FALSE){
				echo json_encode(array('res'=>"error", 'msg' => strip_tags(validation_errors())));exit;
			}else{
				
				$result=$this->Iniciomodel->getCorreo($email);
				if($result){
					$this->recuperarpass($correo, $result);
					$this->load->view('back_end/resetpass', array('correo' => $email));
				}else{
					$this->load->view('back_end/resetpass', array('error' => "El Email no está registrado."));
				}
			}
		}else{
			$this->load->view('back_end/resetpass');
		}  
		
	}


	public function recuperarpass(){

					$this->load->library('email');
					$desdecorreo="luiseduardo.venegas7@gmail.com";
					$desdenombre="Luis Eduardo Venegas";
					$asunto="Recuperar contraseña de KM";
					$msg="Ud a solicitado recuperar la contraseña de su correo KM";
					$config = array(
					'protocol' => 'smtp',
					'smtp_port' => 578,
					'smtp_host' => "localhost",
  					'charset'  => 'utf-8',
  					'priority' => '1',
					'wordwrap' => TRUE
					);

					$this->email->initialize($config);

					$this->email->from($desdecorreo, $desdenombre);
					$this->email->to("luiseduardo.venegas7@gmail.com");
					//$this->email->bcc("copiaoculta");
					$this->email->subject($asunto);
					$this->email->message($msg);
					//$this->email->attach($archivo);
					$res=$this->email->send();
					/*echo json_encode(array("res" => 1));
					exit;*/

	}

	function generarPass($length=5,$uc=FALSE,$n=FALSE,$sc=FALSE){
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


	
}