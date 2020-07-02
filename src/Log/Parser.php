<?php

namespace MonologReader\Log;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Parser
 */
class Parser
{
    /**
     * @var string
     */
    protected $pattern = '/^\[(?P<date>[^\]]*)\] (?P<logger>[\w-\s]+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';
    /**
     * @var string Pattern without context and extra parts
     */
    protected $pattern2 = '/^\[(?P<date>[^\]]*)\] (?P<logger>[\w-\s]+).(?P<level>\w+): (?P<message>.*)/';

    /**
     * @var array JSON open tags mapping
     */
    protected $jsonOpenTags = [
        '"' => '"',
        ']' => '[',
        '}' => '{',
    ];

    /**
     * @var array JSON close tags mapping
     */
    protected $jsonCloseTags = [
        '"' => '"',
        '[' => ']',
        '{' => '}',
    ];

    /**
     * @param string $log
     *
     * @return array
     */
    public function parse($log)
    {
        if (!is_string($log) || 0 === strlen($log)) {
            return [];
        }

        $results = $this->reverseParseJsonText($log);

        if (3 !== count($results)) {
            return $this->oldParse($log);
        }

        preg_match($this->pattern2, $results[2], $data);

        return [
            'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']),
            'logger' => $data['logger'],
            'level' => $data['level'],
            'message' => $data['message'],
            'context' => json_decode($results[1], true),
            'extra' => json_decode($results[0], true)
        ];
    }

    /**
     * Use old way to parse
     *
     * @param string $log
     *
     * @return array
     */
    private function oldParse($log)
    {
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

    /**
     * Parse JSON strings
     *
     * Begin parse the JSON string from the end of log text, then we can get the latest two JSON strings correctly.
     *
     * @param string $log
     *
     * @return array Return the log message and the last two JSON strings
     */
    private function reverseParseJsonText($log)
    {
        $chars = preg_split('//u', $log, -1, PREG_SPLIT_NO_EMPTY);
        $max = 2;
        $tags = [];
        $startPos = -1;
        $parts = [];

        for ($i = count($chars) - 1; $i >= 0 && $max > 0; --$i) {
            $char = $chars[$i];

            if (' ' === $char) {
                // Check is the end of number or null
                if (empty($tags) && $startPos !== -1) {
                    $parts[] = [
                        'start' => $i+1,
                        'end' => $startPos,
                    ];
                    $startPos = -1;
                    $max--;
                    continue;
                }

                // Skip useless blank characters
                if ($startPos === -1) {
                    continue;
                }
            }

            if ($startPos === -1) {
                $startPos = $i;
            }

            // Find the JSON open tags
            if (isset($this->jsonOpenTags[$char])) {
                if ($char !== '"' || ($char === '"' && end($tags) !== '"')) {
                    $tags[] = $char;
                    continue;
                }
            }

            // Find the JSON close tags
            if (isset($this->jsonCloseTags[$char])) {
                if (end($tags) === $this->jsonCloseTags[$char]) {
                    if (1 === count($tags)) {
                        $parts[] = [
                            'start' => $i,
                            'end' => $startPos,
                        ];
                        $startPos = -1;
                        $max--;
                        $tags = [];
                        continue;
                    } else {
                        array_pop($tags);
                    }
                } else {
                    // JSON format error
                    $parts[] = [
                        'start' => $i,
                        'end' => $startPos,
                    ];
                    $startPos = -1;
                    $max--;
                    continue;
                }
            }
        }

        $results = [];
        $pos = -1;

        foreach ($parts as $part) {
            $results[] = implode('', array_slice($chars, $part['start'], $part['end'] - $part['start'] + 1));
            $pos = $part['start'] - 1;
        }

        if (-1 === $pos) {
            if (empty($results)) {
                $results[] = $log;
            }
        } else {
            $results[] = trim(implode('', array_slice($chars, 0, $pos+1)));
        }

        return $results;
    }
}
