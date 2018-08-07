<?php

/**
 * Constantes de conexión a la Base de Datos.
 */
const _DBSERVER_ = 'localhost';
const _DBUSER_ = 'root';
const _DBPASS_ = '';
const _DBNAME_ = 'frame';

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