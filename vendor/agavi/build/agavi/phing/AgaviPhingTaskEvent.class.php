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
 * Represents an event that occurred within a Phing target.
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
 * @version    $Id: AgaviPhingTaskEvent.class.php 2596 2008-07-09 10:15:10Z impl $
 */
class AgaviPhingTaskEvent extends AgaviPhingTargetEvent
{
	protected $task = null;
	
	/**
	 * Sets the task that generated this event.
	 *
	 * @param      Task The task.
	 */
	public function setTask(Task $task)
	{
		$this->task = $task;
	}
	
	/**
	 * Gets the target that generated this event.
	 *
	 * @return     Task The task.
	 */
	public function getTask()
	{
		return $this->task;
	}
}

?>
