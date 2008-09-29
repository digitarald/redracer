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
 * Interface for RequestDataHolders that allow access to Headers.
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
 * @version    $Id: AgaviIHeadersRequestDataHolder.interface.php 2258 2008-01-03 16:54:04Z david $
 */
interface AgaviIHeadersRequestDataHolder
{
	public function hasHeader($header);
	
	public function isHeaderValueEmpty($header);
	
	public function &getHeader($header, $default = null);
	
	public function &getHeaders();
	
	public function getHeaderNames();
	
	public function setHeader($name, $value);
	
	public function setHeaders(array $headers);
	
	public function &removeHeader($header);
	
	public function clearHeaders();
	
	public function mergeHeaders(AgaviRequestDataHolder $other);
}

?>