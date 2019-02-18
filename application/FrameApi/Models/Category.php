<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 17/2/2019
 * Time: 22:08
 */

namespace FrameApi\Models;


use FrameApi\DB\Connection;
use PDO;

class Category extends MainModel implements \JsonSerializable
{
	protected $id;

	protected $name;

	protected static $table = 'categories';

	protected static $attributes = [
		'id',
		'name'
	];

	public static function getAll()
	{

		$query = "SELECT * FROM " . static::$table ;

		$stmt = Connection::getStatement($query);

		$stmt->execute();
		$salida = [];
		while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// Creamos un modelo.
			$model = new static();
			// Le insertamos el id
			$model->setPrimaryKey($fila[static::$primaryKey]);
			// Le cargamos los datos.
			$model->cargarDatos($fila);
			// Lo sumamos al array de salida.
			$salida[] = $model;
		}
		return $salida;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize()
	{
		return [
			'id'=> $this->getId(),
			'category' => $this->getName(),
		];
	}
}