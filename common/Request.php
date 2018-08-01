<?php !defined('MONOLOG_READER') && die();

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
    private $query;
    /**
     * @var array
     */
    private $request;
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
        $this->query = $_GET;
        $this->request = $_POST;
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
        if (isset($this->query[$name])) {
            return $this->query[$name];
        }

        if (isset($this->request[$name])) {
            return $this->request[$name];
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
}
