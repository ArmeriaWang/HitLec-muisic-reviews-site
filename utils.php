<?php

function array2string($a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . "'" . $x . "', ";
    }
    return "(" . substr($ret, 0, -2) . ")";
}

function array2multiChoice($a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . '<option value="' . $x . '">' . $x . '</option>\n';
    }
    return $ret;
}