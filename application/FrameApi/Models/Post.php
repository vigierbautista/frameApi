<?php
/**
 * Modelo de los Posts
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:15 PM
 */

namespace FrameApi\Models;


class Post extends MainModel implements \JsonSerializable
{
    protected $id;
    protected $title;
    protected $image;
    protected $content;
    protected $date_added;
    protected $id_user;

    protected static $atributes = [
        'title',
        'image',
        'content',
        'date_added',
        'id_user'
    ];

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
            'id_user'=> $this->getIdUser()
        ];
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
}