<?php
/**
 * Controller que maneja los datos de los posts.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:03 PM
 */

namespace FrameApi\Controllers;


use FrameApi\Core\Request;
use FrameApi\Core\Route;
use FrameApi\Exceptions\DBGetException;
use FrameApi\Exceptions\DBInsertException;
use FrameApi\Exceptions\DBUpdateException;
use FrameApi\Security\Token;
use FrameApi\View\View;

/**
 * Class MainController
 * @package FrameApi\Controllers
 */
class MainController
{

    protected $model;

    public function __construct()
    {
        // Capturamos el controllador que es llamado.
        $controller = get_class($this);

        // Reemplazamos los controllers para usar los models.
        $replace = ['Controller', 'Controllers'];
        $for = ['Model', 'Models'];

        $this->model = str_replace($replace, $for , $controller);

        // Le sacamos el último fragmento que los modelos no tienen.
        // Ej: PostsModel quedaria Post.
        $this->model = str_replace(substr($this->model, -6), '' , $this->model);


    }

    /**
     * Función index que trae y manda todos los registros del modelo.
     */
    public function index()
    {
        $model = $this->model;
        $all = $model::getAll();

        $output = [
            'status' => 1,
            'data' => $all
        ];
        View::renderJson($output);
    }

    /**
     * Devuelve un registro según su ID.
     */
    public function get()
    {
        // Pedimos los datos de la url a Route.
        $urlData = Route::getUrlParameters();

        try {
            // Buscamos el post con ese ID
            $one = new $this->model($urlData['id']);
            // Devolvemos el post.
            $output = [
                'status' => 1,
                'post' => $one
            ];
        } catch (DBGetException $e) {
            $output = [
                'status' => 0,
                'msg' => $e->getMessage()
            ];
        }

        View::renderJson($output);
    }

    /**
     * Guarda un Post en la base y devuelve el post insertado.
     * @param Request $request
     */
    public function save(Request $request)
    {
        // TODO Hacer upload de archivos.

        // Buscamos los datos en el request
        $data = $request->getData();
        $token = $request->getHeaders()['X-Token'];

        if(Token::verifyToken($token)) {
            // TODO validar datos.
            // Guardamos el post en la base.
            try {
                $model = $this->model;
                $newOne = $model::create($data);

                $output = [
                    'status' => 1,
                    'data' => $newOne,
                    'msg' => 'Publicación realizada exitosamente.'
                ];

            } catch (DBInsertException $e) {
                $output = [
                    'status' => 0,
                    'msg' => $e->getMessage()
                ];

            }

        } else {
            $output = [
                'status' => 0,
                'msg' => "El token es inválido"
            ];
        }


        View::renderJson($output);


    }

    public function edit(Request $request)
    {
        // Buscamos los datos en el request
        $data = $request->getData();
        $token = $request->getHeaders()['X-Token'];

        if(Token::verifyToken($token)) {
            // TODO validar datos.
            // Guardamos el post en la base.
            try {
                $model = $this->model;
                $edited = $model::edit($data);

                $output = [
                    'status' => 1,
                    'data' => $edited,
                    'msg' => 'Edición realizada exitosamente.'
                ];

            } catch (DBUpdateException $e) {
                $output = [
                    'status' => 0,
                    'msg' => $e->getMessage()
                ];

            }

        } else {
            $output = [
                'status' => 0,
                'msg' => "El token es inválido"
            ];
        }

        View::renderJson($output);
    }

}