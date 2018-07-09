<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;
 if($_SERVER["HTTP_HOST"]=="localhost"){
	$hostname='localhost';
	$username='root';
	$password='asdf1212';
	$database='km1';
 }else{
	$hostname='localhost';
	$username='ceningen_intrakm';
	$password='#&M#Q@{T;Jry';
	$database='ceningen_intrakm';
 }
 
 

$db['default'] = array(
	'dsn'	=> '',
		
	'hostname' => $hostname,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	
	/*'hostname' => 'localhost',
	'username' => 'kmteleco_web',
	'password' => '87428742',
	'database' => 'kmteleco_intranet',*/

	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_spanish_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
