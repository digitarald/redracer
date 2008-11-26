<?php

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the agavi/agavi.php script.                |
// +---------------------------------------------------------------------------+
require('../vendor/agavi/agavi.php');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to our app/config.php script.                 |
// +---------------------------------------------------------------------------+
require('../app/config.php');

// +---------------------------------------------------------------------------+
// | Initialize the framework. You may pass an environment name to this method.|
// | By default the 'development' environment sets Agavi into a debug mode.    |
// | In debug mode among other things the cache is cleaned on every request.   |
// +---------------------------------------------------------------------------+
Agavi::bootstrap('development-digitarald');

/*
$manager = Doctrine_Manager::getInstance();

$cacheDriver = new Doctrine_Cache_Apc();
$manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
$manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
*/

// +---------------------------------------------------------------------------+
// | Call the controller's dispatch method on the default context              |
// +---------------------------------------------------------------------------+
AgaviContext::getInstance('web')->getController()->dispatch();

?>