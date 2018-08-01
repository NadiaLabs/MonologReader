<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class MonologReader
 *
 * @see https://github.com/pulse00/monolog-parser/blob/2ef54746f428f0efe959a9bdeb11294aa8872d2f/src/Dubture/Monolog/Reader/LogReader.php
 */
class MonologReader implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var \SplFileObject
     */
    protected $file;
    /**
     * @var integer
     */
    protected $lineCount = 0;
    /**
     * @var string
     */
    protected $pattern = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';

    /**
     * @param string $file Log file path
     */
    public function __construct($file)
    {
        $this->file = new \SplFileObject($file, 'r');

        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            $this->lineCount++;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->parse($this->file->current());
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->file->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->file->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->file->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return 0 < $offset && $offset < $this->lineCount;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $key = $this->file->key();

        $this->file->seek($offset);

        $log = $this->current();

        $this->file->seek($key);
        $this->file->current();

        return $log;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('MonologReader is read-only.');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('MonologReader is read-only.');
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->lineCount;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($log)
    {
        if (!is_string($log) || 0 === strlen($log)) {
            return [];
        }

        preg_match($this->pattern, $log, $data);

        if (!isset($data['date'])) {
            return [];
        }

        return [
            'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']),
            'logger' => $data['logger'],
            'level' => $data['level'],
            'message' => $data['message'],
            'context' => json_decode($data['context'], true),
            'extra' => json_decode($data['extra'], true)
        ];
    }
}
