<?php
/**
 * Clase que se encarga de capturar la url de la petición del usuario.
 * Guarda la url relativa y el método de la petición (GET POST PUT DELETE).
 * User: Bautista
 * Date: 20/6/2017
 * Time: 12:34 PM
 */

namespace FrameApi\Core;

/**
 * Class Request
 * @package FrameApi\Core
 */
class Request
{
    /**
     * La url de la solicitud del usuario. (a partir de public/ ).
     * De esta manera guardamos las solicitudes internas que se hacen en nuestra aplicación.
     * Lo anterior a public/ no nos interesa.
     * @var string
     */
    protected $url;

    /**
     * El método de la petición.
     * @var string
     */
    protected $method;

    protected $data;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        // Tomamos el método de la petición de la super global $_SERVER.
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Generamos la ruta absoluta.
        // Le sacamos la doble / que se genera al unir las dos variables
        $rutaAbsoluta = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']);

        // Guardamos solo lo que sigue después de public/
        $this->url = str_replace(App::getPublicPath(), '', $rutaAbsoluta);

        $this->loadData();

    }

    /**
     * Carga los datos que llegan por POST
     */
    protected function loadData()
    {
        switch($this->getMethod()) {
            case "GET":
                break;

            case "POST":
                $this->loadPostData();
                break;

            default:
                $this->loadPostData();
                break;
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    protected function loadPostData()
    {
        $entradaPost = file_get_contents('php://input');

        $datosPost = json_decode($entradaPost, true);
        $this->data = $datosPost;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

}