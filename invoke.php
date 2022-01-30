<?php
require __DIR__ . '/vendor/autoload.php';

$function = include $argv[1] . '.php';
$function('');