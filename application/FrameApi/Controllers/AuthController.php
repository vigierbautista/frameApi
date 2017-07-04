<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 3/7/2017
 * Time: 1:23 AM
 */

namespace FrameApi\Controllers;
use FrameApi\Auth\Authenticate;
use FrameApi\Core\Request;
use FrameApi\Exceptions\DBInsertException;
use FrameApi\Exceptions\InvalidLoginException;
use FrameApi\Models\User;
use FrameApi\View\View;

/**
 * Class AuthController
 * @package FrameApi\Controllers
 */
class AuthController
{
    /**
     * Buscamos los datos del usuario, lo logueamos y luego lo imprimimos
     * @param Request $request
     */
    public function login(Request $request) {

        // Buscamos los datos en el request
        $data = $request->getData();

        // TODO VALIDAR DATOS
        try {

            $output = Authenticate::login($data['name'], $data['password']);

        } catch (InvalidLoginException $e) {
            $output = [
                'status' => 0,
                'msg' => $e->getMessage()
            ];

        }

        View::renderJson($output);
    }


    public function register(Request $request) {
        // Buscamos los datos en el request
        $data = $request->getData();

        // TODO validar datos.
        // Guardamos el usuario en la base.
        try {

            $newUser = User::create($data);

            $output = Authenticate::register($newUser);

        } catch (DBInsertException $e) {
            $output = [
                'status' => 0,
                'msg' => $e->getMessage()
            ];

        }
        View::renderJson($output);
    }
}