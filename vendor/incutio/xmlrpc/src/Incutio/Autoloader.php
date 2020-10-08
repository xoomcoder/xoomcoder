<?php

namespace Incutio;

class Autoloader
{
    private $dir;

    public function __construct($dir = null)
    {
        if (is_null($dir)) {
            $dir = dirname(__DIR__);
        }
        $this->dir = $dir;
    }
    
    /**
     * Registers Autoloader as an SPL autoloader.
     */
    public static function register($thrown = true)
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self(), 'autoload'), $thrown, true);
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class A class name.
     *
     * @return boolean Returns true if the class has been loaded
     */
    public function autoload($class)
    {
        if (0 !== strpos($class, 'Incutio')) {
            return false;
        }

        if (file_exists($file = $this->dir.'/'.str_replace('\\', '/', $class).'.php')) {
            require $file;
        }
    }
}