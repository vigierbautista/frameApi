<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 1/7/2017
 * Time: 11:46 PM
 */

namespace FrameApi\Controllers;


use FrameApi\Core\Route;
use FrameApi\Exceptions\DBGetException;
use FrameApi\Models\Comment;
use FrameApi\View\View;

class CommentsController extends MainController
{

    public function getOfPost()
    {
        // Pedimos los datos de la url a Route.
        $urlData = Route::getUrlParameters();

        try {
            // Buscamos el post con ese ID
            $comments = Comment::getAllOfPost($urlData['id']);
            // Devolvemos el post.
            $output = [
                'status' => 1,
                'post' => $comments
            ];
        } catch (DBGetException $e) {
            $output = [
                'status' => 0,
                'msg' => $e->getMessage()
            ];
        }

        View::renderJson($output);
    }
}