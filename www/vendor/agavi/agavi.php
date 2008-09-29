<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2003-2008 the Agavi Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * Pre-initialization script.
 *
 * @package    agavi
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @author     Mike Vincent <mike@agavi.org>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: agavi.php 2258 2008-01-03 16:54:04Z david $
 */

// load the AgaviConfig class
require(dirname(__FILE__) . '/config/AgaviConfig.class.php');

// check minimum PHP version
AgaviConfig::set('core.minimum_php_version', '5.1.3');
if(!version_compare(PHP_VERSION, AgaviConfig::get('core.minimum_php_version'), 'ge') ) {
	die('You must be using PHP version ' . AgaviConfig::get('core.minimum_php_version') . ' or greater.');
}

// define a few filesystem paths
AgaviConfig::set('core.agavi_dir', dirname(__FILE__), true, true);

// default exception template
AgaviConfig::set('exception.default_template', AgaviConfig::get('core.agavi_dir') . '/exception/templates/shiny.php');

// required files
require(AgaviConfig::get('core.agavi_dir') . '/version.php');
require(AgaviConfig::get('core.agavi_dir') . '/core/Agavi.class.php');

?>