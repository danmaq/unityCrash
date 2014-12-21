<?php

error_reporting(E_ALL | E_STRICT);

require 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\MyState\ControllerState;

$context = new Context(ControllerState::getInstance());
$context->loop();
