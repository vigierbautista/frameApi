<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 17/6/2017
 * Time: 5:43 PM
 *
 * Registramos el autoload que requiere todas las clases de nuestra aplicación.
 */
spl_autoload_register(function($className) {
    // Cambiamos las \ a /
    $className = str_replace('\\', '/', $className);

    // Le agregamos la extensión de php, y la carpeta de
    // base "appplication/".
    $filePath = '../application/' . $className . ".php";

    // Verificamos si existe, y en caso positivo,
    // incluimos la clase.
    if(file_exists($filePath)) {
        require $filePath;
    }
});