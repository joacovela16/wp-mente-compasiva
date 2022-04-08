<?php


function array_find(array $array, Closure $test)
{
    $items = array_filter($array, $test);
    if (empty($items)) {
        return null;
    } else {
        return array_values($items)[0];
    }
}

function array_exists(array $array, Closure $test)
{
    $found = false;
    $iterator = new ArrayIterator($array);

    while ($found === false && $iterator->valid()) {
        if ($test($iterator->current())) {
            $found = true;
        }
        $iterator->next();
    }

    return $found;
}

function array_forall(array $array, Closure $test): bool
{

    $iterator = new ArrayIterator($array);
    while ($iterator->valid()) {
        if (!$test($iterator->current())) {
            return false;
        }
        $iterator->next();
    }

    return true;
}

function nonEmpty($obj): bool
{
    return !empty($obj);
}