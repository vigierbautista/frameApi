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
use FrameApi\Exceptions\UndefinedValidationMethodException;
use FrameApi\Models\User;
use FrameApi\Validation\Validator;
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
	 * @throws \FrameApi\Exceptions\DBGetException
	 */
    public function login(Request $request) {

        // Buscamos los datos en el request
        $data = $request->getData();


		$Validator = new Validator($data, [
			'name' => ['required'],
			'password' => ['required'],
		], [
			'name' => ['required' => 'Ingrese su nombre'],
			'password' => ['required' => 'Ingrese su contraseña'],
		]);

		if ($Validator->isValid()) {
			try {

				$output = Authenticate::login($data['name'], $data['password']);

			} catch (InvalidLoginException $e) {
				$output = [
					'status' => 0,
					'msg' => 'Hubo un error en el login.',
					'errors' => [$e->getMessage()]
				];

			}
		} else {
			$output = [
				'status' => 0,
				'msg' => 'Datos incorrectos',
				'errors' => $Validator->getErrors()
			];
		}

        View::renderJson($output);
    }


	/**
	 * Registra un nuevo usuario
	 * @param Request $request
	 */
    public function register(Request $request) {
        // Buscamos los datos en el request
        $data = $request->getData();

		$Validator = new Validator($data, [
			'name' => ['required', 'min:3', 'max:20'],
			'last_name' => ['required', 'min:3', 'max:20'],
			'email' => ['required', 'email', 'unique:users'],
			'password' => ['required', 'password'],
			'password2' => ['required', 'equal:password']
		], [
			'name' => [
				'required' => 'Ingrese su nombre',
				'min' => 'Su nombre debe tener al menos 3 caracteres.',
				'max' => 'Su nombre debe tener un máximo de 20 caracteres.',
			],
			'last_name' => [
				'required' => 'Ingrese su apellido',
				'min' => 'Su apellido debe tener al menos 3 caracteres.',
				'max' => 'Su apellido debe tener un máximo de 20 caracteres.',
			],
			'email' => [
				'required' => 'Ingrese su email',
				'email' => 'El formato del email debe ser ejemplo@dominio.com',
				'unique' => 'Ese email ya se encuentra registrado.'
			],
			'password' => [
				'required' => 'Ingrese su contraseña',
				'password' => 'La contraseña debe tener un mínimo de 5 caracteres, una mayúscula y un número.'
			],
			'password2' => [
				'required' => 'Repita la contraseña',
				'equal' => 'Las contraseñas deben ser idénticas'
			]
		]);

		if ($Validator->isValid()) {
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
		} else {
			$output = [
				'status' => 0,
				'msg' => 'Datos incorrectos',
				'errors' => $Validator->getErrors()
			];
		}

        View::renderJson($output);
    }
}