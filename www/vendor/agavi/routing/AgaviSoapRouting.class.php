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
 * AgaviSoapRouting handles the routing for SOAP web service requests.
 *
 * @package    agavi
 * @subpackage routing
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviSoapRouting.class.php 2258 2008-01-03 16:54:04Z david $
 */
class AgaviSoapRouting extends AgaviWebserviceRouting
{
	/**
	 * Returns the local filesystem path to the WSDL file built from routing.xml.
	 *
	 * @return     string A fully qualified filesystem path.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getWsdlPath()
	{
		$path = $this->getParameter('wsdl', AgaviConfig::get('core.agavi_dir') . '/routing/soap/wsdl.xml');
		
		return AgaviConfigCache::checkConfig($path, $this->context->getName());
	}
}

?>