<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2003-2006 the Agavi Project                                 |
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
 * AgaviTimestampLoggerLayout prepends the current date and time to the message.
 *
 * @package    agavi
 * @subpackage logging
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviTimestampLoggerLayout.class.php 1784 2007-02-23 11:53:51Z david $
 */
class AgaviTimestampLoggerLayout extends AgaviLoggerLayout
{
	/**
	 * Format a message.
	 *
	 * @param      AgaviLoggerMessage An AgaviLoggerMessage instance.
	 *
	 * @return     string A formatted message.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function format(AgaviLoggerMessage $message)
	{
		return sprintf($this->getParameter('message_format', '[%1$s] %2$s'), strftime($this->getParameter('timestamp_format', '%c')), $message->__toString());
	}
}

?>