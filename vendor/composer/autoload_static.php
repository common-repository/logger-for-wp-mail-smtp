<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60fd289f7e269ea912f7725b5ce12d1b
{
    public static $files = array (
        'a2c48002d05f7782d8b603bd2bcb5252' => __DIR__ . '/..' . '/johnbillion/extended-cpts/extended-cpts.php',
    );

    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'InterFix\\WPMailSMTPLogger\\' => 26,
        ),
        'E' => 
        array (
            'ExtCPTs\\Tests\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'InterFix\\WPMailSMTPLogger\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'ExtCPTs\\Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/johnbillion/extended-cpts/tests/phpunit',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60fd289f7e269ea912f7725b5ce12d1b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60fd289f7e269ea912f7725b5ce12d1b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
