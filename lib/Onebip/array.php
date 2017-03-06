<?php

namespace Onebip;

use InvalidArgumentException;

/*
 * Variant of PHP's array_reduce, but it supports any traversable in input
 * (arrays or objects instance Traversable) and is sensible to associative
 * arrays, other than normal arrays.
 */
function array_reduce($array, callable $f, $acc)
{
    foreach ($array as $key => $value) {
        $acc = call_user_func($f, $acc, $value, $key);
    }

    return $acc;
}

/*
 * Concatenates every parameters in an array. Parameters could be scalar values
 * or arrays. Doesn't preserve keys.
 *
 * Examples:
 *    $this->assertSame(
 *        [1, 2, 3, 4],
 *        array_concat(1, [2, 3], [4])
 *    );
 */
function array_concat(/* $element, ... */)
{
    $concatenated = [];
    $arguments = func_get_args();
    foreach ($arguments as $argument) {
        if (is_array($argument)) {
            foreach ($argument as $element) {
                $concatenated[] = $element;
            }
        } else {
            $concatenated[] = $argument;
        }
    }
    return $concatenated;
}


/*
 * Merges two array recursively, it behaves like `array_merge` from
 * the standard library but applied recursively. There's a difference
 * between this implementation and `array_merge_recursive` from the
 * standard library: `array_merge_recursive` when two values need
 * to be merged and they are not array, an array will be created
 * with both values, this function will keep the second value and
 * discard the first
 *
 * Examples:
 *    $this->assertSame(
 *        ['a' => [1, 2, 3, 4]],
 *        array_merge(['a' => [1, 2]], ['a' => [3, 4]]))
 *    );
 *
 *    $this->assertSame(
 *        ['a' => 1, 'b' => 2],
 *        array_merge(['a' => 1], ['b' => 2]))
 *    );
 *
 *    $this->assertSame(
 *        ['a' => 2],
 *        array_merge(['a' => 1], ['a' => 2]))
 *    );
 */
function array_merge(/* $array, ... */)
{
    $merged = [];
    $arrays = array_reverse(func_get_args());
    while (!empty($arrays)) {
        $current = array_shift($arrays);
        if (!is_array($current)) {
            $current = [$current];
        }
        foreach (array_reverse($current) as $key => $value) {
            if (array_key_exists($key, $merged)) {
                if (is_array($value) && is_array($merged[$key])) {
                    $merged[$key] = array_merge($value, $merged[$key]);
                }
                if (is_numeric($key)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }
    }
    return array_reverse($merged);
}


/*
 * Iterate over an array and apply a callback to each value.
 *
 * Examples:
 *    $this->assertSame(
 *        [2, 4, 6],
 *        array_map([1, 2, 3], function($value) { return $value * 2; })
 *    );
 */
function array_map($array, callable $mapper = null, $preserveKeys = false)
{
    $mapped = [];
    $mapper = $mapper ?: function($value) { return $value; };
    if ($preserveKeys) {
        foreach ($array as $key => $value) {
            $mapped[$key] = call_user_func($mapper, $value, $key, $array);
        }
    } else {
        foreach ($array as $key => $value) {
            $mapped[] = call_user_func($mapper, $value, $key, $array);
        }
    }
    return $mapped;
}

/*
 * Pluck a column from an array.
 *
 * Examples:
 *    $this->assertSame(
 *        ['bar', 'bar'],
 *        array_pluck([['foo' => 'bar', 'bis' => 'ter'],
 *                     ['foo' => 'bar', 'bis' => 'ter']],
 *                    'foo')
 *    );
 */
function array_pluck($arrays, $column)
{
    $plucked = [];
    foreach ($arrays as $array) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($key === $column) {
                    $plucked[] = $value;
                    continue 2;
                }
            }
        }
        $plucked[] = null;
    }
    return $plucked;
}

