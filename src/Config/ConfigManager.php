<?php

namespace MonologReader\Config;

!defined('MONOLOG_READER') && die(0);

/**
 * Class ConfigManager
 */
class ConfigManager
{
    /** @var string */
    const CONFIG_NAME_ENCRYPTED_PASSWORD = 'encrypted-password';
    /** @var string */
    const CONFIG_NAME_LOGS = 'logs';

    /**
     * @var array
     */
    private $configurations = [];

    /**
     * @return string
     */
    public function loadEncryptedPassword()
    {
        return $this->load(self::CONFIG_NAME_ENCRYPTED_PASSWORD);
    }

    /**
     * @return array
     */
    public function loadLogs()
    {
        $return = $this->load(self::CONFIG_NAME_LOGS);
        $return = array_values($return);

        foreach ($return as $id => &$config) {
            $config['id'] = $id;

            unset($config['group']);
        }

        return $return;
    }

    /**
     * @param string $encryptedPassword
     *
     * @return $this
     */
    public function updateEncryptedPassword($encryptedPassword)
    {
        return $this->update(self::CONFIG_NAME_ENCRYPTED_PASSWORD, $encryptedPassword);
    }

    /**
     * @param array $logs
     *
     * @return $this
     */
    public function updateLogs(array $logs)
    {
        return $this->update(self::CONFIG_NAME_LOGS, $logs);
    }

    /**
     * Load an existed configuration
     *
     * @param string $name Configuration name
     *
     * @return mixed
     */
    public function load($name)
    {
        if (array_key_exists($name, $this->configurations)) {
            return $this->configurations[$name];
        }

        $filepath = $this->getFilepath($name);

        if (!file_exists($filepath)) {
            return null;
        }

        $this->configurations[$name] = require $filepath;

        return $this->configurations[$name];
    }

    /**
     * Update configuration data
     *
     * @param string $name Configuration name
     * @param array|string|int $data Configuration data
     *
     * @return $this
     */
    public function update($name, $data)
    {
        // Do not generate configuration file for "Objects"
        if (is_object($data)) {
            return;
        }

        $dataText = is_array($data) ? var_export($data, true) : "'" . str_replace("'", "\\'", $data) . "'";
        $content =
            '<?php !defined(\'MONOLOG_READER\') && die(0);' . PHP_EOL .
            'return ' . $dataText . ';' . PHP_EOL
        ;
        $filepath = $this->getFilepath($name);

        file_put_contents($filepath, $content);

        if (function_exists('opcache_invalidate')) {
            \opcache_invalidate($filepath, true);
        }

        unset($this->configurations[$name]);

        return $this;
    }

    /**
     * Get configuration file path
     *
     * @param string $name
     *
     * @return string
     */
    private function getFilepath($name)
    {
        return __DIR__ . '/../../config/' . $name . '.php';
    }
}
