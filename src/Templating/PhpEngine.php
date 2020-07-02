<?php

namespace MonologReader\Templating;

!defined('MONOLOG_READER') && die(0);

/**
 * Class PhpEngine
 */
class PhpEngine
{
    /**
     * @var array
     */
    private $globalParameters;

    /**
     * PhpEngine constructor.
     *
     * @param array $globalParameters
     */
    public function __construct(array $globalParameters = [])
    {
        $this->globalParameters = $globalParameters;
    }

    /**
     * Render a PHP template
     *
     * @param string $view The view file path (without ".php" suffix) in "templates" directory,
     *                     e.g. "foo/bar" for "templates/foo/bar.php"
     *                          "dashboard" for "templates/dashboard.php"
     * @param array $parameters Parameters for rendering with view file
     *
     * @return string
     */
    public function render($view, array $parameters = [])
    {
        $parameters = array_merge($this->globalParameters, $parameters);

        if (array_key_exists('view', $parameters)) {
            unset($parameters['view']);
        }
        if (array_key_exists('parameters', $parameters)) {
            unset($parameters['parameters']);
        }

        // Render view contents
        ob_start();

        extract($parameters);

        include __DIR__ . '/../../templates/' . $view .'.php';

        $mainContents = ob_get_contents();

        ob_end_clean();
        // End of render view contents

        // Render layout contents
        ob_start();

        include __DIR__ . '/../../templates/layout.php';

        $contents = ob_get_contents();

        ob_end_clean();
        // End of render layout contents

        return $contents;
    }
}