/*
 * Flattens nested arrays.
 *
 * Examples:
 *
 *    $this->assertSame(
 *        [1, 2, 3, 4, 5],
 *        array_flatten([1, [2, [3, [4, 5]]]])
 *    );
 */
function array_flatten($array)
{
    return array_reduce(
        $array,
        function($acc, $item) {
            if (is_array($item) || $item instanceof \Traversable) {
                return array_merge($acc, array_flatten($item));
            } else {
                $acc[] = $item;
                return $acc;
            }
        },
        []
    );
}

/*
 * Returns whether every element of the array satisfies the given predicate or not.
 * Works with Iterators too.
 *
 * Examples:
 *
 *     $this->assertTrue([], function () {});
 *     $this->assertTrue(
 *         array_all([2, 4, 6], function ($n) {
 *            return $n % 2 === 0;
 *         })
 *     )
 *     $this->assertFalse(
 *         array_all([2, 4, 5], function ($n) {
 *            return $n % 2 === 0;
 *         })
 *     )
 */
function array_all($array, callable $predicate)
{
    foreach ($array as $key => $value) {
        if (!call_user_func($predicate, $value, $key, $array)) {
            return false;
        }
    }
    return true;
}

/*
 * Returns true if some items in an array match a truth test
 *
 * Examples:
 *
 *      $this->assertTrue(
 *          array_some([1, 2, 3], function($value, $key) {
 *              return $value % 2 === 0;
 *          })
 *      );
 */
function array_some($array, callable $predicate)
{
    foreach ($array as $key => $value) {
        if (call_user_func($predicate, $value, $key, $array)) {
            return true;
        }
    }
    return false;
}

/*
 * Returns the cartesian product of elements in the passed arrays.
 *
 * Examples:
 *
 *     $this->assertSame(
 *         [[1, 3], [1, 4], [2, 3], [2, 4]],
 *         array_cartesian_product([[1, 2], [3, 4]])
 *     );
 */
function array_cartesian_product(array $arrays)
{
    if (empty($arrays)) {
        return [[]];
    }

    $subset = array_shift($arrays);
    $cartesianSubset = array_cartesian_product($arrays);
    $product = [];

    foreach ($subset as $value) {
        foreach ($cartesianSubset as $p) {
            array_unshift($p, $value);
            $product[] = $p;
        }
    }

    return $product;
}

/*
 * Separates elements from an array into groups.
 * The function maps an element to the key that will be used for grouping.
 * If no function is passed, the element itself will be used as key.
 *
 *    Examples:
 *
 *        $this->assertSame(
 *            [
 *                1 => [1],
 *                2 => [2, 2],
 *                3 => [3, 3],
 *                4 => [4],
 *            ],
 *            array_group_by([1, 2, 2, 3, 3, 4])
 *        );
 *
 *        $this->assertSame(
 *            [
 *                1 => [1, 3, 5],
 *                0 => [2, 4, 6],
 *            ],
 *            array_group_by(
 *                [1, 2, 3, 4, 5, 6],
 *                function ($n) {
 *                    return $n % 2;
 *                }
 *            )
 *        );
 */
function array_group_by($array, callable $f = null)
{
    $f = $f ?: function($value) { return $value; };
    return array_reduce(
        $array,
        function($buckets, $x) use ($f) {
            $key = call_user_func($f, $x);
            if (!array_key_exists($key, $buckets)) {
                $buckets[$key] = [];
            }
            $buckets[$key][] = $x;
            return $buckets;
        },
        []
    );
}

/**
 * Transforms a 1-dimensional array into a multi-dimensional one,
 * exploding keys according to a separator.
 *
 * Examples:
 *       $this->assertSame(
 *           [
 *               'key' => [
 *                   'sub1' => 'value1',
 *                   'sub2' => 'value2',
 *                   'sub3' => [
 *                       'sub4' => 'value3',
 *                   ],
 *               ],
 *               'answer' => 42,
 *           ],
 *           array_as_hierarchy([
 *               'key.sub1' => 'value1',
 *               'key.sub2' => 'value2',
 *               'key.sub3.sub4' => 'value3',
 *               'answer' => 42,
 *           ])
 *       );
 *
 * Beware: this method uses references, only modify it
 * if you know what you're doing.
 */
