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
 * AgaviFactoryConfigHandler allows you to specify which factory implementation 
 * the system will use.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviFactoryConfigHandler.class.php 2258 2008-01-03 16:54:04Z david $
 */
class AgaviFactoryConfigHandler extends AgaviConfigHandler
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
	 * @since      0.9.0
	 */
	public function execute($config, $context = null)
	{
		if($context == null) {
			$context = '';
		}

		// parse the config file
		$configurations = $this->orderConfigurations(AgaviConfigCache::parseConfig($config, true, $this->getValidationFile(), $this->parser)->configurations, AgaviConfig::get('core.environment'), $context);
		
		$data = array();
		
		// The order of this initialisiation code is fixed, to not change
		// name => required?
		$factories = array(
			'execution_container' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
				),
			),
			
			'validation_manager' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
				),
			),
			
			'dispatch_filter' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
					'AgaviIGlobalFilter',
				),
			),
			
			'execution_filter' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
					'AgaviIActionFilter',
				),
			),
			
			'security_filter' => array(
				'required' => AgaviConfig::get('core.use_security', false),
				'var' => null,
				'must_implement' => array(
					'AgaviIActionFilter',
					'AgaviISecurityFilter',
				),
			),
			
			'filter_chain' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
				),
			),
			
			'response' => array(
				'required' => true,
				'var' => null,
				'must_implement' => array(
				),
			),
			
			'database_manager' => array(
				'required' => AgaviConfig::get('core.use_database', false),
				'var' => 'databaseManager',
				'must_implement' => array(
				),
			),
			
			'database_manager', // startup()
			
			'logger_manager' => array(
				'required' => AgaviConfig::get('core.use_logging', false),
				'var' => 'loggerManager',
				'must_implement' => array(
				),
			),
			
			'logger_manager', // startup()
			
			'translation_manager' => array(
				'required' => AgaviConfig::get('core.use_translation', false),
				'var' => 'translationManager',
				'must_implement' => array(
				),
			),
			
			'request' => array(
				'required' => true,
				'var' => 'request',
				'must_implement' => array(
				),
			),
			
			'routing' => array(
				'required' => true,
				'var' => 'routing',
				'must_implement' => array(
				),
			),
			
			'controller' => array(
				'required' => true,
				'var' => 'controller',
				'must_implement' => array(
				),
			),
			
			'storage' => array(
				'required' => true,
				'var' => 'storage',
				'must_implement' => array(
				),
			),
			
			'storage', // startup()
			
			'user' => array(
				'required' => true,
				'var' => 'user',
				'must_implement' => (
					AgaviConfig::get('core.use_security')
					? array(
						'AgaviISecurityUser',
					)
					: array(
					)
				),
			),
			
			'translation_manager', // startup()
			
			'user', // startup()
			
			'routing', // startup()
			
			'request', // startup()
			
			'controller', // startup()
		);
		
		foreach($configurations as $cfg) {
			foreach($factories as $factory => $info) {
				if($info['required'] && isset($cfg->$factory)) {
					$data[$factory] = isset($data[$factory]) ? $data[$factory] : array('class' => null, 'params' => array());
					$data[$factory]['class'] = $cfg->$factory->getAttribute('class', $data[$factory]['class']);
					$data[$factory]['params'] = $this->getItemParameters($cfg->$factory, $data[$factory]['params']);
				}
			}
		}
		
		$code = array();
		$shutdownSequence = array();
		
		foreach($factories as $factory => $info) {
			if(is_array($info)) {
				if(!$info['required']) {
					continue;
				}
				if(!isset($data[$factory]) || $data[$factory]['class'] === null) {
					$error = 'Configuration file "%s" has missing or incomplete entry "%s"';
					$error = sprintf($error, $config, $factory);
					throw new AgaviConfigurationException($error);
				}
				
				try {
					$rc = new ReflectionClass($data[$factory]['class']);
				} catch(ReflectionException $e) {
					$error = 'Configuration file "%s" specifies unknown class "%s" for entry "%s"';
					$error = sprintf($error, $config, $data[$factory]['class'], $factory);
					throw new AgaviConfigurationException($error);
				}
				foreach($info['must_implement'] as $interface) {
					if(!$rc->implementsInterface($interface)) {
						$error = 'Class "%s" for entry "%s" does not implement interface "%s" in configuration file "%s"';
						$error = sprintf($error, $data[$factory]['class'], $factory, $interface, $config);
						throw new AgaviConfigurationException($error);
					}
				}
				
				if($info['var'] !== null) {
					// we have to make an instance
					$code[] = sprintf(
						'$this->%1$s = new %2$s();' . "\n" . '$this->%1$s->initialize($this, %3$s);',
						$info['var'],
						$data[$factory]['class'],
						var_export($data[$factory]['params'], true)
					);
				} else {
					// it's a factory info
					$code[] = sprintf(
						'$this->factories[%1$s] = %2$s;',
						var_export($factory, true),
						var_export(array(
							'class' => $data[$factory]['class'],
							'parameters' => $data[$factory]['params'],
						), true)
 					);
				}
			} else {
				if($factories[$info]['required']) {
					$code[] = sprintf('$this->%s->startup();', $factories[$info]['var']);
					array_unshift($shutdownSequence, sprintf('$this->%s', $factories[$info]['var']));
				}
			}
		}
		
		$code[] = sprintf('$this->shutdownSequence = array(%s);', implode(",\n", $shutdownSequence));
		
		return $this->generate($code);
	}
}

?>