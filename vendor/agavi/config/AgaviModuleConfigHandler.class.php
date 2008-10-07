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
 * AgaviModuleConfigHandler reads module configuration files to determine the
 * status of a module.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviModuleConfigHandler.class.php 2782 2008-09-04 12:25:59Z david $
 */
class AgaviModuleConfigHandler extends AgaviXmlConfigHandler
{
	const XML_NAMESPACE = 'http://agavi.org/agavi/config/parts/module/1.0';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      AgaviXmlConfigDomDocument The document to parse.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      0.9.0
	 */
	public function execute(AgaviXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'module');
		
		// remember the config file path
		$config = $document->documentURI;
		
		$enabled = false;
		$prefix = 'modules.${moduleName}.';
		$data = array();
		
		// loop over <configuration> elements
		foreach($document->getConfigurationElements() as $configuration) {
			$module = $configuration->getChild('module');
			if(!$module) {
				continue;
			}
			
			// enabled flag is treated separately
			$enabled = (bool) AgaviToolkit::literalize($module->getAttribute('enabled'));
			
			// loop over <settings> elements; there can be many of them
			foreach($module as $settings) {
				$localPrefix = $settings->getAttribute('prefix', $prefix);
				// <settings> has <setting> children
				foreach($settings as $setting) {
					$settingName = $localPrefix . $setting->getAttribute('name');
					if($setting->hasAgaviParameters()) {
						$data[$settingName] = $setting->getAgaviParameters();
					} else {
						$data[$settingName] = $setting->getValue();
					}
				}
			}
		}
		
		$code = array();
		$code[] = '$lcModuleName = strtolower($moduleName);';
		$code[] = 'AgaviConfig::set(AgaviToolkit::expandVariables(' . var_export($prefix . 'enabled', true) . ', array(\'moduleName\' => $lcModuleName)), ' . var_export($enabled, true) . ', true, true);';
		if(count($data)) {
			$code[] = '$moduleConfig = ' . var_export($data, true) . ';';
			$code[] = '$moduleConfigKeys = array_keys($moduleConfig);';
			$code[] = 'foreach($moduleConfigKeys as &$value) $value = AgaviToolkit::expandVariables($value, array(\'moduleName\' => $lcModuleName));';
			$code[] = '$moduleConfig = array_combine($moduleConfigKeys, $moduleConfig);';
			$code[] = 'AgaviConfig::fromArray($moduleConfig);';
		}
		
		return $this->generate($code);
	}
}

?>