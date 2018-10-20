<?php

/*
 * (C) 2018, AII (Alexey Ilyin).
 */

namespace Ailixter\Gears\Filter;

/**
 * @author AII (Alexey Ilyin)
 */
class Filter
{
    private $formats = [
        'int' => [
            'filter' => FILTER_VALIDATE_INT,
            'flags'  => FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX
        ],
        'float' => [
            'filter' => FILTER_VALIDATE_FLOAT
        ],
        'bool' => [
            'filter' => FILTER_VALIDATE_BOOLEAN,
            'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'str' => [
            'filter' => FILTER_DEFAULT
        ],
    ];

    /**
     * Pre-defined formats:
     *  - int
     *  - float
     *  - bool
     *  - str
     * @param iterable $formats - follows filter specifications.
     */
    public function __construct($formats = false)
    {
        if ($formats) {
            foreach ($formats as $key => $value) {
                $this->formats[$key] = $value;
            }
        }
    }

    protected function makeDescriptor($type, $default = null)
    {
        if (is_int($type)) {
            // simple filter
            $result = ['filter' => $type];
        } elseif (isset($type['filter'])) {
            // complex filter
            $result = $type;
        } elseif (isset($this->formats[(string)$type]['filter'])) {
            // pre-configured filter
            $result = $this->formats[(string)$type];
        } else {
            throw new \InvalidArgumentException(
                "undefined cast: '$type'"
            );
        }

        if (isset($default)) {
            $result['options']['default'] = $default;
        }

        return $result;
    }

    protected function makeFilterOptions($type, $default)
    {
        $descr = $this->makeDescriptor($type, $default);
        $filter = $descr['filter'];
        unset($descr['filter']);
        return [$filter, $descr];
    }

    /**
     * Casts $var into $type.
     *
     * <code>
     *  $filter = new Filter;
     *  $int1 = $filter->cast(FILTER_VALIDATE_INT, '123');
     *  $int2 = $filter->cast(['filter'=>FILTER_VALIDATE_INT, 'flags'=>...], '123');
     *  $int3 = $filter->cast('int', '123');
     * </code>
     * 
     * @param mixed $type
     * @param mixed $var
     * @param mixed $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function cast($type, $var, $default = null)
    {
        list($filter, $options) = $this->makeFilterOptions($type, $default);
        return filter_var($var, $filter, $options);
    }

    /**
     * Casts $array[$key] into $type.
     *
     * <code>
     *  $filter = new Filter;
     *  $arr = ['a' => '123'];
     *  $int1 = $filter->castItem(FILTER_VALIDATE_INT, $arr, 'a');
     *  $int2 = $filter->castItem(['filter'=>FILTER_VALIDATE_INT, 'flags'=>...], $arr, 'a');
     *  $int3 = $filter->castItem('int', $arr, 'a');
     * </code>
     * 
     * @param mixed  $type
     * @param array  $array
     * @param scalar $key
     * @param mixed  $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function castItem($type, $array, $key, $default = null)
    {
        return $this->cast($type, isset($array[$key]) ? $array[$key] : null, $default);
    }

    /**
     * Casts input $key into $type.
     *
     * <code>
     *  $filter = new Filter;
     *  $int1 = $filter->castInput(FILTER_VALIDATE_INT, INPUT_POST, 'p');
     *  $int2 = $filter->castInput(['filter'=>FILTER_VALIDATE_INT, 'flags'=>...], INPUT_POST, 'p');
     *  $int3 = $filter->castInput('int', INPUT_POST, 'p');
     * </code>
     * 
     * @param mixed $type
     * @param int $input
     * @param scalar $key
     * @param mixed $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function castInput($type, $input, $key, $default = null)
    {
        list($filter, $options) = $this->makeFilterOptions($type, $default);
        return filter_input($input, $key, $filter, $options);
    }
    
    protected function makeDescriptorArray($casts)
    {
        $result = [];
        foreach ($casts as $key => $type) {
            $result[$key] = $this->makeDescriptor($type);
        }
        return $result;
    }

    /**
     * Casts array-to-array.
     * 
     * <code>
     *  $filter = new Filter;
     *  $arr = ['a' => '123'];
     *  $int = $filter->castArray(['a' => 'int'], $arr);
     * </code>
     * 
     * @param iterable $casts - array of casts (see cast()) by key names.
     * @param array $array
     * @param bool $addEmpty
     * @return array
     * @see cast
     */
    public function castArray($casts, array $array, $addEmpty = true)
    {
        return filter_var_array($array, $this->makeDescriptorArray($casts), $addEmpty);
    }

    /**
     * Casts input into array.
     * 
     * <code>
     *  $filter = new Filter;
     *  $int = $filter->castInputArray(['p' => 'int'], INPUT_POST);
     * </code>
     * 
     * @param iterable $casts
     * @param int $input
     * @param bool $addEmpty
     * @return array
     */
    public function castInputArray($casts, $input, $addEmpty = true)
    {
        return filter_input_array($input, $this->makeDescriptorArray($casts), $addEmpty);
    }
}

