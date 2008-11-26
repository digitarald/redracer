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

require_once(dirname(__FILE__) . '/AgaviListenerTask.php');

/**
 * Defines a new listener on targets for this build environment.
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
 * @version    $Id: AgaviTargetListenerTask.php 3071 2008-10-20 06:08:55Z impl $
 */
class AgaviTargetListenerTask extends AgaviListenerTask
{
	public function main()
	{
		if($this->object === null) {
			throw new BuildException('The object attribute must be specified');
		}
		
		$objectType = $this->object->getReferencedObject($this->project);
		if(!$objectType instanceof AgaviObjectType) {
			throw new BuildException('The object attribute must be a reference to an Agavi object type');
		}
		
		$object = $objectType->getInstance();
		if(!$object instanceof AgaviIPhingTargetListener) {
			throw new BuildException(sprintf('Cannot add target listener: Object is of type %s which does not implement %s',
				get_class($object), 'AgaviIPhingTargetListener'));
		}
		
		$dispatcher = AgaviPhingEventDispatcherManager::get($this->project);
		$dispatcher->addTargetListener($object);
	}
}

?>