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
 * Determines whether a given directory is a valid Agavi project.
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
 * @version    $Id: AgaviProjectFilesystemCheck.class.php 2596 2008-07-09 10:15:10Z impl $
 */
class AgaviProjectFilesystemCheck extends AgaviFilesystemCheck
{
	/**
	 * @var        string The relative path to the project application
	 *                    directory.
	 */
	protected $appDirectory = 'app';
	
	/**
	 * @var        string The relative path to the project public
	 *                    directory.
	 */
	protected $pubDirectory = 'pub';

	/**
	 * Sets the application directory.
	 *
	 * @param      string The application directory.
	 */
	public function setAppDirectory($appDirectory)
	{
		$this->appDirectory = $appDirectory;
	}
	
	/**
	 * Gets the application directory.
	 *
	 * @return     string The application directory.
	 */
	public function getAppDirectory()
	{
		return $this->appDirectory;
	}
	
	/**
	 * Sets the public directory.
	 *
	 * @param      string The public directory.
	 */
	public function setPubDirectory($pubDirectory)
	{
		$this->pubDirectory = $pubDirectory;
	}
	
	/**
	 * Gets the public directory.
	 *
	 * @return     string The public directory.
	 */
	public function getPubDirectory()
	{
		return $this->pubDirectory;
	}
	
	/**
	 * Determines whether a given directory is a valid Agavi project.
	 *
	 * @return     bool True if the directory is valid; false otherwise.
	 */
	public function check()
	{
		$path = $this->getPath();
		if(is_dir($path)) {
			if(is_dir($path . '/' . $this->appDirectory) && is_dir($path . '/' . $this->pubDirectory)) {
				if(file_exists($path . '/' . $this->appDirectory . '/config.php')) {
					return true;
				}
			}
		}
		return false;
	}
}

?>