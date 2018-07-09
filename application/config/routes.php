<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'inicio';
$route['404_override'] = "";
$route['nojs'] = "inicio/nojs";
$route['inicio'] = "inicio/index";
$route['login'] = "inicio/login";
$route['unlogin'] = "inicio/unlogin";
$route['loginProcess'] = "inicio/loginProcess";

$route[''] = "inicio/index";

/****** CPP*******/
	$route['getCPPInicio'] = "back_end/cpp/getCPPInicio";
	$route['getCPPView'] = "back_end/cpp/getCPPView";
	$route['listaCPP'] = "back_end/cpp/listaCPP";
	$route['getActividadesPorPe'] = "back_end/cpp/getActividadesPorPe";
	$route['formCPP'] = "back_end/cpp/formCPP";
	$route['eliminaActividad'] = "back_end/cpp/eliminaActividad";
	$route['getDataAct'] = "back_end/cpp/getDataAct";
	$route['getTiposPorPe'] = "back_end/cpp/getTiposPorPe";
	$route['getActividadesPorTipo'] = "back_end/cpp/getActividadesPorTipo";
		

	$route['getVistaMensualView'] = "back_end/cpp/getVistaMensualView";
	$route['getMantActView'] = "back_end/cpp/getMantActView";
	$route['getMantUsView'] = "back_end/cpp/getMantUsView";
	

/* End of file routes.php */
/* Location: ./application/config/routes.php */