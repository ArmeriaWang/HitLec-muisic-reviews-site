<?php

require_once "vendor/autoload.php";
require_once "entity/enum/Sex.php";
require_once "entity/enum/Style.php";
require_once "MysqlConn.php";
require_once "utils.php";


MysqlConn::getMysqlConnection()->resetTables();