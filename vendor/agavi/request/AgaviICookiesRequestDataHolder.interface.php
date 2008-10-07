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
 * Interface for RequestDataHolders that allow access to Cookies.
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
 * @version    $Id: AgaviICookiesRequestDataHolder.interface.php 2259 2008-01-03 16:57:11Z david $
 */
interface AgaviICookiesRequestDataHolder
{
	public function hasCookie($cookie);
	
	public function isCookieValueEmpty($cookie);
	
	public function &getCookie($cookie, $default = null);
	
	public function &getCookies();
	
	public function getCookieNames();
	
	public function getFlatCookieNames();
	
	public function setCookie($name, $value);
	
	public function setCookies(array $cookies);
	
	public function &removeCookie($cookie);
	
	public function clearCookies();
	
	public function mergeCookies(AgaviRequestDataHolder $other);
}

?>