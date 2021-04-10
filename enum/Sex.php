<?php

require_once "vendor/autoload.php";
use MyCLabs\Enum\Enum;
final class Sex extends Enum
{
    private const MALE = 'male';
    private const FEMALE = 'female';
    private const GROUP = 'group';
    private const OTHERS = 'others';
}