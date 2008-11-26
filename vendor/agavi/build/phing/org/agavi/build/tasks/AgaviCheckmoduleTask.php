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

require_once(dirname(__FILE__) . '/AgaviTask.php');

/**
 * Validates that a given directory is a module directory for a project.
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
 * @version    $Id: AgaviCheckmoduleTask.php 3071 2008-10-20 06:08:55Z impl $
 */
class AgaviCheckmoduleTask extends AgaviTask
{
	protected $property = null;
	protected $path = null;
	protected $value = true;
	
	/**
	 * Sets the property that this task will modify.
	 *
	 * @param      string The property to modify.
	 */
	public function setProperty($property)
	{
		$this->property = $property;
	}
	
	/**
	 * Sets the path to use to validate the module.
	 *
	 * @param      string The path to use.
	 */
	public function setPath(PhingFile $path)
	{
		$this->path = $path;
	}
	
	/**
	 * Sets the value that the property will contain if the module is valid.
	 *
	 * @param      string The value to which the property will be set.
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Executes this target.
	 */
	public function main()
	{
		if($this->property === null) {
			throw new BuildException('The property attribute must be specified');
		}
		if($this->path === null) {
			throw new BuildException('The path attribute must be specified');
		}
		
		$check = new AgaviModuleFilesystemCheck();
		$check->setActionsDirectory($this->project->getProperty('module.directory.actions'));
		$check->setViewsDirectory($this->project->getProperty('module.directory.views'));
		$check->setTemplatesDirectory($this->project->getProperty('module.directory.templates'));
		$check->setConfigDirectory($this->project->getProperty('module.directory.config'));
		
		$check->setPath($this->path->getAbsolutePath());
		if($check->check()) {
			$this->project->setUserProperty($this->property, $this->value);
		}
		else {
			$this->project->setUserProperty($this->property, null);
		}
	}
}

?>