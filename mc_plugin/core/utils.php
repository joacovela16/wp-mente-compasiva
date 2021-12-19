<?php
function mc_get_datetime()
{
    return date("Y-m-d H:i:s");
}

function runQuery(array $q)
{
    global $wpdb;
    foreach ($q as $item) {
        $wpdb->query($item);
    }
}

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