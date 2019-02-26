<?php
/**
 * Clase principal de los modelos.
 * Dinamiza los métodos más comunes de los modelos.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:28 PM
 */

namespace FrameApi\Models;


use FrameApi\Core\App;
use FrameApi\DB\Connection;
use FrameApi\Exceptions\DBGetException;
use FrameApi\Exceptions\DBInsertException;
use FrameApi\Exceptions\DBUpdateException;
use FrameApi\Exceptions\UndefinedMethodException;
use FrameApi\Security\Hash;
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
    protected static $attributes = [];

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


    protected static $fk;

	/** @var $validation_rules array Reglas de validación */
	protected static $validation_rules = [];


	/** @var $validation_msgs array Mensajes de error */
	protected static $validation_msgs = [];


	/**
	 * Modelo constructor.
	 * @param int|null $pk
	 * @throws DBGetException
	 */
    public function __construct($pk = null)
    {
        if(!is_null($pk)) {
            $this->getByPk($pk);
        }
    }

	/**
     * Retorna los métodos get de cada propiedad del modelo.
     * Esta función se ejecuta automágicamente  en el MainModel->cargarDatos;
     * @param string $attrName  El nombre de la propiedad.
     * @return mixed    El valor correspondiente a esa propiedad.
     */
    public function __get($attrName)
    {
        // Armamos el método: Ej: getTitle
        $getterName = "get" . ucfirst($attrName);

        // Verificamos si el método existe.
        if(method_exists($this, $getterName)) {

            return $this->{$getterName}();
        } else {

            return null;
        }

    }


    /**
     * Se ejecuta automágicamente cuando se trata de asignar un valor
     * a un propiedad que no es pública, o no existe.
     * @param $attrName
     * @param $value
     * @throws UndefinedMethodException
     */
    public function __set($attrName, $value)
    {

        // $this->{$attrName} = $value;

        $methodName = 'set' . ucfirst($attrName);
        if(method_exists($this, $methodName)) {
            $this->{$methodName}($value);
        } else {
            throw new UndefinedMethodException('No existe un setter para ' . $attrName . ". En la clase " . get_class($this));
        }
    }


    /**
     * Carga los datos del array $data en la instancia.
     * @param array $data
     */
    public function cargarDatos($data)
    {
        foreach ($data as $key => $value) {

            if(in_array($key, static::$attributes)) {

                if ($key == 'image' && $value !== '') {
					$this->image = __SITE_URL__ . '/images/' . static::$table . '/' . $this->getPrimaryKey() . "/$value";
				} else {
					$this->{$key} = $value;
				}
            }

            // Obtenemos el nombre de la clase.
            $className = get_class($this);

            // Si la clase tiene un método para traer su FK y su FK es igual que la propierdad de la clase
            if( method_exists($className, 'getFk') ){

                for ($i = 0; $i < count($className::getFK()); $i++) {

                    if( $key == $className::getFk()[$i] ) {

                        // Rompemos el string del método y nos quedamos con el nombre de la entidad que hace referencia el FK.
                        $property = explode('_', $className::getFk()[$i] );

                        $idGetter = "getId". ucfirst($property[1]);

                        // Cargamos la relación.
                        $this->loadRelations($this->{$idGetter}(), $property[1]);

                    }
                }

            }

        }


    }

    /**
     * Carga las relaciones del modelo.
     * Es importante saber que esto solo funciona si las Fk de las tablas llevan la siguiente nomenclatura: id_tabla
     * Por ejemplo: posts.id_user hace referencia a users.id
     *
     * @param $getFkValue int Valor del FK
     * @param $property string Nombre de la propiedad que guarda la entidad que hace referencia a la FK.
     */
    public function loadRelations($getFkValue, $property)
    {
        $className = "\\FrameApi\\Models\\" . ucfirst($property);
        $this->{$property} = new $className($getFkValue);

    }



    /**
     * Busca según el id
     * @param int $pk
     * @throws DBGetException
     */
    public function getByPk($pk)
    {
        $this->setPrimaryKey($pk);
        $query = "SELECT *
                  FROM " . static::$table . "
                  WHERE " . static::$primaryKey . " = ?";
        $stmt = Connection::getStatement($query);
        $stmt->execute([$this->getPrimaryKey()]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data) {

            $this->cargarDatos($data);

        } else {

            throw new DBGetException("No se encontró ningún registro.");
        }

    }

	/**
	 * Busca todos los registros de la tabla.
	 * @return array
	 * @throws DBGetException
	 */
    public static function getAll()
    {

        $query = "SELECT * FROM " . static::$table . " ORDER BY date_added DESC";

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
	 * Inserta en la base un registro nuevo.
	 * @param $data
	 * @return static
	 * @throws DBGetException
	 * @throws DBInsertException
	 */
    public static function create($data)
    {
        $data = static::filterData($data);

        $query = static::createQuery($data);

        $stmt = Connection::getStatement($query);

        if($stmt->execute($data)) {
            // Si el insert se hace con éxito creamos una instancia del modelo.
            $model = new static;
            // Luego le insertamos el ID al modelo.
            $model->setPrimaryKey(Connection::lastInsertedId());
            $model->cargarDatos($data);
            return $model;
        } else {
            throw new DBInsertException('Error al insertar el registro.');
        }
    }

	/**
	 * Edita un registro de la base.
	 * @param $data
	 * @return static
	 * @throws DBUpdateException
	 * @throws DBGetException
	 */
    public static function edit($data)
    {
    	if (isset($data['image'])) {
    		if (empty($data['image'])) {
    			unset($data['image']);
			}
		}

        $data = static::filterData($data);

        $query = static::editQuery($data);

        $stmt = Connection::getStatement($query);
        if($stmt->execute($data)) {
            // Si el insert se hace con éxito creamos una instancia del modelo.
            $model = new static($data['id']);
            return $model;
        } else {
            throw new DBUpdateException('Error al editar el registro.');
        }
    }


    public static function delete($id)
	{
		$query = static::deleteQuery();
		$stmt = Connection::getStatement($query);

		return $stmt->execute([':id' => $id]);
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
            if(!in_array($campoNombre, static::$attributes)) {
                unset($datos[$campoNombre]);
            }
            // Encryptamos la contraseña
            if($campoNombre === 'password') {
                $datos['password'] = Hash::encrypt($dato);
            }
            // Seteamos el valor de la fecha a ahora.
            if($campoNombre === 'date_added' && $dato == 'null') {
                $datos['date_added'] = date("Y-m-d H:i:s", time());
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
    protected static function createQuery($datos)
    {
        // Definimos la estructura base de nuestro query.
        $query = "INSERT INTO " . static::$table . " (";
        $queryValues = "VALUES (";

        // Recorremos los datos.
        $campos = [];
        $holders = [];
        foreach ($datos as $campoNombre => $dato) {
            if(in_array($campoNombre, static::$attributes)) {

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
     * Retorna una UPDATE QUERY genérica.
     * @param array $datos
     * @return string
     */
    protected static function editQuery($datos)
    {
        // Definimos la estructura base de nuestro query.
        $query = "UPDATE " . static::$table . " SET ";
        $queryValues = "";
        $queryCond = "WHERE id=";
        // Recorremos los datos.
        $values = [];
        foreach ($datos as $campoNombre => $dato) {
            if(in_array($campoNombre, static::$attributes)) {
                if($campoNombre === 'id') continue;
                $values[] = "$campoNombre = :$campoNombre";

            }
        }

        // Unificamos los campos y holders en el query.

        $queryValues .= implode(', ', $values);
        $queryCond .= ":id";
        return $query . " " . $queryValues . " " . $queryCond;
    }


	private static function deleteQuery()
	{
		$query = "DELETE FROM ". static::$table ." WHERE id=:id";
		return $query;
	}



    public function uploadFiles($files)
	{
		foreach ($files as $file) {


			$table_img_dir = App::getPublicPath() . '/images/' . static::$table . '/';

			if (!file_exists($table_img_dir) && !is_dir($table_img_dir)) {
				mkdir($table_img_dir);
			}

			$post_img_dir = $table_img_dir . $this->getPrimaryKey() . '/';

			if (!file_exists($post_img_dir) && !is_dir($post_img_dir)) {
				mkdir($post_img_dir);
			}

			$target_file = $post_img_dir . basename($file["name"]);

			if (file_exists($file["tmp_name"]) && !file_exists($target_file)) {
				return move_uploaded_file($file["tmp_name"], $target_file);
			}
		}

		return null;
	}


    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->{static::$primaryKey};
    }

    /**
     * @param $pk
     * @internal param string $primaryKey
     */
    public function setPrimaryKey($pk)
    {
        $this->{static::$primaryKey} = $pk;
    }

	/**
	 * @return array
	 */
	public static function getValidationRules()
	{
		return static::$validation_rules;
	}

	/**
	 * @return array
	 */
	public static function getValidationMsgs()
	{
		return static::$validation_msgs;
	}

}