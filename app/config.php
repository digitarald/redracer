<?php

// let's make a generic project dir setting
AgaviConfig::set('core.project_dir', realpath(dirname(__FILE__) . '/../'));

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to your web application directory. This       |
// | directory is the root of your web application, which includes the core    |
// | configuration files and related web application data.                     |
// | You shouldn't have to change this usually since it's auto-determined.     |
// | Agavi can't determine this automatically, so you always have to supply it.|
// +---------------------------------------------------------------------------+
AgaviConfig::set('core.app_dir', AgaviConfig::get('core.project_dir') . '/app');

// +---------------------------------------------------------------------------+
// | You may also modify the following other directives in this file:          |
// |  - core.config_dir   (defaults to "<core.app_dir>/config")                |
// |  - core.lib_dir      (defaults to "<core.app_dir>/lib")                   |
// |  - core.model_dir    (defaults to "<core.app_dir>/models")                |
// |  - core.module_dir   (defaults to "<core.app_dir>/modules")               |
// |  - core.template_dir (defaults to "<core.app_dir>/templates")             |
// +---------------------------------------------------------------------------+

// the folder with customizations
AgaviConfig::set('core.custom_dir', AgaviConfig::get('core.project_dir') . '/custom');
// holds the 3rd party libraries
AgaviConfig::set('core.vendor_dir', AgaviConfig::get('core.project_dir') . '/vendor');

// Report all errors during development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Custom error handler, to throw nice-looking error exceptions
function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
	$report = error_reporting();
	if ($report && $report & $errno) {
	  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
}
set_error_handler('errorHandler');


ini_set('session.use_trans_sid', '0');

set_include_path(AgaviConfig::get('core.vendor_dir') . PATH_SEPARATOR . get_include_path());

define('Auth_OpenID_RAND_SOURCE', null);

define('Auth_OpenID_BUGGY_GMP', true);

?>