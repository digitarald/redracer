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
 * Displays all actions in an Agavi module.
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
 * @version    $Id: AgaviDisplayactionsTask.php 2596 2008-07-09 10:15:10Z impl $
 */
class AgaviDisplayactionsTask extends AgaviTask
{
	protected $path = null;
	
	/**
	 * Sets the path to the project directory from which this task will read.
	 *
	 * @param      PhingFile Path to the project directory.
	 */
	public function setPath(PhingFile $path)
	{
		$this->path = $path;
	}
	
	/**
	 * Executes this task.
	 */
	public function main()
	{
		if($this->path === null) {
			throw new BuildException('The path attribute must be specified');
		}
		
		$check = new AgaviModuleFilesystemCheck();
		$check->setActionsDirectory($this->project->getProperty('module.directory.actions'));
		$check->setViewsDirectory($this->project->getProperty('module.directory.views'));
		$check->setTemplatesDirectory($this->project->getProperty('module.directory.templates'));
		$check->setConfigDirectory($this->project->getProperty('module.directory.config'));
		
		$check->setPath($this->path->getAbsolutePath());
		if(!$check->check()) {
			throw new BuildException('The path attribute must be a valid module base directory');
		}
		
		$actions = array();
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path->getAbsolutePath() . DIRECTORY_SEPARATOR . $this->project->getProperty('module.directory.actions')));
		for(; $iterator->valid(); $iterator->next()) {
			$rdi = $iterator->getInnerIterator();
			if($rdi->isDot() || !$rdi->isFile()) {
				continue;
			}
			
			$file = $rdi->getSubpathname();
			if(preg_match('#Action\.class\.php$#', $file)) {
				$this->log(str_replace(DIRECTORY_SEPARATOR, '.', substr($file, 0, -16 /* strlen('Action.class.php') */)), Project::MSG_INFO);
			}
		}
	}
}

?>
