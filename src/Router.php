<?php
namespace baohan\SwooleJob;

use baohan\Collection\Collection;
use baohan\SwooleJob\Input\Event;

class Router
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $executor;

    /**
     * custom unserialize method
     *
     * @var Callable
     */
    protected $decode;

    public function __construct()
    {
        $this->decode = function($payload) {
            return $payload;
        };
    }

    /**
     * @param Callable $decode
     */
    public function setDecode($decode)
    {
        $this->decode = $decode;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function callback($data)
    {
        try {
            $decode = $this->decode;
            $ev = new Event(new Collection($decode($data)));
            $class = $this->getJobClassName($ev->getEvent());
            if(!class_exists($class)) throw new \Exception('No found class: ' . $class);
            $job = new $class;
            return $job->{$this->executor}($ev->getPayload());
        } catch (\Exception $e) {
            echo "Caught Exception: " . $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $name
     */
    public function setExecutor($name)
    {
        $this->executor = $name;
    }

    /**
     * @param $key
     * @return string
     */
    protected function getJobClassName($key) {
        $class = $this->prefix;
        $parts = explode('::', $key);
        foreach($parts as &$part) $part = ucwords($part);
        $class .= implode("\\", $parts);
        return $class;
    }
}