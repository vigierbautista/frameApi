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
     * Constructor estático para
     * Connection constructor.
     */
    private function __construct()
    {}

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
     * @return PDOStatement
     */
    public static function getStatement($query)
    {
        return self::getConnection()->prepare($query);
    }
}