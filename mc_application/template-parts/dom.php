<?php

function div(...$args): string
{
    return el("div", ...$args);
}

function input(...$args): string
{

    return el("input", ...$args);
}

function textarea(...$args): string
{
    return el('textarea', ...$args);
}

function details(...$args): string
{
    return el('details', ...$args);
}

function summary(...$args): string
{
    return el('summary', ...$args);
}

function img(...$args): string
{
    return el('img', ...$args);
}

function el(...$args): string
{
    $num_args = func_num_args();

    $tag = $args[0];
    $attr = $num_args > 1 && is_array($args[1]) ? $args[1] : [];
    $closable = !("input" === $tag || 'img'==$tag);

    $start = 2;
    if ($num_args > 1 && !is_array($args[1])) {
        $start = 1;
    }

    $inner = join("\n", array_slice($args, $start, $num_args));

    $finalAttr = [];
    if (is_array($attr)) {
        foreach ($attr as $key => $value) {
            if (is_null($value)) {
                $finalAttr[] = "$key";
            } else {
                $finalAttr[] = "$key=\"$value\"";
            }
        }
    }

    $attrs = join(" ", $finalAttr);
    $closeSlash = $closable ? "" : "/";
    $html = "<$tag $attrs $closeSlash>";

    $html .= $inner;

    if ($closable) $html .= "</$tag>";

    return $html;
}