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
 * AgaviSettingConfigHandler handles the settings.xml file
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviSettingConfigHandler.class.php 2265 2008-01-14 14:29:37Z david $
 */
class AgaviSettingConfigHandler extends AgaviConfigHandler
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
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function execute($config, $context = null)
	{
		$configurations = $this->orderConfigurations(AgaviConfigCache::parseConfig($config, false, $this->getValidationFile(), $this->parser)->configurations, AgaviConfig::get('core.environment'));

		// init our data array
		$data = array();

		foreach($configurations as $cfg) {
			// let's do our fancy work
			if($cfg->hasChildren('system_actions')) {
				foreach($cfg->system_actions->getChildren() as $action) {
					$name = $action->getAttribute('name');
					$data[sprintf('actions.%s_module', $name)] = $action->module->getValue();
					$data[sprintf('actions.%s_action', $name)] = $action->action->getValue();
				}
			}

			if(isset($cfg->settings)) {
				$multiSettings = $cfg->getChildren('settings');
				foreach($multiSettings as $settings) {
					$prefix = $settings->getAttribute('prefix', 'core.');
					foreach($settings as $setting) {
						if($setting->hasChildren()) {
							$data[$prefix . $setting->getAttribute('name')] = $this->getItemParameters($setting);
						} else {
							$data[$prefix . $setting->getAttribute('name')] = AgaviToolkit::literalize($setting->getValue());
						}
					}
				}
			}

			if($cfg->hasChildren('exception_templates')) {
				foreach($cfg->exception_templates->getChildren() as $exception_template) {
					$tpl = AgaviToolkit::expandDirectives($exception_template->getValue());
					if(!is_readable($tpl)) {
						throw new AgaviConfigurationException('Exception template "' . $tpl . '" does not exist or is unreadable');
					}
					if($exception_template->hasAttribute('context')) {
						foreach(array_map('trim', explode(' ', $exception_template->getAttribute('context'))) as $ctx) {
							$data['exception.templates.' . $ctx] = $tpl;
						}
					} else {
						$data['exception.default_template'] = AgaviToolkit::expandDirectives($tpl);
					}
				}
			}
		}

		$code = 'AgaviConfig::fromArray(' . var_export($data, true) . ');';

		return $this->generate($code);
	}
}

?>