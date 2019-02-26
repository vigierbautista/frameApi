<?php
/**
 * Clase que se encarga de procesar las rutas de nuestra aplicación y su manejo interno.
 * A partir de los verbos de las peticiones: define la ruta, el controlador que las maneja y su respectivo método.
 * User: Bautista
 * Date: 20/6/2017
 * Time: 1:30 PM
 */

namespace FrameApi\Core;

/**
 * Class Route
 * @package FrameApi\Core
 */
class Route
{
    /**
     * Guarda las rutas según el verbo de la petición.
     * @var array
     */
    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
		'OPTIONS' => []
    ];

    /**
     * La acción del Controller a ejecutar.
     * @var string
     */
    protected static $controllerAction;

    /**
     * Los parámetros parseados de la url, cuando esta contiene {}.
     * @var array
     */
    protected static $urlParameters = [];






    /**
     * Guarda la ruta con el controller@método según el verbo
     * @param $method
     * @param $url
     * @param $controller
     */
    public static function setRoute($method, $url, $controller)
    {
        // Pasamos el verbo a mayúsculas.
        $method = strtoupper($method);
        // Guardamos la ruta y su controlador@método en el verbo indicado.
        self::$routes[$method][$url] = $controller;
    }

    /**
     * Retorna si existe o no la ruta.
     * @param $method
     * @param $url
     * @return bool
     */
    public static function exists($method, $url)
    {

        if(isset(self::$routes[$method][$url])) {
            return true;
        }
        // Verificamos si existe una ruta parametrizada (que contenga valores entre{}) que matchee la ruta que nos piden.
        else if(self::checkParameterizedRoute($method, $url)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Indica si existe una ruta parametrizada que matchee la $url para el $method.
     * Adicionalmente, va a parsear y almacenar los datos de la url.
     * @param string $method
     * @param string $url
     * @return bool
     */
    public static function checkParameterizedRoute($method, $url)
    {

        // Convertimos la url en un array.
        $urlParts = explode('/', $url);

        // Recorremos todas las rutas para este método
        foreach (self::$routes[$method] as $route => $controllerAction) {

            // Convertimos el $route en array.
            $routeParts = explode('/', $route);


            $routeMatches = true;
            $urlData = [];

            // Recorremos las partes y las comparamos con las de la url.
            foreach ($routeParts as $key => $part) {

                // Verificamos que cuenten con la misma cantidad de partes.
                if(count($routeParts) != count($urlParts)) {
                    $routeMatches = false;
                }
                if(isset($routeParts[$key]) && isset($urlParts[$key])) {
                    // Verificamos si no coinciden exactamente.
                    if($routeParts[$key] != $urlParts[$key]) {
                        // Verificamos si tiene una {
                        if(strpos($routeParts[$key], '{') === 0) {

                            // Obtenemos el nombre del parámetro, quitando las llaves del comienzo y del final.
                            $parameterName = substr($routeParts[$key], 1, -1);

                            // Guardamos el valor en el array de $urlData.
                            $urlData[$parameterName] = $urlParts[$key];

                        } else {
                            // La ruta no matchea :(
                            $routeMatches = false;
                        }
                    }
                }


            }

            // Verificamos si la ruta matchea.
            if($routeMatches) {

                // Guardamos los datos de la ruta.
                self::$controllerAction = $controllerAction;
                self::$urlParameters = $urlData;

                return true;
            }
        }

        // No existe ninguna ruta que matchee.
        return false;
    }

    /**
     * Retorna el controlador de la ruta
     * @param $method
     * @param $url
     * @return mixed
     */
    public static function getController($method, $url)
    {
        // Si obtuvimos una url parametrizada, la retornamos.
        if(!is_null(self::$controllerAction)) {
            return self::$controllerAction;
        }

        return self::$routes[$method][$url];
    }

    public static function getUrlParameters()
    {
        return self::$urlParameters;
    }
}