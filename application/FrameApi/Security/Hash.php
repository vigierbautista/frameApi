<?php
namespace FrameApi\Security;

class Hash
{
    /**
     * @param $pass
     * @return bool|string
     */
    public static function encrypt($pass)
    {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     *
     * @param string $pass  El password a verificar.
     * @param string $hash  El hash del password almacenado.
     * @return bool
     */
    public static function verify($pass, $hash)
    {
        return password_verify($pass, $hash);
    }
}