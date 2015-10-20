<?php

namespace Onebip;

/*
 * Iterate over an array and apply a callback to each value.
 *
 * Examples:
 *    $this->assertSame(
 *        [2, 4, 6],
 *        array_map([1, 2, 3], function($value) { return $value * 2; })
 *    );
 */
function array_map(array $array, callable $mapper = null)
{
    $mapped = [];
    $mapper = $mapper ?: function($value) { return $value; };
    foreach ($array as $key => $value) {
        $mapped[] = call_user_func($mapper, $value, $key, $array);
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
function array_pluck(array $arrays, $column)
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
function array_flatten(array $array)
{
    return array_reduce(
        $array,
        function($acc, $item) {
            if (is_array($item)) {
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
function array_some(array $array, callable $predicate)
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
function array_group_by(array $array, callable $f = null)
{
    if ($f === null) {
        return array_reduce(
            $array,
            function($buckets, $x) use ($f) {
                if (!array_key_exists($x, $buckets)) {
                    $buckets[$x] = [];
                }

                $buckets[$x][] = $x;
                return $buckets;
            },
            []
        );
    } else {
        return array_reduce(
            $array,
            function($buckets, $x) use ($f) {
                $key = $f($x);

                if (!array_key_exists($key, $buckets)) {
                    $buckets[$key] = [];
                }

                $buckets[$key][] = $x;
                return $buckets;
            },
            []
        );
    }
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
