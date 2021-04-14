<?php

require_once "vendor/autoload.php";
require_once "enum/Sex.php";
require_once "enum/Style.php";
require_once "MysqlConn.php";
require_once "utils.php";


MysqlConn::getMysqlConnection()->resetTables();