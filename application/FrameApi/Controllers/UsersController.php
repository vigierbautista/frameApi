<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:03 PM
 */

namespace FrameApi\Controllers;


use FrameApi\Core\Request;
use FrameApi\Mail\SendMail;
use FrameApi\Models\RecoverCode;
use FrameApi\Models\User;
use FrameApi\Security\Hash;
use FrameApi\View\View;

class UsersController extends MainController
{
	public function sendRecoverCode(Request $request)
	{
		$data = $request->getData();

		$User = new User();


		if ($result = $User->getByEmail($data['email'])) {


			$RecoverCode = new RecoverCode($User->getPrimaryKey());

			if ($RecoverCode->getCode()) {
				$message = "
				<h2 style='color: #39B7CC; margin-bottom: 20px'>Hola ". $User->getName() ."</h2>
				<p>Recibimos una solicitud de cambio de contraseña. Si no fuiste vos, desestimá este mensaje.</p>
				<p style='font-weight: bold; margin-bottom: 20px;'>Para cambiar tu contraseña ingresá este código en la app:</p>
				<span style='
					padding: 12px;
					font-weight: bold;
					font-size: 25px;
					color: #ffffff;
					background-color: #39B7CC;
				'>
					". $RecoverCode->getCode() ."
				</span>
			";

				$Mail = new SendMail(
					$data['email'],
					'Frame App - Solicitud de cambio de contraseña.',
					$message
				);

				$success = $Mail->send();

				$output = [
					'status' => $success ? 1 : 0,
					'user' => $User,
					'msg' => $success ?
						'Gracias '. $User->getName() . '. <br> Enviamos tu códidgo a ' . $User->getEmail()
						: 'No pudimos enviarte el email. Intenta de nuevo más tarde.'
				];

			} else {
				$output = [
					'status' => 0,
					'msg' => 'No pudimos generar el código. Intente de nuevo más tarde.'
				];
			}


		} else {
			$output = [
				'status' => 0,
				'msg' => 'No existe una cuenta con ese mail.'
			];
		}
		View::renderJson($output);
	}


	public function validateRecoverCode(Request $request)
	{
		$user = $request->getData();

		if (!$user['id'] && !$user['code']) {
			View::renderJson([
				'status' => 0,
				'msg' => 'Datos Incorrectos.'
			]);
		}


		if ($user['id']) {

			$result = RecoverCode::getUserCode($user['id']);

		} else {

			$result = RecoverCode::getUserCodeByCode($user['code']);

		}

		if ($result) {

			$expiration = strtotime($result['date_added'] . '+1 hour');


			if (strtotime($result['date_added']) > $expiration) {
				$output = [
					'status' => 0,
					'msg' => 'Código expirado. Solicite uno nuevo.'
				];
			} else {

				$output = [
					'status' => 1,
					'msg' => 'Código válido.',
					'user' => [
						'id' => $result['id_user'],
						'email' => $user['email']
					]
				];

			}

		} else {
			$output = [
				'status' => 0,
				'msg' => 'Código inválido. Solicite uno nuevo.'
			];
		}


		View::renderJson($output);
	}


	public function changePass(Request $request)
	{
		$data = $request->getData();

		$result = User::changePass($data['id'], Hash::encrypt($data['password']));

		if ($result) RecoverCode::deleteCode($data['id']);

		$output = [
			'status' => $result ? 1 : 0,
			'msg' => $result ? 'Contraseña cambiada con éxito.' : 'No pudimos cambiar tu contraseña. Intenta de nuevo más tarde.'
		];

		View::renderJson($output);
	}
}