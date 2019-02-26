<?php

const __LOCAL_ADDR__ = [
	'localhost',
	'127.0.0.1',
	'::1'
];


if (in_array($_SERVER['SERVER_ADDR'], __LOCAL_ADDR__)) {
	define('__ENV__', 'DEV');
}

/**
 * Constantes de conexión a la Base de Datos.
 */
const _DBSERVER_ 	= __ENV__ == 'DEV' ? 'localhost' 	: 'localhost';
const _DBUSER_ 		= __ENV__ == 'DEV' ? 'root' 		: 'u388058213_root';
const _DBPASS_ 		= __ENV__ == 'DEV' ?  '' 			: 'dbframe';
const _DBNAME_ 		= __ENV__ == 'DEV' ?  'frame' 		: 'u388058213_frame';



const __SITE_URL__ 	= __ENV__ == 'DEV' ? 'http://localhost/frameApi/public_html' : 'https://web-frame.site/';


/**
 * Error Display
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('html_errors', false);
error_reporting(E_ALL);

# CORS
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Origin: *');

/**
 * Default timezone
 */
date_default_timezone_set('America/Argentina/Buenos_Aires');