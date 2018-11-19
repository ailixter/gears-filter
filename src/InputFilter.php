<?php

/*
 * (C) 2018, AII (Alexey Ilyin).
 */

namespace Ailixter\Gears\Filter;

/**
 * @author AII (Alexey Ilyin)
 */
class InputFilter extends AbstractDataFilter
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
        return $this->filter->castInput($type, $this->data, $key, $default);
    }
    /**
     * @param iterable $casts
     * @return array
     */
    public function castAll($casts)
    {
        return $this->filter->castInputArray($casts, $this->data, true);
    }
}
