<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 17/6/2017
 * Time: 6:03 PM
 */

namespace FrameApi\Core;


use FrameApi\Exceptions\BadRequestException;

/**
 * Class App
 * @package FrameApi\Core
 */
class App
{

    /** @var string Ruta a la raíz de la aplicación. */
    private static $rootPath;

    /** @var string Ruta al directorio de la aplicación. */
    private static $appPath;

    /** @var string Ruta al directorio público. */
    private static $publicPath;

    /** @var string Ruta al directorio de las vistas. */
    private static $viewsPath;

    /** @var Request La petición del usuario. */
    protected $request;

    /**
     * App constructor.
     * @param $rootPath
     */
    public function __construct($rootPath)
    {
        self::$rootPath = $rootPath;
        self::$appPath = $rootPath . '/application';
        self::$publicPath = $rootPath . '/public';
        self::$viewsPath = $rootPath . '/views';
    }

    /**
     * Arranca la aplicación.
     * Obtiene la petición e instancia una Request.
     * Si existe la ruta asociada en la clase Route, ejecuta el controlador correspondiente.
     */
    public function run()
    {
        // Obtenemos la petición.
        $this->request = new Request();

        // Verificamos si la ruta existe.
        // Pasamos el verbo de la y la ruta de la petición.
        if(Route::exists($this->request->getMethod(), $this->request->getUrl())) {

            $controller = Route::getController($this->request->getMethod(), $this->request->getUrl());
            $this->executeController($controller);

        } else {
            throw new BadRequestException("No existe la ruta especificada.");
        }
    }



    private function executeController($controller)
    {

        // Separamos el controlador y el método a ejecutar y los guardamos en un array.
        $controllerArray = explode('@', $controller);
        $controllerName = $controllerArray[0];
        $controllerMethod = $controllerArray[1];

        // Le agregamos el namespace a la clase.
        $controllerName = "\\FrameApi\\Controllers\\" . $controllerName;

        // Instanciamos el controller.
        $controllerObject = new $controllerName;

        // Ejecutamos su método.
        $controllerObject->{$controllerMethod}($this->request);

    }





    /*********************** SETTERS & GETTERS ***************************/

    /**
     * @return string
     */
    public static function getRootPath()
    {
        return self::$rootPath;
    }

    /**
     * @param string $rootPath
     */
    public static function setRootPath($rootPath)
    {
        self::$rootPath = $rootPath;
    }

    /**
     * @return string
     */
    public static function getAppPath()
    {
        return self::$appPath;
    }

    /**
     * @param string $appPath
     */
    public static function setAppPath($appPath)
    {
        self::$appPath = $appPath;
    }

    /**
     * @return string
     */
    public static function getPublicPath()
    {
        return self::$publicPath;
    }

    /**
     * @param string $publicPath
     */
    public static function setPublicPath($publicPath)
    {
        self::$publicPath = $publicPath;
    }

    /**
     * @return string
     */
    public static function getViewsPath()
    {
        return self::$viewsPath;
    }

    /**
     * @param string $viewsPath
     */
    public static function setViewsPath($viewsPath)
    {
        self::$viewsPath = $viewsPath;
    }


}