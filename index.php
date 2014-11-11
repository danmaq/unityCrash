<?php

error_reporting(E_ALL|E_STRICT);

require 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();


header('text/plain');
header('Pragma: no-cache');
header('Cache-Control: no-cache');

$hello = new UnityCrash\HelloWorld();
echo($hello->getHello() . "\n");
if (isset($_SERVER['QUERY_STRING']))
{
	echo($_SERVER['QUERY_STRING'] . "\n");
}
