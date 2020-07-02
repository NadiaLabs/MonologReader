<?php

namespace MonologReader\Log;

use SplFileObject;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Reader
 */
class Reader implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var SplFileObject
     */
    protected $file;
    /**
     * @var Parser
     */
    protected $parser;
    /**
     * @var integer
     */
    protected $lineCount = 0;

    /**
     * @param string $file Log file path
     */
    public function __construct($file)
    {
        $this->file = new SplFileObject($file, 'r');
        $this->parser = new Parser();

        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            $this->lineCount++;
        }

        $this->file->seek($this->lineCount - 1);
        $lastLine = $this->file->current();

        if (empty($lastLine)) {
            $this->lineCount--;
        }

        $this->file->rewind();
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->parser->parse($this->file->current());
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->file->next();
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->file->key();
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->file->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->file->rewind();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return 0 < $offset && $offset < $this->lineCount;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('MonologReader is read-only.');
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('MonologReader is read-only.');
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->lineCount;
    }
}
