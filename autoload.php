<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class AutoLoadRegister
 */
class AutoLoadRegister
{
    /**
     * @var array
     */
    private $loadedClasses = [];

    /**
     * @param string $class
     */
    public function autoload($class)
    {
        if (isset($this->loadedClasses[$class])) {
            return;
        }

        $class = ltrim($class, '\\ ');
        $classPrefix = 'MonologReader\\';

        if (0 !== strpos($class, $classPrefix)) {
            return;
        }

        $path = substr($class, strlen($classPrefix));
        $path = str_replace('\\', '/', $path);
        $filepath = __DIR__ . '/src/' . $path . '.php';

        if (!file_exists($filepath)) {
            return;
        }

        $this->loadedClasses[$class] = true;

        require $filepath;
    }
}

\spl_autoload_register([new AutoLoadRegister(), 'autoload']);
