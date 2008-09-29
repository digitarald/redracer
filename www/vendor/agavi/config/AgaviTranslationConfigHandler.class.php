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
 * AgaviTranslationConfigHandler allows you to define translator implementations
 * for different domains.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviTranslationConfigHandler.class.php 2258 2008-01-03 16:54:04Z david $
 */
class AgaviTranslationConfigHandler extends AgaviConfigHandler
{
	/**
	 * Execute this configuration handler.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string An optional context in which we are currently running.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviConfigurationException</b> on error in the config.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute($config, $context = null)
	{
		$configurations = $this->orderConfigurations(AgaviConfigCache::parseConfig($config, false, $this->getValidationFile(), $this->parser)->configurations, AgaviConfig::get('core.environment'), $context);

		$translatorData = array();
		$localeData = array();

		$defaultDomain = '';
		$defaultLocale = null;
		$defaultTimeZone = null;

		foreach($configurations as $cfg) {

			if(isset($cfg->available_locales)) {
				$defaultLocale = $cfg->available_locales->getAttribute('default_locale', $defaultLocale);
				$defaultTimeZone = $cfg->available_locales->getAttribute('default_timezone', $defaultTimeZone);
				foreach($cfg->available_locales as $locale) {
					$name = $locale->getAttribute('identifier');
					if(!isset($localeData[$name])) {
						$localeData[$name] = array('name' => $name, 'params' => array(), 'fallback' => null, 'ldml_file' => null);
					}
					$localeData[$name]['params'] = $this->getItemParameters($locale, $localeData[$name]['params']);
					$localeData[$name]['fallback'] = $locale->getAttribute('fallback', $localeData[$name]['fallback']);
					$localeData[$name]['ldml_file'] = $locale->getAttribute('ldml_file', $localeData[$name]['ldml_file']);
				}
			}

			if(isset($cfg->translators)) {
				$defaultDomain = $cfg->translators->getAttribute('default_domain', $defaultDomain);
				$this->getTranslators($cfg->translators, $translatorData);
			}
		}

		$data = array();

		$data[] = sprintf('$this->defaultDomain = %s;', var_export($defaultDomain, true));
		$data[] = sprintf('$this->defaultLocaleIdentifier = %s;', var_export($defaultLocale, true));
		$data[] = sprintf('$this->defaultTimeZone = %s;', var_export($defaultTimeZone, true));

		foreach($localeData as $locale) {
			// TODO: fallback stuff

			$data[] = sprintf('$this->availableConfigLocales[%s] = array(\'identifier\' => %s, \'identifierData\' => %s, \'parameters\' => %s);', var_export($locale['name'], true), var_export($locale['name'], true), var_export(AgaviLocale::parseLocaleIdentifier($locale['name']), true), var_export($locale['params'], true));
		}

		foreach($translatorData as $domain => $translator) {
			foreach(array('msg', 'num', 'cur', 'date') as $type) {
				if(isset($translator[$type]['class'])) {
					if(!class_exists($translator[$type]['class'])) {
						throw new AgaviConfigurationException(sprintf('The Translator or Formatter class "%s" for domain "%s" could not be found.', $translator[$type]['class'], $domain));
					}
					$data[] = join("\n", array(
						sprintf('$this->translators[%s][%s] = new %s();', var_export($domain, true), var_export($type, true), $translator[$type]['class']),
						sprintf('$this->translators[%s][%s]->initialize($this->getContext(), %s);', var_export($domain, true), var_export($type, true), var_export($translator[$type]['params'], true)),
						sprintf('$this->translatorFilters[%s][%s] = %s;', var_export($domain, true), var_export($type, true), var_export($translator[$type]['filters'], true)),
					));
				}
			}
		}

		return $this->generate($data);
	}
	
	/**
	 * Builds a list of filters for a translator.
	 *
	 * @param      AgaviConfigValueHolder The Translator node.
	 *
	 * @return     array An array of filter definitions.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getFilters($translator)
	{
		$filters = array();
		if(isset($translator->filters)) {
			foreach($translator->filters as $filter) {
				$func = explode('::', $filter->getValue());
				if(count($func) != 2) {
					$func = $func[0];
				}
				if(!is_callable($func)) {
					throw new AgaviConfigurationException('Non-existant or uncallable filter function "' . $filter->getValue() .  '" specified.');
				}
				$filters[] = $func;
			}
		}
		return $filters;
	}

	/**
	 * Build a list of translators.
	 *
	 * @param      AgaviConfigValueHolder The translators container.
	 * @param      array                  The destination data array.
	 * @param      string                 The name of the parent domain.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getTranslators($translators, &$data, $parent = null)
	{
		static $defaultData = array(
			'msg'  => array('class' => null, 'filters' => array(), 'params' => array()),
			'num'  => array('class' => 'AgaviNumberFormatter', 'filters' => array(), 'params' => array()),
			'cur'  => array('class' => 'AgaviCurrencyFormatter', 'filters' => array(), 'params' => array()),
			'date' => array('class' => 'AgaviDateFormatter', 'filters' => array(), 'params' => array()),
		);

		foreach($translators as $translator) {
			$domain = $translator->getAttribute('domain');
			if($parent) {
				$domain = $parent . '.' . $domain;
			}
			if(!isset($data[$domain])) {
				if(!$parent) {
					$data[$domain] = $defaultData;
				} else {
					$data[$domain] = array();
				}
			}

			$domainData =& $data[$domain];

			foreach(array('msg' => 'message_translator', 'num' => 'number_formatter', 'cur' => 'currency_formatter', 'date' => 'date_formatter') as $type => $node) {
				if(isset($translator->$node)) {
					if(!isset($domainData[$type])) {
						$domainData[$type] = $defaultData[$type];
					}
					
					if($translator->$node->hasAttribute('translation_domain')) {
						$domainData[$type]['params']['translation_domain'] = $translator->$node->getAttribute('translation_domain');
					}
					$domainData[$type]['class'] = $translator->$node->getAttribute('class', $domainData[$type]['class']);
					$domainData[$type]['params'] = $this->getItemParameters($translator->$node, $domainData[$type]['params']);
					$domainData[$type]['filters'] = $this->getFilters($translator->$node);
				}
			}

			if(isset($translator->translators)) {
				$this->getTranslators($translator->translators, $data, $domain);
			}
		}
	}
}

?>