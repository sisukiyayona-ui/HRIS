<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ZKLoader
{
    protected $prefix = 'ZK\\';
    protected $baseDir;

    public function __construct()
    {
        $this->baseDir = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'php_zklib' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        if (!extension_loaded('sockets')) {
            show_error('ZKLib butuh ekstensi PHP "sockets". Aktifkan php_sockets di php.ini lalu restart Apache.');
        }

        spl_autoload_register([$this, 'autoload'], true, true);
    }

    protected function autoload($class)
    {
        $len = strlen($this->prefix);
        if (strncmp($this->prefix, $class, $len) !== 0) return;

        $relative = substr($class, $len);
        $file = $this->baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        if (is_file($file)) require $file;
    }
}
