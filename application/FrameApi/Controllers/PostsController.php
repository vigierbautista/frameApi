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
use FrameApi\Exceptions\DBInsertException;
use FrameApi\Models\Post;
use FrameApi\View\View;

/**
 * Class PostsController
 * @package FrameApi\Controllers
 */
class PostsController
{
    /**
     * Función index que trae y manda todos los posts.
     */
    public function index()
    {
        $posts = Post::getAll();
        $output = [
            'status' => 1,
            'data' => $posts
        ];
        View::renderJson($output);
    }

    /**
     * Devuelve un Post según su ID.
     */
    public function get()
    {
        // Pedimos los datos de la url a Route.
        $urlData = Route::getUrlParameters();

        // Buscamos el post con ese ID
        //$post = new Post($urlData['id']);

        // Devolvemos el post.
        //View::renderJson($post);
        echo "Informacón del Post id: ". $urlData['id'];
    }

    /**
     * Guarda un Post en la base e devuelve el post insertado.
     * @param Request $request
     */
    public function save(Request $request)
    {
        // TODO Hacer upload de archivos.

        // Buscamos los datos en el request
        $data = $request->getData();

        // TODO validar datos.
        // Guardamos el post en la base.
        try {

            $newPost = Post::create($data);

            $output = [
                'status' => 1,
                'data' => $newPost
            ];
            View::renderJson($output);
        } catch (DBInsertException $e) {
            View::renderJson([
                'status' => 0,
                'errors' => $e->getMessage()
            ]);
        }


    }

}