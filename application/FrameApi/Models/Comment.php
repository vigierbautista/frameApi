<?php
/**
 * Modelo de los comentarios.
 * User: Bautista
 * Date: 1/7/2017
 * Time: 10:30 PM
 */

namespace FrameApi\Models;


use FrameApi\DB\Connection;
use FrameApi\Exceptions\DBGetException;
use FrameApi\View\View;
use PDO;

class Comment extends MainModel implements \JsonSerializable
{
    /** @var int ID del comentario */
    protected $id;

    /** @var string contenido del comentario */
    protected $comment;

    /** @var string fecha de creación del comentario */
    protected $date_added;

    /** @var int ID del post de donde es el comentario */
    protected $id_post;

    /** @var int ID del usuario que comentó */
    protected $id_user;

    /**
     * Variable que guarda una Instancia de Post
     * @var Post
     */
    protected $post;

    /**
     * Variable que guarda una Instancia de User
     * @var User
     */
    protected $user;

    /**
     * Array con los campos permitidos para la tabla posts.
     * @var array
     */
    protected static $fk = [
        'id_post',
        'id_user',
    ];


    /**
     * Array con los campos permitidos para la tabla posts.
     * @var array
     */
    protected static $attributes = [
        'comment',
        'date_added',
        'id_user',
        'id_post'
    ];

    /**
     * Nombre de la tabla en la DB.
     * @var string
     */
    protected static $table = 'comments';

	protected static $validation_rules = [
		'comment' => ['required', 'min:5', 'max:500']
	];


	protected static $validation_msgs = [
		'comment' => [
			'required' => 'Ingrese su comentario',
			'min' => 'El comentario debe tener un mínimo de 5 caracteres',
			'max' => 'El comentario debe tener un máximo de 500 caracteres'
		]
	];

	/**
	 * Comment constructor.
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
	 * Busca todos los comentarios de un post especifico.
	 * @param $postId
	 * @return array
	 */
	public static function getAllOfPost($postId)
    {
        $query = "SELECT *
                  FROM comments
                  WHERE id_post=$postId
                  ORDER BY date_added DESC
		";

        $stmt = Connection::getStatement($query);

        $stmt->execute();
        $salida = [];
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // Creamos un modelo.
            $comment = new Comment();
            // Le insertamos el id
            $comment->setId($fila['id']);
            // Le cargamos los datos.
            $comment->cargarDatos($fila);
            // Lo sumamos al array de salida.
            $salida[] = $comment;
        }
        return $salida;
    }


    /**
     * Retorna las propiedades serializables a JSON.
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'=> $this->getId(),
            'comment'=> $this->getComment(),
            'date_added'=> $this->getDateAdded(),
            'id_user'=> $this->getIdUser(),
            'id_post'=> $this->getIdPost(),
            'user'=> $this->user->getPublicData(),
            'post'=> $this->post->getTitle(),
        ];
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param string $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
    }

    /**
     * @return int
     */
    public function getIdPost()
    {
        return $this->id_post;
    }

    /**
     * @param int $id_post
     */
    public function setIdPost($id_post)
    {
        $this->id_post = $id_post;
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param int $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public static function getFk()
    {
        return self::$fk;
    }

    /**
     * @param array $fk
     */
    public static function setFk($fk)
    {
        self::$fk = $fk;
    }

}