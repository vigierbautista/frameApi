<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 17/6/2017
 * Time: 5:29 PM
 */

use FrameApi\Core\App;
use FrameApi\Exceptions\BadRequestException;
use FrameApi\View\View;

// Antes que nada, requerimos el autoload.
require '../autoload.php';
require '../config.php';

// Guardamos la ruta absoluta de base del proyecto.
$rootPath = realpath(__DIR__ . '/../');

// Normalizamos las \ a /
$rootPath = str_replace('\\', '/', $rootPath);

// Requerimos las rutas de la aplicación.
require $rootPath . '/application/routes.php';

// Requerimos el autoload del vendor.
require $rootPath . '/application/vendor/autoload.php';

// Instanciamos nuestra App.
$App = new App($rootPath);


// Arrancamos la App.
try {
    $App->run();
}catch (BadRequestException $e) {
    // En caso de algún error, imprimimos el mensaje.
    View::renderJson([
        'status' => 0,
        'errors' => $e->getMessage()
    ]);
}
