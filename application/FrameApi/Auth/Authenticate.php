<?php

namespace FrameApi\Auth;

use FrameApi\Exceptions\InvalidLoginException;
use FrameApi\Models\User;
use FrameApi\Security\Hash;
use FrameApi\Security\Token;
use FrameApi\Session\Session;

/**
 * Class Authenticate
 * @package FrameApi\Auth
 */
class Authenticate
{
    /**
     * User
     * @var null
     */
    protected static $user = null;

	/**
	 * Loguea un usuario
	 * @param $userName
	 * @param $pass
	 * @param bool $apiResponse | optional: indica si la respuesta debe tratarse como Api
	 * @return array
	 * @throws InvalidLoginException
	 * @throws \FrameApi\Exceptions\DBGetException
	 */
    public static function login($userName, $pass, $apiResponse = true)
    {
        $user = new User;

        // Verifico si existe el User
        if($result = $user->getByName($userName)) {

            // Verifico si el password es correcto.
            if(Hash::verify($pass, $user->getPassword())) {

                if($apiResponse) {
                    $authToken = Token::createToken($user->getId());

                    return [
                        'status' => 1,
                        'token' => $authToken,
                        'data'  => $user,
                        'msg' => 'Ya estás logueado!'
                    ];

                } else {
                    self::logUser($user);
                }

            } else {

                throw new InvalidLoginException ("Password incorrecta.");
            }

        } else {
            throw new InvalidLoginException ("Usuario incorrecto.");

        }

		return [
			'status' => 0,
			'msg' => 'Error de login.'
		];

    }

    /**
     * Registra un usuario
     * @param User $user
     * @return array
     */
    public static function register(User $user)
    {

        $authToken = Token::createToken($user->getId());

        return [
            'status' => 1,
            'token' => $authToken,
            'data'  => $user,
            'msg' => 'Cuenta creada con éxito'
        ];

    }



    /**
     * @param $user
     */
    protected static function logUser($user)
    {
        Session::set('auth', true);
        Session::set('user', $user);
        self::$user = $user;
    }

    /**
     * @return bool
     */
    public static function isLogged()
    {
        if(Session::get('auth') === true) {
            self::$user = Session::get('user');
            return true;
        }
        return false;
    }

    /**
     * @return null|User
     */
    public static function getUser()
    {
        return self::$user;
    }
}