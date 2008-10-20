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

require_once('phing/listener/AnsiColorLogger.php');

/**
 * Default logger for Agavi Phing build events.
 *
 * @package    agavi
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      1.0.0
 *
 * @version    $Id: AgaviBuildLogger.php 3053 2008-10-19 06:54:43Z impl $
 */
class AgaviBuildLogger extends AnsiColorLogger
{
	public function buildStarted(BuildEvent $event)
	{
		
	}
	
	public function buildFinished(BuildEvent $event)
	{
		$exception = $event->getException();
		if($exception !== null) {
			$this->printMessage(str_pad('[error] ', DefaultLogger::LEFT_COLUMN_SIZE, ' ', STR_PAD_LEFT) . $exception->getMessage(), $this->out, $event->getPriority());
		}
	}
}

?>
