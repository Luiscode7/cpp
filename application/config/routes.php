<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'inicio';
$route['404_override'] = "";
$route['nojs'] = "inicio/nojs";
$route['inicio'] = "inicio/index";
$route['login'] = "inicio/login";
$route['unlogin'] = "inicio/unlogin";
$route['loginProcess'] = "inicio/loginProcess";
$route['recuperarpass'] = "inicio/recuperarpass";

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
	$route['getUmPorActividad'] = "back_end/cpp/getUmPorActividad";
	$route['excelTareas/(:any)/(:any)'] = "back_end/cpp/excelTareas/$1/$2";
	$route['getUsuariosSel2CPP'] = "back_end/cpp/getUsuariosSel2CPP";

/****** VISTA MENSUAL*******/
	$route['getVistaMensualView'] = "back_end/cpp/getVistaMensualView";
	$route['listaMes'] = "back_end/cpp/listaMes";
	$route['excelMensual/(:any)/(:any)/(:any)'] = "back_end/cpp/excelMensual/$1/$2/$3";


/****** MANTENEDOR ACTIVIDADES ******/
	$route['getMantActView'] = "back_end/cpp/getMantActView";
	$route['listaActividad'] = "back_end/cpp/listaActividad";
	$route['formActividad'] = "back_end/cpp/formActividad";
	$route['deleteActividad'] = "back_end/cpp/deleteActividad";
	$route['getDataActividad'] = "back_end/cpp/getDataActividad";
	$route['excelInforme/(:any)'] = "back_end/cpp/excelInforme/$1";

	$route['getMantUsView'] = "back_end/cpp/getMantUsView";

	

/****** MANTENEDOR USUARIOS*******/
	$route['getMantUsView'] = "back_end/cpp/getMantUsView";
	$route['getUsuariosSel2'] = "back_end/cpp/getUsuariosSel2";
	$route['listaMantUsuarios'] = "back_end/cpp/listaMantUsuarios";
	$route['formMantUs'] = "back_end/cpp/formMantUs";
	$route['getDataMantUs'] = "back_end/cpp/getDataMantUs";
	$route['eliminaUsuario'] = "back_end/cpp/eliminaUsuario";
	$route['excelusuarios'] = "back_end/cpp/excelusuarios";


/* End of file routes.php */
/* Location: ./application/config/routes.php */