<?php

require_once "vendor/autoload.php";
use MyCLabs\Enum\Enum;
final class Style extends Enum
{
    private const POP = 'pop';
    private const ROCK = 'rock';
    private const CLASSIC = 'classic';
    private const JAZZ = 'jazz';
}