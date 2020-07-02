<?php

namespace MonologReader\HttpFoundation;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Request
 */
class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Session
     */
    private $session;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = empty($_SERVER['REQUEST_METHOD']) ? 'GET' : strtoupper($_SERVER['REQUEST_METHOD']);
        $this->parameters = array_merge($_GET, $_POST);
        $this->session = new Session();
    }

    /**
     * Get request parameter value
     *
     * @param string $name
     * @param null   $default
     *
     * @return string|null
     */
    public function get($name, $default = null)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return $default;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Check request method
     *
     * @param string $method Request method
     *
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->method === strtoupper($method);
    }

    /**
     * @return string
     */
    public function getControllerClassName()
    {
        $name = !empty($_GET['c']) ? $_GET['c'] : 'dashboard';
        $camelizedName = str_replace('-', '', ucwords($name, '-'));

        return 'MonologReader\\Controller\\' . $camelizedName .'Controller';
    }

    /**
     * @param string $controllerClassName
     * @param array $parameters
     *
     * @return string
     */
    public function generateUrl($controllerClassName, array $parameters = [])
    {
        $controllerClassName = trim($controllerClassName, '\\ ');
        $name = substr($controllerClassName, strlen('MonologReader\\Controller\\'), -strlen('Controller'));
        $uncamelizedName = strtolower(preg_replace('/[A-Z]/', '-' . '\\0', lcfirst($name)));

        $uri = empty($_SERVER['REQUEST_URI']) ? '/' : $_SERVER['REQUEST_URI'];
        $uri = parse_url($uri);
        $path = empty($uri['path']) ? '' : $uri['path'];
        $path = rtrim($path, '/ ');

        $url = $path . '?c=' . $uncamelizedName;

        if (!empty($parameters)) {
            $url .= '&' . http_build_query($parameters);
        }

        return $url;
    }
}
