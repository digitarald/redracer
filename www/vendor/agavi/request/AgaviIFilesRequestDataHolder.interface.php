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
 * Interface for RequestDataHolders that allow access to Files.
 *
 * @package    agavi
 * @subpackage request
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviIFilesRequestDataHolder.interface.php 2259 2008-01-03 16:57:11Z david $
 */
interface AgaviIFilesRequestDataHolder
{
	public function hasFile($file);
	
	public function isFileValueEmpty($file);
	
	public function &getFile($file, $default = null);
	
	public function &getFiles();
	
	public function getFileNames();
	
	public function getFlatFileNames();
	
	public function setFile($name, $value);
	
	public function setFiles(array $files);
	
	public function &removeFile($file);
	
	public function clearFiles();
	
	public function mergeFiles(AgaviRequestDataHolder $other);
}

?>