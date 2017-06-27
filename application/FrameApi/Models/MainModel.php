<?php
/**
 * Clase principal de los modelos.
 * Dinamiza los métodos más comunes de los modelos.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:28 PM
 */

namespace FrameApi\Models;


use FrameApi\DB\Connection;
use FrameApi\Exceptions\DBInsertException;
use PDO;

/**
 * Class MainModel
 * @package FrameApi\Models
 */
class MainModel
{
    /**
     * Atributos del modelo permitidos.
     * @var array
     */
    protected static $atributosPermitidos = [];

    /**
     * Nombre de la base del modelo.
     * @var string
     */
    protected static $table;

    /**
     * Seteamos el valor del ID por defecto de todas nuestros modelos.
     * @var string
     */
    protected static $primaryKey = "id";


    /**
     * Modelo constructor.
     * @param int|null $pk
     */
    public function __construct($pk = null)
    {
        if(!is_null($pk)) {
            $this->getByPk($pk);
        }
    }

    /**
     * Carga los datos del array $data en la instancia.
     * @param array $data
     */
    public function cargarDatos($data)
    {
        foreach ($data as $key => $value) {
            if(in_array($key, static::$atributosPermitidos)) {
                // Ej: $key = "nombre";
                //     $this->$key equivale a: $this->nombre
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Busca según el id
     * @param int $pk
     */
    public function getByPk($pk)
    {
        $this->setPrimaryKey($pk);
        $query = "SELECT *
                  FROM " . static::$table . "
                  WHERE " . static::$primaryKey . " = ?";
        $stmt = Connection::getStatement($query);

        $stmt->execute([$this->getPrimaryKey()]);
        $this->cargarDatos($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Busca todos los registros de la tabla.
     * @return array
     */
    public static function getAll()
    {
        $query = "SELECT * FROM " . static::$table;
        $stmt = Connection::getStatement($query);
        $stmt->execute();
        $salida = [];
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new static();
            $model->setPrimaryKey($fila[static::$primaryKey]);
            $model->cargarDatos($fila);
            $salida[] = $model;
        }
        return $salida;
    }

    public static function create($data) // V2.0
    {
        // TODO
        // TODO
        // TODO
        // TODO EN este moemnto el array de data solo contiene los datos a insertar por eso devuelve error
        // TODO Debería recibir un array asociativo que contenga campo_de_la_base => valor
        // TODO
        // TODO
        $data = static::filterData($data);
        echo "<pre>";
        print_r($data); die;
        echo "</pre>";

        $query = static::generateCreateQuery($data);
        $stmt = Connection::getStatement($query);

        if($stmt->execute($data)) {
            // Si el insert se hace con exito creamos una instancia del modelo.
            $model = new static;
            $model->cargarDatos($data);
            // Luego le insertamos el ID al modelo.
            $model->setPrimaryKey(Connection::getConnection()->lastInsertId());
            return $model;
        } else {
            throw new DBInsertException('Error al insertar el registro.');
        }
    }

    /**
     * Filtra del array de datos los campos que no existen o no están permitidos.
     * @param $datos
     * @return mixed
     */
    protected static function filterData($datos)
    {
        foreach ($datos as $campoNombre => $dato) {
            // Si en el array hay datos que no están permitidos los sacamos del array.
            if(!in_array($campoNombre, static::$atributosPermitidos)) {
                unset($datos[$campoNombre]);
            }
        }
        // Devolvemos los datos filtrados.
        return $datos;
    }

    /**
     * Retorna una INSERT QUERY genérica.
     * @param array $datos
     * @return string
     */
    protected static function generateCreateQuery($datos)
    {
        // Definimos la estructura base de nuestro query.
        $query = "INSERT INTO " . static::$table . " (";
        $queryValues = "VALUES (";

        // Recorremos los datos.
        $campos = [];
        $holders = [];
        foreach ($datos as $campoNombre => $dato) {
            if(in_array($campoNombre, static::$atributosPermitidos)) {
                $campos[] = $campoNombre;
                $holders[] = ":" . $campoNombre;
            }
        }

        // Unificamos los campos y holders en el query.
        $query .= implode(', ', $campos) . ")";
        $queryValues .= implode(', ', $holders) . ")";
        return $query . " " . $queryValues;
    }

    /**
     * @return string
     */
    public static function getPrimaryKey()
    {
        return self::$primaryKey;
    }

    /**
     * @param string $primaryKey
     */
    public static function setPrimaryKey($primaryKey)
    {
        self::$primaryKey = $primaryKey;
    }


}