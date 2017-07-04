<?php
namespace FrameApi\Session;

/**
 * Class Session
 * @package FrameApi\Session
 */
class Session
{
    /**
     * @var bool
     */
    protected static $sessionStarted = false;

    /**
     * Inicia la sesión.
     */
    public static function start()
    {
        session_start();
        self::$sessionStarted = true;
    }

    /**
     * Termina la sesión.
     */
    public static function destroy()
    {
        session_destroy();
        self::$sessionStarted = false;
    }

    /**
     * @param $prop
     * @param $value
     */
    public static function set($prop, $value)
    {
        if(!self::$sessionStarted) {
            self::start();
        }
        $_SESSION[$prop] = $value;
    }

    /**
     * @param $prop
     * @return mixed
     */
    public static function get($prop)
    {
        if(!self::$sessionStarted) {
            self::start();
        }
        return $_SESSION[$prop];
    }

    /**
     * @param $prop
     * @return mixed
     */
    public static function has($prop)
    {
        if(!self::$sessionStarted) {
            self::start();
        }
        return isset($_SESSION[$prop]);
    }

    /**
     * @param $prop
     * @return mixed
     */
    public static function clearValue($prop)
    {
        if(!self::$sessionStarted) {
            self::start();
        }
        unset($_SESSION[$prop]);
    }
}