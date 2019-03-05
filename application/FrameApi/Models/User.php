<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:16 PM
 */

namespace FrameApi\Models;


use FrameApi\DB\Connection;
use PDO;

/**
 * Class User
 * @package FrameApi\Models
 */
class User extends MainModel implements \JsonSerializable
{
    /** @var int ID del usuario */
    protected $id;

    /** @var string Nombre del usuario */
    protected $name;

    /** @var string Apellido del usuario */
    protected $last_name;

    /** @var string Email del usuario */
    protected $email;

    /** @var string Password del usuario */
    protected $password;

    protected $image;


    /**
     * Array con los campos permitidos para la tabla users.
     * @var array
     */
    protected static $attributes = [
        'id',
        'name',
        'last_name',
        'email',
        'password',
		'image'
    ];

    /**
     * Nombre de la tabla en la DB
     * @var string
     */
    protected static $table = 'users';


	protected static $validation_rules = [
		'email' => ['required', 'min:3', 'max:20'],
		'last_name' => ['required', 'min:3', 'max:20']
	];


	protected static $validation_msgs = [
		'name' => [
			'required' => 'Ingrese su nombre',
			'min' => 'Su nombre debe tener al menos 3 caracteres.',
			'max' => 'Su nombre debe tener un máximo de 20 caracteres.',
		],
		'last_name' => [
			'required' => 'Ingrese su apellido',
			'min' => 'Su apellido debe tener al menos 3 caracteres.',
			'max' => 'Su apellido debe tener un máximo de 20 caracteres.',
		]
	];


	/**
	 * Busca a un usuario por su mail.
	 * @param $email
	 * @return bool
	 */
    public function getByEmail($email)
    {
        $query = "SELECT * FROM users
                  WHERE email = '$email'";
        $stmt = Connection::getStatement($query);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if($userData) {
            $this->cargarDatos($userData);
            return true;
        } else {
            return false;
        }
    }

	public static function changePass($id, $password)
	{
		$query = "
			UPDATE users SET `password`= :password WHERE  `id`=:id;
		";
		$stmt = Connection::getStatement($query);

		return $stmt->execute(['id' => $id, 'password' => $password]);
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
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

	/**
	 * @return mixed
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * @param mixed $image
	 */
	public function setImage($image)
	{
		$this->image = $image;
	}


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'id'=> $this->getId(),
            'name'=> $this->getName(),
            'last_name'=> $this->getLastName(),
            'email'=> $this->getEmail(),
            'password'=> $this->getPassword(),
            'image'=> $this->getImage()
        ];
    }

	public function getPublicData()
	{
		return [
			'id'=> $this->getId(),
			'name'=> $this->getName(),
			'last_name'=> $this->getLastName(),
			'image'=> $this->getImage()
		];
	}
}