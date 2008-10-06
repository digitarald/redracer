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
 * AgaviConfigHandlersConfigHandler allows you to specify configuration handlers
 * for the application or on a module level.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviConfigHandlersConfigHandler.class.php 2727 2008-08-27 18:35:26Z felix $
 */
class AgaviConfigHandlersConfigHandler extends AgaviXmlConfigHandler
{
	const XML_NAMESPACE = 'http://agavi.org/agavi/config/parts/config_handlers/1.0';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      AgaviXmlConfigDomDocument The document to handle.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>AgaviParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      0.11.0
	 */
	public function execute(AgaviXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'config_handlers');
		
		// init our data arrays
		$handlers = array();
		
		foreach($document->getConfigurationElements() as $configuration) {
			if(!$configuration->hasChildren('handlers')) {
				continue;
			}
			
			// let's do our fancy work
			foreach($configuration->getChildren('handlers') as $handler) {
				$pattern = $handler->getAttribute('pattern');
				
				$category = AgaviToolkit::normalizePath(AgaviToolkit::expandDirectives($pattern));
				
				$class = $handler->getAttribute('class');
				
				$transformations = array(
					AgaviXmlConfigParser::STAGE_SINGLE => array(),
					AgaviXmlConfigParser::STAGE_COMPILATION => array(),
				);
				if($handler->hasChildren('transformations')) {
					foreach($handler->getChildren('transformations') as $transformation) {
						$path = AgaviToolkit::literalize($transformation->getValue());
						$for = $transformation->getAttribute('for', AgaviXmlConfigParser::STAGE_SINGLE);
						$transformations[$for][] = $path;
					}
				}
				
				$validations = array(
					AgaviXmlConfigParser::STAGE_SINGLE => array(
						AgaviXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(
							AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
						AgaviXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
							AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
					),
					AgaviXmlConfigParser::STAGE_COMPILATION => array(
						AgaviXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(
							AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
						AgaviXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
							AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							AgaviXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
					),
				);
				if($handler->hasChildren('validations')) {
					foreach($handler->getChildren('validations') as $validation) {
						$path = AgaviToolkit::literalize($validation->getValue());
						$type = null;
						if(!$validation->hasAttribute('type')) {
							$type = $this->guessValidationType($path);
						} else {
							$type = $validation->getAttribute('type');
						}
						$for = $validation->getAttribute('for', AgaviXmlConfigParser::STAGE_SINGLE);
						$step = $validation->getAttribute('step', AgaviXmlConfigParser::STEP_TRANSFORMATIONS_AFTER);
						$validations[$for][$step][$type][] = $path;
					}
				}
				
				$handlers[$category] = isset($handlers[$category])
					? $handlers[$category]
					: array(
						'parameters' => array(),
						);
				$handlers[$category] = array(
					'class' => $class,
					'parameters' => $handler->getAgaviParameters($handlers[$category]['parameters']),
					'transformations' => $transformations,
					'validations' => $validations,
				);
			}
		}
		
		$data = array(
			'return ' . var_export($handlers, true),
		);
		
		return $this->generate($data);
	}
	
	public function guessValidationType($path)
	{
		switch(pathinfo($path, PATHINFO_EXTENSION)) {
			case 'rng':
				return AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG;
			case 'rnc':
				return AgaviXmlConfigParser::VALIDATION_TYPE_RELAXNG;
			case 'sch':
				return AgaviXmlConfigParser::VALIDATION_TYPE_SCHEMATRON;
			case 'xsd':
				return AgaviXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA;
			default:
				throw new AgaviException(sprintf('Could not determine validation type for file "%s"', $path));
		}
	}
}

?>