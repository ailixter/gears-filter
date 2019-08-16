<?php

/*
 * (C) 2018, AII (Alexey Ilyin).
 */

namespace Ailixter\Gears\Filter;

/**
 * @author AII (Alexey Ilyin)
 */
class ArrayFilter extends AbstractDataFilter
{
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
    /**
     * @param iterable $casts
     * @return array
     */
    public function castAll($casts): array
    {
        return $this->filter->castArray($casts, $this->dataArray(), true);
    }

    protected function dataArray()
    {
        return (array)$this->data;
    }
}
