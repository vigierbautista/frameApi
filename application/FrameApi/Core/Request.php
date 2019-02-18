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

    /**
     * Datos que llegan por POST.
     * @var
     */
    protected $data;

	/**
	 * Archivos que llegan por POST
	 * @var
	 */
    protected $files = [];

    /**
     * Headers de la petición.
     * @var array
     */
    protected $headers;


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

        // Cargamos los headers
        $this->headers = apache_request_headers();

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
                $this->loadFiles();
                break;

            default:
                $this->loadPostData();
                break;
        }
    }

    /**
     * Busca los datos del buffer de entrada de php
     */
    protected function loadPostData()
    {
        $entradaPost = file_get_contents('php://input');

        $datosPost = json_decode($entradaPost, true);

        if (!$datosPost) {
        	$this->data = $_POST;
		} else {
			$this->data = $datosPost;
		}

	}


	private function loadFiles()
	{
		if (!empty($_FILES)) {
			$this->files = $_FILES;
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
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

	public function getFiles()
	{
		return $this->files;
	}

}