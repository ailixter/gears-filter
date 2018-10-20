# gears-filter
The project, which gears Filter functions.

Casts
- variable
- input GET, POST, etc.
- array

Cast $var into $type.
``` php
$filter = new Filter;
$int1 = $filter->cast(FILTER_VALIDATE_INT, '123');
$int2 = $filter->cast(['filter'=>FILTER_VALIDATE_INT, 'flags'=>...], '123');
$int3 = $filter->cast('int', '123');
```

Cast input $key into $type.
``` php
 $filter = new Filter;
 $int1 = $filter->castInput(FILTER_VALIDATE_INT, INPUT_POST, 'p');
 $int2 = $filter->castInput(['filter'=>FILTER_VALIDATE_INT, 'flags'=>...], INPUT_POST, 'p');
 $int3 = $filter->castInput('int', INPUT_POST, 'p');
```

Utility Filter-based class ArrayFilter
``` php
$array = new ArrayFilter(['a' => 123, 'b' => 'false'], new Filter);
$bool = $array->get('bool', 'b');
$float = $array->getFloat('a', 0.0);
$custom = $array->getCustomType('a');
$all = $array->castAll(['a' => 'float', 'b' => 'bool', 'undefined' => 'str']);
```

