<?php

namespace FrameApi\Validation;
use FrameApi\DB\Connection;
use FrameApi\Exceptions\UndefinedValidationMethodException;

/**
 * Clase que se encarga de la validación de formularios
 * Class Validator
 * @package FrameApi\Validation
 */
class Validator
{
	/**
	 * Data a validar.
	 *
	 * @var array
	 */
	protected $data = [];
	/**
	 * Reglas de validación a aplicar.
	 *
	 * @var array
	 */
	protected $rules = [];
	/**
	 * Bolsa de errores, en caso de haberlos.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Array de mensajes de cada campo para cada regla no cumplida
	 *
	 * @var array
	 */
	protected $msgs = [];


	/**
	 * Validator constructor.
	 *
	 * @param $data
	 * @param $rules
	 * @param $msgs
	 * @throws UndefinedValidationMethodException
	 */
	public function __construct($data, $rules, $msgs) {

		$this->data = $data;
		$this->rules = $rules;
		$this->msgs = $msgs;
		$this->validate();
	}

	/**
	 * Realiza las validaciones de los valores de $data según
	 * las $rules indicadas.
	 *
	 *    $data = [
	 *    'nombre' => 'Titanic',
	 *    'genero' => 'Drama',
	 *    'precio' => 1.5,
	 *    'fecha' => '2001-01-01',
	 *    'descripcion' => 'Leo se muere! :D'
	 *    ];
	 *
	 *    $rules = [
	 *    'nombre' => ['required', 'min:3'],
	 *    'genero' => ['required'],
	 *    'precio' => ['required', 'numeric', 'greater:0'],
	 *    'fecha' => ['date:d-m-Y']
	 *    ];
	 *
	 *    $msg = [
	 *  'nombre' => [
	 *        'required => 'Este campo es obligatorio',
	 *        'min' => 'Minimo de 3 caracteres'
	 *        ]
	 *    ]
	 *
	 * @throws UndefinedValidationMethodException
	 */
	private function validate() {

		// Recorremos todas las reglas.
		foreach ($this->rules as $fieldName => $fieldRules) {
			// Ej de valores:
			// $fieldName => "nombre"
			// $fieldRules => ['required', 'min:3']

			foreach ($fieldRules as $rule) {

				if(!$this->callValidation($rule, $fieldName)) {
					// Si la validación para este campo falla, entonces corto el bucle.
					break;
				}
			}
		}
	}

	/**
	 * @param string $rule
	 * @param string $fieldName
	 * @return bool Si la validación fue exitosa o no.
	 * @throws UndefinedValidationMethodException
	 */
	protected function callValidation($rule, $fieldName) {
		// Ej: $rule => 'required'
		// Ej 2: $rule => 'min:3'

		// Verificamos si estamos en el caso 1 o 2 de los ejemplos.
		$ruleData = explode(':', $rule);

		// Ahora, tendríamos que llamar a la validación de la regla, tomando además el valor del campo a validar.

		$methodName = '_' . $ruleData[0];

		if (method_exists($this, $methodName)) {

			if (count($ruleData) == 1) {
				return $this->{$methodName}($fieldName);
			} elseif (count($ruleData) == 2) {
				return $this->{$methodName}($fieldName, $ruleData[1]);
			} else {
				return $this->{$methodName}($fieldName, $ruleData[1], $ruleData[2]);
			}

		} else {
			throw new UndefinedValidationMethodException("No existe la regla de validación: " . $ruleData[0] . ".");
		}
	}



	/*****************************************************************
	 *****************************************************************
	 ** Validaciones:                                               **
	 ** Por cada regla, vamos a crear un método, que se llame igual **
	 ** a dicha regla, pero agregándole el prefijo: _               **
	 *****************************************************************
	 *****************************************************************/


