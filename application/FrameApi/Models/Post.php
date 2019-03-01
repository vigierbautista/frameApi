<?php
/**
 * Modelo de los Posts
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:15 PM
 */

namespace FrameApi\Models;
use FrameApi\DB\Connection;
use FrameApi\Exceptions\DBGetException;
use FrameApi\Exceptions\DBInsertException;
use FrameApi\View\View;
use PDO;


/**
 * Class Post
 * @package FrameApi\Models
 */
class Post extends MainModel implements \JsonSerializable
{
    /** @var int ID del post */
    protected $id;

    /** @var string Titulo del post */
    protected $title;

    /** @var string Nombre de la imagen del post */
    protected $image;

    /** @var string Contenido del post */
    protected $content;

    /** @var string Fecha de creación del post */
    protected $date_added;

    /** @var int Fk del usuario que creo el post */
    protected $id_user;

    protected $id_category;


    /** @var array de los nombres de los campos que son FK */
    protected static $fk = [
        'id_user',
		'id_category'
    ];

    /**
     * Variable que guarda una Instancia de User
     * @var User
     */
    protected $user;

	/**
	 * Variable que guarda una Instancia de Category
	 * @var User
	 */
    protected $category;

	/**
	 * Likes del post por usario
	 * @var
	 */
    protected $likes = [];

    /**
     * Array con los campos permitidos para la tabla posts.
     * @var array
     */
    protected static $attributes = [
        'id',
        'title',
        'image',
        'content',
        'date_added',
        'id_user',
		'id_category'
    ];

    /**
     * Nombre de la tabla en la DB.
     * @var string
     */
    protected static $table = 'posts';


    protected static $validation_rules = [
		'title' => ['required', 'max:30'],
		'content' => ['max:1000']
	];


    protected static $validation_msgs = [
		'title' => [
			'required' => 'Ingrese el titulo',
			'max' => 'El titulo debe tener un máximo de 30 caracteres'
		],
		'content' => [
			'max' => 'El contenido debe tener un máximo de 1.000 caracteres'
		]
	];

	/**
	 * Post constructor.
	 * @param null $pk
	 */
	public function __construct($pk = null)
	{
		try {

			parent::__construct($pk);

			$this->setLikes();

		} catch (DBGetException $e) {
			View::renderJson([
				'status' => 0,
				'msg' => $e->getMessage()
			]);
		}
	}

	public static function getAll()
	{

		$query = "SELECT * FROM posts ORDER BY date_added DESC";

		$stmt = Connection::getStatement($query);

		$stmt->execute();
		$salida = [];
		while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

			// Creamos un modelo.
			$post = new Post();
			// Le insertamos el id
			$post->setPrimaryKey($fila[static::$primaryKey]);
			// Le cargamos los datos.
			$post->cargarDatos($fila);
			$post->setLikes();
			// Lo sumamos al array de salida.
			$salida[] = $post;
		}
		return $salida;
	}

	public static function likePost($post_id, $user_id)
	{
		$query = "INSERT INTO liked_posts (id_user, id_post) VALUES (:id_user, :id_post)";
		$stmt = Connection::getStatement($query);

		return $stmt->execute([ ':id_user' => $user_id, ':id_post' => $post_id ]);
	}


	public static function unLikePost($post_id, $user_id)
	{
		$query = "DELETE FROM liked_posts WHERE id_user = :id_user AND id_post = :id_post";
		$stmt = Connection::getStatement($query);

		return $stmt->execute([ ':id_user' => $user_id, ':id_post' => $post_id ]);
	}


	private function setLikes()
	{
		$query = "
			SELECT l.*, u.name, u.last_name, u.image 
			FROM liked_posts l
			LEFT JOIN  users u ON u.id = l.id_user
			WHERE l.id_post = ". $this->getPrimaryKey();

		$stmt = Connection::getStatement($query);

		$stmt->execute();
		while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

			$this->likes[] = $fila;

		}
	}

	public function getLikes() {
		return $this->likes;
	}


    /**
     * Retorna las propiedades serializables a JSON.
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'=> $this->getId(),
            'title'=> $this->getTitle(),
            'image'=> $this->getImage(),
            'content'=> $this->getContent(),
            'date_added'=> $this->getDateAdded(),
            'user'=> $this->user->getPublicData(),
			'id_category' => $this->category->getId(),
			'category' => $this->category->getName(),
			'likes' => $this->getLikes()
        ];
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param mixed $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

	/**
	 * @return mixed
	 */
	public function getIdCategory()
	{
		return $this->id_category;
	}

	/**
	 * @param mixed $id_category
	 */
	public function setIdCategory($id_category)
	{
		$this->id_category = $id_category;
	}

    /**
     * @return array
     */
    public static function getFk()
    {
        return self::$fk;
    }
}