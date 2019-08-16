<?php

/*
 * (C) 2018, AII (Alexey Ilyin).
 */

namespace Ailixter\Gears\Filter;

use Ailixter\Gears\Exceptions\MethodException;
use InvalidArgumentException;

/**
 * @author AII (Alexey Ilyin)
 */
abstract class AbstractDataFilter
{
    protected $data;
    /**
     * @var Filter
     */
    protected $filter;

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
    abstract public function get($type, $key, $default = null);
    /**
     * @param iterable $casts
     * @return array
     */
    abstract public function castAll($casts): array;

    public function getInt($key, $default = null): int
    {
        return $this->get('int', $key, $default);
    }

    public function getFloat($key, $default = null): float
    {
        return $this->get('float', $key, $default);
    }

    public function getBool($key, $default = null): bool
    {
        return $this->get('bool', $key, $default);
    }

    public function getStr($key, $default = null): string
    {
        return $this->get('str', $key, $default);
    }

    public function __call($name, $arguments)
    {
        if (\sscanf(\strtolower($name), 'get%s', $type) <= 0) {
            throw MethodException::forCall($this, $name);
        }
        if (!count($arguments)) {
            throw new InvalidArgumentException(
                "ArgumentCountError: Too few arguments to function '$name'"
            );
        }
        array_unshift($arguments, $type);
        return \call_user_func_array([$this, 'get'], $arguments);
    }
}
