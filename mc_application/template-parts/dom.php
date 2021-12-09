<?php


function div($attr, $inner = null)
{
    el("div", $attr, $inner);
}

function el(string $tag, $attr, $inner = null, $closable = true)
{
    $finalAttr = [];
    if (is_array($attr)) {
        foreach ($attr as $key => $value) {
            if (is_null($value)) {
                $finalAttr[] = "$key";
            } else {
                $finalAttr[] = "$key=\"$value\"";
            }
        }
    } elseif (is_string($attr)) {
        $inner = $attr;
    }

    $attrs = join(" ", $finalAttr);
    $closeSlash = $closable ? "": "/";
    $html= "<$tag $attrs $closeSlash>";

    if (!is_null($inner)) {
        if (is_callable($inner))
        $html.= $inner();
        elseif (is_string($inner))
        $html.= $inner;
    }

    if ($closable) $html.= "</$tag>";

    return $html;
}