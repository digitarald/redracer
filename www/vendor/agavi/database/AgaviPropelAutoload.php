<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2008 the Agavi Project.                                |
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
 * A file that serves as the autoload file for Propel < 1.3
 * It is enabled by default in the Agavi autoload configuration.
 * Whenever you want to use Propel now, it will automatically be setup for you
 * using the configuration file you specified in database.xml
 * 
 * @package    agavi
 * @subpackage database
 * 
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.10.0
 *
 * @version    $Id: AgaviPropelAutoload.php 2259 2008-01-03 16:57:11Z david $
 */

// we don't need a _once here
require('propel/Propel.php');

Propel::init(AgaviPropelDatabase::getDefaultConfigPath());

?>