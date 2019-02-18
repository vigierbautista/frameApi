<?php
/**
 * Modelo de los Posts
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:15 PM
 */

namespace FrameApi\Models;
use FrameApi\Exceptions\DBGetException;
use FrameApi\View\View;


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

		} catch (DBGetException $e) {
			View::renderJson([
				'status' => 0,
				'msg' => $e->getMessage()
			]);
		}
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
            'id_user'=> $this->getIdUser(),
            'user'=> $this->user->getName(),
			'id_category' => $this->category->getId(),
			'category' => $this->category->getName(),
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