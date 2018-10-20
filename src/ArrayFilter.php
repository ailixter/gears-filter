<?php

/*
 * (C) 2018, AII (Alexey Ilyin).
 */

namespace Ailixter\Gears\Filter;

/**
 * @author AII (Alexey Ilyin)
 */
class ArrayFilter
{

    private $data;
    private $filter;

    public function __construct($data, Filter $filter)
    {
        $this->data = $data;
        $this->filter = $filter;
    }

    /**
     * Get $key as $type.
     * @param mixed  $type {@see Filter::cast}
     * @param scalar $key
     * @param mixed  $default
     * @return mixed
     * 
     */
    public function get($type, $key, $default = null)
    {
        return $this->filter->castItem($type, $this->data, $key, $default);
    }

    public function getInt($key, $default = null)
    {
        return $this->filter->castItem('int', $this->data, $key, $default);
    }

    public function getFloat($key, $default = null)
    {
        return $this->filter->castItem('float', $this->data, $key, $default);
    }

    public function getBool($key, $default = null)
    {
        return $this->filter->castItem('bool', $this->data, $key, $default);
    }

    public function getStr($key, $default = null)
    {
        return $this->filter->castItem('str', $this->data, $key, $default);
    }

    public function __call($name, $arguments)
    {
        if (\sscanf(\strtolower($name), 'get%s', $type) <= 0) {
            throw new \RuntimeException(
                "unsupported method: '$name'"
            );
        }
        if (!count($arguments)) {
            throw new \InvalidArgumentException(
                "ArgumentCountError: Too few arguments to function '$name'"
            );
        }
        array_unshift($arguments, $this->data);
        array_unshift($arguments, $type);
        return \call_user_func_array([$this->filter, 'castItem'], $arguments);
    }

    /**
     * @param iterable $casts
     * @return array
     */
    public function castAll($casts)
    {
        return $this->filter->castArray($casts, $this->data, true);
    }
}
