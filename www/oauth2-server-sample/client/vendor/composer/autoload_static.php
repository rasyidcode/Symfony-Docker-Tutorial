<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8a5da8f53a460b80e15b4bd86074762d
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rcd\\Client\\' => 11,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rcd\\Client\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8a5da8f53a460b80e15b4bd86074762d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8a5da8f53a460b80e15b4bd86074762d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8a5da8f53a460b80e15b4bd86074762d::$classMap;

        }, null, ClassLoader::class);
    }
}
