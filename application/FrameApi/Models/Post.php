<?php
/**
 * Modelo de los Posts
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:15 PM
 */

namespace FrameApi\Models;


use FrameApi\Exceptions\UndefinedMethodException;

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

    /** @var array de los nombres de los campos que son FK */
    protected static $fk = [
        'id_user'
    ];

    /**
     * Variable que guarda una Instancia de User
     * @var User
     */
    protected $user;

    /**
     * Array con los campos permitidos para la tabla posts.
     * @var array
     */
    protected static $atributes = [
        'id',
        'title',
        'image',
        'content',
        'date_added',
        'id_user'
    ];

    /**
     * Nombre de la tabla en la DB.
     * @var string
     */
    protected static $table = 'posts';




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
     * @return array
     */
    public static function getFk()
    {
        return self::$fk;
    }
}