<?php

require 'vendor/autoload.php';

function setAttr($value, $attr, $object)
{
    $closure = function () use ($value, $attr, $object) {
        $object->{$attr} = $value;
    };

    $closure->call($object);
}
