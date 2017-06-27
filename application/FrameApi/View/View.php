<?php
/**
 * Clase que se encarga de renderizar las vistas.
 * En caso de recibir llamadas externas renderiza el JSON con la respuesta.
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:03 PM
 */
namespace FrameApi\View;

use FrameApi\Core\App;

/**
 * Class View
 * @package FrameApi\View
 */
class View
{
    /**
     * Renderiza una vista.
     * @param string $view
     * @param array $data
     */
    public static function render($view, $data = [])
    {
        $__data__ = $data;
        // Recorremos el array con los datos que le llegan a la vista
        foreach ($__data__ as $varName => $varValue) {

            //Por cada data que llega creamos una variable con su nombre y su valor.
            ${$varName} = $varValue;
        }
        // Requerimos la vista.
        require App::getViewsPath() . '/' . $view . ".php";
    }

    /**
     * Renderiza una representación JSON de $data.
     * @param mixed $data
     */
    public static function renderJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}