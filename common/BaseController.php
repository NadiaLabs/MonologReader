<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class BaseController
 */
abstract class BaseController
{
    /**
     * @var string
     */
    const SESSION_REFERER_URL = 'referer_url';
    /**
     * @var string
     */
    const SESSION_LOGGED_IN = 'logged_in';

    /**
     * @var array
     */
    protected static $configs = [];

    /**
     * Run controller
     *
     * @param Request $request
     *
     * @return Response
     */
    abstract public function run(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        $session = $request->getSession()->start();
        $anonymousControllers = ['LoginController', 'SecurityController'];

        if (!$session->has(self::SESSION_LOGGED_IN) && !in_array(get_class($this), $anonymousControllers)) {
            if (!empty($_SERVER['HTTP_REFERER'])) {
                $session->set(self::SESSION_REFERER_URL, $_SERVER['HTTP_REFERER']);
            }

            return $this->redirectController(LoginController::class);
        }

        return $this->run($request);
    }

    /**
     * @param string $content
     *
     * @return Response
     */
    protected function response200($content)
    {
        return new Response($content);
    }

    /**
     * @param string $content
     *
     * @return Response
     */
    protected function response404($content)
    {
        return new Response($content, 400);
    }

    /**
     * @param string $content
     *
     * @return Response
     */
    protected function response500($content)
    {
        return new Response($content, 500);
    }

    /**
     * @param array $data
     *
     * @return Response
     */
    protected function responseJson(array $data)
    {
        return new Response(json_encode($data), 200, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    protected function redirect($url)
    {
        return new Response('', 302, ['Location' => $url]);
    }

    /**
     * @param string $controllerClass Controller class name
     * @param array  $params          Url query parameters
     *
     * @return Response
     */
    protected function redirectController($controllerClass, array $params = [])
    {
        return $this->redirect($this->generateUrl($controllerClass, $params));
    }

    /**
     * @param string $controllerClass Controller class name
     * @param array  $params          Url query parameters
     *
     * @return string
     */
    protected function generateUrl($controllerClass, array $params = [])
    {
        $url = '/?c='.uncamelize(str_replace('Controller', '', $controllerClass));

        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }

        return $url;
    }

    /**
     * @param array $viewData
     *
     * @return Response
     */
    protected function render(array $viewData = [])
    {
        $viewFile = __DIR__.'/../views/'.uncamelize(str_replace('Controller', '', get_class($this))).'.php';
        $viewData = array_merge($this->getGlobalViewData(), $viewData);

        ob_start();

        include __DIR__.'/../views/layout.php';

        $content = ob_get_contents();

        ob_end_clean();

        return $this->response200($content);
    }

    /**
     * @return array
     */
    protected function getGlobalViewData()
    {
        $logConfigs = $this->getConfig('logs', []);

        return [
            'selectedLogKey' => '',
            'logKeys' => array_keys($logConfigs),
        ];
    }

    /**
     * Get a config data
     *
     * @param string $name    Config name
     * @param mixed  $default Default config data
     *
     * @return mixed
     */
    protected function getConfig($name, $default = null)
    {
        if (array_key_exists($name, static::$configs)) {
            return static::$configs[$name];
        }

        $configFilePath = __DIR__.'/../config/'.$name.'.php';

        if (!file_exists($configFilePath)) {
            static::$configs[$name] = $default;
        } else {
            static::$configs[$name] = require $configFilePath;
        }

        return static::$configs[$name];
    }

    /**
     * Get a config data
     *
     * @param string $name Config name
     *
     * @return bool
     */
    protected function hasConfig($name)
    {
        if (array_key_exists($name, static::$configs)) {
            return true;
        }

        $data = $this->getConfig($name);

        return !empty($data);
    }

    /**
     * @param string           $name
     * @param array|string|int $data
     */
    protected function writeConfigFile($name, $data)
    {
        // Do not generate config file for Object input
        if (is_object($data)) {
            return;
        }

        $dataText = (is_array($data)) ? var_export($data, true) : "'".$data."'";
        $content =
            '<?php !defined(\'MONOLOG_READER\') && die(0);'.PHP_EOL.
            'return '.$dataText.';'.PHP_EOL
        ;

        file_put_contents(__DIR__.'/../config/'.$name.'.php', $content);

        static::$configs[$name] = $data;
    }

    /**
     * Encrypt password
     *
     * @param string $password
     *
     * @return string
     */
    protected function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * Check use is logged in or not
     *
     * @param Session $session
     *
     * @return bool
     */
    protected function isLoggedIn(Session $session)
    {
        return $session->has(self::SESSION_LOGGED_IN);
    }
}
