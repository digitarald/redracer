<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2008 the Agavi Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
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
 * RbacDefinitionConfigHandler handles RBAC role and permission definition files
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviRbacDefinitionConfigHandler.class.php 2584 2008-07-07 13:51:19Z david $
 */
class AgaviRbacDefinitionConfigHandler extends AgaviConfigHandler
{
	/**
	 * Execute this configuration handler.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string An optional context in which we are currently running.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>AgaviParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute($config, $context = null)
	{
		// parse the config file
		$configurations = $this->orderConfigurations(AgaviConfigCache::parseConfig($config, false, $this->getValidationFile(), $this->parser)->configurations, AgaviConfig::get('core.environment'), $context);

		$data = array();

		foreach($configurations as $cfg) {
			if(!isset($cfg->roles)) {
				continue;
			}
			
			$this->parseRoles($cfg->roles, null, $data);
		}

		$code = "return " . var_export($data, true) . ";";
		
		return $this->generate($code);
	}
	
	/**
	 * Parse a 'roles' node.
	 *
	 * @param      AgaviConfigValueHolder The "roles" node.
	 * @param      string                 The name of the parent role, or null.
	 * @param      array                  A reference to the output data array.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function parseRoles(AgaviConfigValueHolder $roles, $parent, &$data)
	{
		foreach($roles as $role) {
			$name = $role->getAttribute('name');
			$entry = array();
			$entry['parent'] = $parent;
			$entry['permissions'] = array();
			if(isset($role->permissions)) {
				foreach($role->permissions as $permission) {
					$entry['permissions'][] = $permission->getValue();
				}
			}
			if(isset($role->roles)) {
				$this->parseRoles($role->roles, $name, $data);
			}
			$data[$name] = $entry;
		}
	}
}

?>