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
 * AgaviStdoutLoggerAppender appends an AgaviLoggerMessage to stdout.
 *
 * @package    agavi
 * @subpackage logging
 *
 * @author     Bob Zoller <bob@agavi.org>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.10.0
 *
 * @version    $Id: AgaviStdoutLoggerAppender.class.php 2522 2008-06-18 18:37:51Z david $
 */
class AgaviStdoutLoggerAppender extends AgaviStreamLoggerAppender
{
	/**
	 * Initialize the object.
	 *
	 * @param      AgaviContext An AgaviContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Bob Zoller <bob@agavi.org>
	 * @since      0.10.0
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		$parameters['destination'] = 'php://stdout';
		// 'a' doesn't work on Linux
		$parameters['mode'] = 'w';
		
		parent::initialize($context, $parameters);
	}
}

?>