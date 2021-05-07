<?php

function array2string4insertion(array $a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . "'" . $x . "', ";
    }
    return "(" . substr($ret, 0, -2) . ")";
}

function array2string4display(array $a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . $x . ", ";
    }
    return substr($ret, 0, -2);
}

function array2multiChoice(array $a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . '<option value="' . $x . '">' . $x . '</option>\n';
    }
    return $ret;
}

function testInput(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}