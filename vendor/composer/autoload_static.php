<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite111da0bde70b0567c7eabc60bcc7697
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite111da0bde70b0567c7eabc60bcc7697::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite111da0bde70b0567c7eabc60bcc7697::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite111da0bde70b0567c7eabc60bcc7697::$classMap;

        }, null, ClassLoader::class);
    }
}