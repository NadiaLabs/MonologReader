<?php !defined('MONOLOG_READER') && die();

/**
 * Class ErrorController
 */
class ErrorController extends BaseController
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * ErrorController constructor.
     *
     * @param string $content
     * @param int    $statusCode
     */
    public function __construct($content, $statusCode = 500)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        return new Response($this->content, $this->statusCode);
    }
}
