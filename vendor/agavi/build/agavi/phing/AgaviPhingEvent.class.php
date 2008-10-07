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
 * Represents an event that occurred within Phing.
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
 * @version    $Id: AgaviPhingEvent.class.php 2596 2008-07-09 10:15:10Z impl $
 */
class AgaviPhingEvent extends AgaviEvent
{
	protected $project = null;
	
	/**
	 * Sets the project that generated this event.
	 *
	 * @param      Project The project.
	 */
	public function setProject(Project $project)
	{
		$this->project = $project;
	}
	
	/**
	 * Gets the project that generated this event.
	 *
	 * @return     Project The project.
	 */
	public function getProject()
	{
		return $this->project;
	}
}

?>
