<?php
/**
 * Clase que se encarga de la conexión a la base de datos.
 * Modo Singleton.
 * User: Bautista
 * Date: 20/6/2017
 * Time: 6:42 PM
 */

namespace FrameApi\DB;

use PDO;
use PDOStatement;

/**
 * Class Connection
 * @package FrameApi\DB
 */
class Connection implements ConnectionInterface
{
	/**
	 * Propiedad que instancia el objeto de conexión
	 * @var null|PDO
	 */
	private static $db = null;

	/**
	 * Server donde está la DB.
	 * @var string
	 */
	private static $host;

	/**
	 * Nombre de usuario de la DB
	 * @var string
	 */
	private static $user;

	/**
	 * Password del usuario de la DB
	 * @var string
	 */
	private static $pass;

	/**
	 * Nombre de la DB
	 * @var string
	 */
	private static $base;

	/**
	 * Constructor privado modo Singleton
	 * Connection constructor.
	 */
	private function __construct(){}

	/**
	 * Crea la conexón a la base de datos.
	 */
	private static function connect()
	{
		self::$host = _DBSERVER_;
		self::$user = _DBUSER_;
		self::$pass = _DBPASS_;
		self::$base = _DBNAME_;

		$dsn = "mysql:host=".self::$host.";dbname=".self::$base.";charset=utf8";
		self::$db = new PDO($dsn, self::$user, self::$pass);
	}

	/**
	 * Retorna una conexión a la base de datos en modo Singleton.
	 * @return PDO
	 */
	public static function getConnection()
	{
		// Si no tenemos conexión, la abrimos.
		if(is_null(self::$db)) {
			self::connect();
		}

		// Retornamos la conexión.
		return self::$db;
	}

	/**
	 * Retorna el PDOStatement para el $query proporcionado.
	 * @param string $query
	 * @param array $options
	 * @return PDOStatement
	 */
	public static function getStatement($query, $options = [])
	{
		return (count($options) > 0)?
			self::getConnection()->prepare($query, $options):
			self::getConnection()->prepare($query);
	}

	/**
	 * Ejecuta una consulta de tipo SELECT.
	 * Es necesario usar la variante de :ejemplo para los prepared statements.
	 * @param string $query
	 * @param array $prepared_statements
	 * @return mixed
	 */
	public static function select($query, $prepared_statements = null)
	{
		$stmt = self::getStatement($query);
		$stmt->execute($prepared_statements);
		$result = [];

		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			$result[] = $row;
		}

		if (empty($result)) return false;

		return count($result) > 1 ? $result : $result[0];
	}


	/**
	 * Ejecuta una consulta de tipo INSERT.
	 * Es necesario usar la variante de :ejemplo para los prepared statements.
	 * @param $table string Tabla donde hacer la inserción.
	 * @param $fields ['nombre_campo' => 'valor_campo']
	 * @return bool
	 */
	public static function insert($table, $fields)
	{
		$values = [];
		$prepared_statements = [];
		foreach ($fields as $key => $val) {
			$values[] = ":$key";
			$prepared_statements[":$key"] = $val;
		}

		$query = "INSERT INTO $table (". implode(',', array_keys($fields)) .") VALUES (". implode(',', $values) .")";

		$stmt = self::getStatement($query);
		return $stmt->execute($prepared_statements);
	}


	/**
	 * Escapa los caracteres de la cadena dada.
	 * @param $string
	 * @return string
	 */
	public static function quote($string)
	{
		return self::$db->quote($string);
	}

	/**
	 * Retorna el último id insertado.
	 * @return string
	 */
	public static function lastInsertedId()
	{
		return self::getConnection()->lastInsertId();
	}

}