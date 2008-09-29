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
 * A renderer produces the output as defined by a View
 *
 * @package    agavi
 * @subpackage renderer
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Benjamin Muskalla <bm@bmuskalla.de>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviPhptalRenderer.class.php 2258 2008-01-03 16:54:04Z david $
 */
class AgaviPhptalRenderer extends AgaviRenderer
{
	/**
	 * @constant   string The directory inside the cache dir where templates will
	 *                    be stored in compiled form.
	 */
	const COMPILE_DIR = 'templates';
	
	/**
	 * @constant   string The subdirectory inside the compile dir where templates
	 *                    will be stored in compiled form.
	 */
	const COMPILE_SUBDIR = 'phptal';
	
	/**
	 * @var        string A string with the default template file extension,
	 *                    including the dot.
	 */
	protected $defaultExtension = '.tal';

	/**
	 * @var        PHPTAL PHPTAL template engine.
	 */
	protected $phptal = null;

	/**
	 * Pre-serialization callback.
	 *
	 * Excludes the PHPTAL instance to prevent excessive serialization load.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __sleep()
	{
		$keys = parent::__sleep();
		unset($keys[array_search('phptal', $keys)]);
		return $keys;
	}

	/**
	 * Retrieve the PHPTAL instance
	 *
	 * @return     PHPTAL A PHPTAL instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Benjamin Muskalla <bm@muskalla.de>
	 * @since      0.11.0
	 */
	protected function getEngine()
	{
		if($this->phptal === null) {
			if(!defined('PHPTAL_PHP_CODE_DESTINATION')) {
				define('PHPTAL_PHP_CODE_DESTINATION', AgaviConfig::get('core.cache_dir') . DIRECTORY_SEPARATOR . AgaviPhptalRenderer::COMPILE_DIR . DIRECTORY_SEPARATOR . AgaviPhptalRenderer::COMPILE_SUBDIR . DIRECTORY_SEPARATOR);
				AgaviToolkit::mkdir(PHPTAL_PHP_CODE_DESTINATION, fileperms(AgaviConfig::get('core.cache_dir')), true);
			}
			
			if(!class_exists('PHPTAL')) {
				require('PHPTAL.php');
			}
			
			$this->phptal = new PHPTAL();
		}
		return $this->phptal;
	}

	/**
	 * Render the presentation and return the result.
	 *
	 * @param      AgaviTemplateLayer The template layer to render.
	 * @param      array              The template variables.
	 * @param      array              The slots.
	 * @param      array              Associative array of additional assigns.
	 *
	 * @return     string A rendered result.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Benjamin Muskalla <bm@bmuskalla.de>
	 * @since      0.11.0
	 */
	public function render(AgaviTemplateLayer $layer, array &$attributes = array(), array &$slots = array(), array &$moreAssigns = array())
	{
		$engine = $this->getEngine();
		
		if($this->extractVars) {
			foreach($attributes as $key => $value) {
				$engine->set($key, $value);
			}
		} else {
			$engine->set($this->varName, $attributes);
		}
		
		$engine->set($this->slotsVarName, $slots);
		
		foreach($this->assigns as $key => $getter) {
			$engine->set($key, $this->context->$getter());
		}
		
		foreach($moreAssigns as $key => $value) {
			if(isset($this->moreAssignNames[$key])) {
				$key = $this->moreAssignNames[$key];
			}
			$engine->set($key, $value);
		}
		
		$engine->setTemplate($layer->getResourceStreamIdentifier());
		
		return $engine->execute();
	}
}

?>