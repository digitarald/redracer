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
 * Proxies a target from an external build file.
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
 * @version    $Id: AgaviProxyTarget.class.php 2596 2008-07-09 10:15:10Z impl $
 */
class AgaviProxyTarget extends Target
{
	/**
	 * The proxied target.
	 *
	 * @var        Target
	 */
	protected $target;
	
	/**
	 * Sets the proxied target.
	 *
	 * @param      Target The target to proxy.
	 */
	public function setTarget(Target $target)
	{
		$this->target = $target;
	}
	
	/**
	 * Gets the proxied target.
	 *
	 * @return     Target The proxied target.
	 */
	public function getTarget()
	{
		return $this->target;
	}
	
	/**
	 * Proxies task adding.
	 *
	 * @param      Task The task that is being added.
	 */
	public function addTask(Task $task)
	{
		if($this->target === null) {
			throw new BuildException('Tasks can not be added to a proxy target without a concrete target');
		}
		$task->setOwningTarget($this->target);
		$this->target->addTask($task);
	}
	
	/**
	 * Proxies datatype adding.
	 *
	 * @param      DataType The datatype that is being added.
	 */
	public function addDataType($type)
	{
		if($this->target === null) {
			throw new BuildException('Datatypes can not be added to a proxy target without a concrete target');
		}
		$this->target->addDataType($type);
	}
	
	/**
	 * Proxies if-conditional adding.
	 *
	 * @param      string The condition.
	 */
	public function setIf($property)
	{
		if($this->target === null) {
			throw new BuildException('Tasks can not be added to a proxy target without a concrete target');
		}
		$this->target->setIf($property);
	}
	
	/**
	 * Proxies unless-conditional adding.
	 *
	 * @param      string The condition.
	 */
	public function setUnless($property)
	{
		if($this->target === null) {
			throw new BuildException('Tasks can not be added to a proxy target without a concrete target');
		}
		$this->target->setUnless($property);
	}
	
	/**
	 * Executes this target.
	 */
	public function main()
	{
		$thisProject = $this->getProject();
		$project = $this->target->getProject();
		
		Phing::setCurrentProject($project);
		chdir($project->getBasedir()->getAbsolutePath());
		
		/* Assign properties for consistency. */
		$thisProject->copyUserProperties($project);
		$thisProject->copyInheritedProperties($project);
		foreach($thisProject->getProperties() as $name => $property) {
			if(!AgaviProxyProject::isPropertyProtected($name) && $project->getProperty($name) === null) {
				$project->setNewProperty($name, $property);
			}
		}
		
		/* Execute the proxied target. */
		$project->executeTarget($this->target->getName());
		
		Phing::setCurrentProject($thisProject);
		chdir($thisProject->getBasedir()->getAbsolutePath());
		
		$project->copyUserProperties($thisProject);
		$project->copyInheritedProperties($thisProject);
		foreach($project->getProperties() as $name => $property) {
			if(!AgaviProxyProject::isPropertyProtected($name) && $thisProject->getProperty($name) === null) {
				$thisProject->setNewProperty($name, $property);
			}
		}
	}
}

?>
