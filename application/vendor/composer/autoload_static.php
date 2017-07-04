<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd6e8c271ddb5581c7e45b5f3438d45bb
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Emarref\\Jwt\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Emarref\\Jwt\\' => 
        array (
            0 => __DIR__ . '/..' . '/emarref/jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd6e8c271ddb5581c7e45b5f3438d45bb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd6e8c271ddb5581c7e45b5f3438d45bb::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