function array_as_hierarchy(array $array, $separator = '.')
{
    $hierarchy = [];
    foreach ($array as $key => $value) {
        $segments = explode($separator, $key);
        $valueSegment = array_pop($segments);
        $branch = &$hierarchy;
        foreach ($segments as $segment) {
            if (!isset($branch[$segment])) {
                $branch[$segment] = [];
            }
            $branch = &$branch[$segment];
        }
        $branch[$valueSegment] = $value;
    }
    return $hierarchy;
}

/**
 * Tells if it's a numeric array or not
 *
 * Examples:
 *       $this->assertTrue(is_numeric_array([1,2,3]));
 *       $this->assertTrue(is_numeric_array(['foo', 'bar']));
 *       $this->assertFalse(is_numeric_array(['field-1' => 1, 'field-2' => 2]));
 *       $this->assertFalse(is_numeric_array([1, 2, 'field' => 3]));
 */
function is_numeric_array(array $array)
{
    foreach ($array as $key => $_) {
        if (!is_integer($key)) {
            return false;
        }
    }
    return true;
}

/**
 * Safe access to array, with optional fallback.
 *
 * Examples:
 *      array_fetch(['foo'], 0) -> 'foo'
 *      array_fetch([], 0, 'bar') -> 'bar'
 *      array_fetch([], 0, function($i) { return $i + 10; }) -> 10
 */
function array_fetch($array, $key /* plus optional $fallback */) {
    $presence = array_key_exists($key, $array);

    if ($presence) {
        return $array[$key];
    }

    if (func_num_args() < 3) {
        throw new InvalidArgumentException("key not found $key");
    }

    $fallback = func_get_arg(2);

    if (is_callable($fallback)) {
        return $fallback($key);
    } else {
        return $fallback;
    }
}

/**
 * Updates an element of the array using a function, only if the value
 * is present and it's not null.
 *
 * Examples:
 *   array_update(['a' => 0], 'a', function ($n) { return $n + 1; })
 *      -> ['a' => 0]
 *   array_update([], 0, function ($n) { return $n + 1; }) -> []
 */
function array_update($array, $key, callable $f)
{
    if (isset($array[$key])) {
        $array[$key] = $f($array[$key]);
    }

    return $array;
}

/**
 * Returns the maximum value in the array, or null when array is empty.
 */
function array_max($array)
{
    if (empty($array)) {
        return null;
    }

    if (is_array($array)) {
        $max = $array[0];
        $tail = array_slice($array, 1);
    } else {
        $max = $array->current();
        $array->next();
        $tail = $array;
    }

    return array_reduce(
        $tail,
        function ($max, $value) {
            if ($value > $max) {
                return $value;
            } else {
                return $max;
            }
        },
        $max
    );
}

/**
 * Returns the value in a nested associative structure,
 * where $path is an array of keys. Returns null if the key
 * is not present, or the $default value if supplied.
 */
function array_get_in($array, array $path, $default = null)
{
    if (empty($path)) {
        return $array;
    }

    $head = array_shift($path);

    if (!is_array($array) || !array_key_exists($head, $array)) {
        return $default;
    }

    return array_get_in($array[$head], $path, $default);
}

/**
 * Is array1 a subset of array2?
 */
function array_subset(array $array1, array $array2)
{
    return (count($array1) <= count($array2)) &&
        array_all($array1, function ($elem) use ($array2) {
            return in_array($elem, $array2);
        });
}

/**
 * Returns a the element for which predicate returns true.
 */
function array_find($array, callable $pred)
{
    foreach ($array as $elem) {
        if (call_user_func($pred, $elem, $array)) {
            return $elem;
        }
    }
}
