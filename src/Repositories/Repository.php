<?php

namespace DonePM\ConsoleClient\Repositories;

/**
 * Class Repository
 *
 * @package DonePM\ConsoleClient\Repositories
 */
abstract class Repository
{
    /**
     * data
     *
     * @var array
     */
    private $data;

    /**
     * constructing Repository
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * returns data key
     *
     * @param string|int $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * sets data
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * does a key exists
     *
     * @param string|int $key
     *
     * @return bool
     */
    public function has($key)
    {
        if ( (! is_numeric($key) && array_key_exists($key, $this->data))
            || (is_numeric($key) && isset($this->data[$key]))) {
            return true;
        }

        return false;
    }

    /**
     * returns all data
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * resets all data
     *
     * @return $this
     */
    public function reset()
    {
        $this->data = [];

        return $this;
    }
}