	/**
	 * Valida que $value no esté vacío.
	 *
	 * @param string $fieldName El nombre del campo a validar
	 * @return bool
	 */
	protected function _required($fieldName) {
		if(empty($this->data[$fieldName]) && $this->data[$fieldName] !== '0' && $this->data[$fieldName] !== 0) {
			$this->addError($fieldName, $this->msgs[$fieldName]['required']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el $fieldName sea mayor que $than
	 * @param $fieldName
	 * @param $than
	 * @return bool
	 */
	protected function _greater($fieldName, $than) {
		if ($this->data[$fieldName] < $than) {
			$this->addError($fieldName, $this->msgs[$fieldName]['greater']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el $fieldName sea menor que $than
	 * @param $fieldName
	 * @param $than
	 * @return bool
	 */
	protected function _smaller($fieldName, $than) {
		if ($this->data[$fieldName] > $than) {
			$this->addError($fieldName, $this->msgs[$fieldName]['smaller']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el $fieldName tenga al menos $minLength caracteres.
	 *
	 * @param string $fieldName
	 * @param int $minLength
	 * @return bool
	 */
	protected function _min($fieldName, $minLength) {
		if(strlen($this->data[$fieldName]) < $minLength) {
			$this->addError($fieldName, $this->msgs[$fieldName]['min']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el $fieldName tenga al menos $minLength caracteres.
	 *
	 * @param string $fieldName
	 * @param int $minLength
	 * @return bool
	 */
	protected function _max($fieldName, $minLength) {
		if(strlen($this->data[$fieldName]) > $minLength) {
			$this->addError($fieldName, $this->msgs[$fieldName]['max']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el formato del $fieldName sea de un email correcto.
	 *
	 * @param $fieldName
	 * @return bool
	 */
	protected function _email($fieldName) {
		$emailRegEx = '/^([\w-\.]+)@((?:[\w]+\.)+)([a-z]{2,4})/';
		if (!preg_match($emailRegEx, $this->data[$fieldName])) {
			$this->addError($fieldName, $this->msgs[$fieldName]['email']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el valor del $fieldName esté registrado en la DB.
	 *
	 * @param $fieldName
	 * @param $table
	 * @return bool
	 */
	protected function _registered($fieldName, $table) {

		$result = Connection::select(" SELECT * FROM $table WHERE $fieldName = $this->data[$fieldName] LIMIT 1");
		if (!$result) {
			$this->addError($fieldName, $this->msgs[$fieldName]['registered']);
			return false;
		}
		return true;
	}
	//TODO PASSWORD VALIDATION
	protected function _password($fieldName) {

		if ($this->data[$fieldName] < 5) {
			$this->addError($fieldName, $this->msgs[$fieldName]['password']);
			return false;
		}
		if ($this->data[$fieldName] < 5) {
			$this->addError($fieldName, $this->msgs[$fieldName]['password']);
			return false;
		}
		if ($this->data[$fieldName] < 5) {
			$this->addError($fieldName, $this->msgs[$fieldName]['password']);
			return false;
		}

		return true;

	}

	/**
	 * Valida que el valor del $fieldName sea único.
	 *
	 * @param $fieldName
	 * @param $table
	 * @return bool
	 */
	protected function _unique($fieldName, $table) {
		$result = Connection::select(" SELECT * FROM $table WHERE $fieldName = $this->data[$fieldName] LIMIT 1");
		if (!$result) {
			$this->addError($fieldName, $this->msgs[$fieldName]['unique']);
			return false;
		}
		return true;
	}

	/**
	 * Valida si el valor del $fieldName es numérico.
	 *
	 * @param $fieldName
	 * @return bool
	 */
	protected function _numeric($fieldName) {
		if(!is_numeric($this->data[$fieldName])) {
			$this->addError($fieldName, $this->msgs[$fieldName]['numeric']);
			return false;
		}
		return true;
	}

	/**
	 * Valida si el valor  del $fieldName es igual al otro campo dado.
	 *
	 * @param $fieldName
	 * @param $otherField
	 * @return bool
	 */
	protected function _equal($fieldName, $otherField) {
		if($this->data[$fieldName] !== $this->data[$otherField]) {
			$this->addError($fieldName, $this->msgs[$fieldName]['equal']);
			return false;
		}
		return true;
	}

	/**
	 * Valida que el formato del $fieldname sea de una fecha válida
	 *
	 * @param $fieldName
	 * @return bool
	 */
	protected function _dateformat($fieldName) {
		$dateRegEx = '/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/';
		if (!preg_match($dateRegEx, $this->data[$fieldName])) {
			$this->addError($fieldName, $this->msgs[$fieldName]['dateformat']);
			return false;
		}
		return true;
	}



	/****************
	 ** Handlers:  **
	 ****************/

	/**
	 * Registra un error para un campo.
	 *
	 * @param $value
	 * @param $message
	 */
	private function addError($value, $message)
	{
		$this->errors[$value] = $message;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Informa si la validación fue exitosa.
	 *
	 * @return bool
	 */
	public function isValid()
	{
		return count($this->errors) == 0;
	}

	/**
	 * Informa si la validación fue errónea.
	 *
	 * @return bool
	 */
	public function isInvalid()
	{
		return !$this->isValid();
	}
}