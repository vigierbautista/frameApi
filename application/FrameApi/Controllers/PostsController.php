<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 1/7/2017
 * Time: 11:13 PM
 */

namespace FrameApi\Controllers;


use FrameApi\Core\Request;
use FrameApi\Models\Post;
use FrameApi\Security\Token;
use FrameApi\View\View;

class PostsController extends MainController
{
	public function like(Request $request)
	{
		$data = $request->getData();

		$token = $request->getHeaders()['X-Token'];

		if(!Token::verifyToken($token)) {
			View::renderJson([
				'status' => 0,
				'msg' => "El token es inválido"
			]);
		}

		if (!$data) {
			View::renderJson([
				'status' => 0,
				'msg' => "No se recibieron datos"
			]);
		}

		if ($data['liked']) {
			$result = Post::likePost($data['post_id'], $data['user_id']);
		} else {
			$result = Post::unLikePost($data['post_id'], $data['user_id']);
		}

		if ($result) {
			View::renderJson([
				'status' => 1,
				'user_id' => $data['user_id'],
				'post_id' => $data['post_id'],
				'liked' => $data['liked'],
				'msg' => "Cambios guardados con éxito."
			]);
		} else {
			View::renderJson([
				'status' => 0,
				'msg' => "Error al guardar los cambios."
			]);
		}
	}
}