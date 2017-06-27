<?php
/**
 * Interfaz de la conexión
 * User: Bautista
 * Date: 25/6/2017
 * Time: 9:18 PM
 */

namespace FrameApi\DB;


interface ConnectionInterface
{
    public static function getConnection();

    public static function getStatement($query);
}