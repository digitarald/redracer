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
 * AgaviParameterHolder provides a base class for managing parameters.
 *
 * @package    agavi
 * @subpackage util
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviParameterHolder.class.php 2795 2008-09-05 16:19:48Z david $
 */
class AgaviParameterHolder
{
	/**
	 * @var        array An array of parameters
	 */
	protected $parameters = array();

	/**
	 * Constructor. Accepts an array of initial parameters as an argument.
	 *
	 * @param      array An array of parameters to be set right away.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct(array $parameters = array())
	{
		$this->parameters = $parameters;
	}

	/**
	 * Clear all parameters associated with this request.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function clearParameters()
	{
		$this->parameters = array();
	}

	/**
	 * Retrieve a parameter.
	 *
	 * @param      string A parameter name.
	 * @param      mixed  A default parameter value.
	 *
	 * @return     mixed A parameter value, if the parameter exists, otherwise
	 *                   null.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function &getParameter($name, $default = null)
	{
		if(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters)) {
			return $this->parameters[$name];
		}
		$parts = AgaviArrayPathDefinition::getPartsFromPath($name);
		return AgaviArrayPathDefinition::getValue($parts['parts'], $this->parameters, $default);
	}

	/**
	 * Retrieve an array of parameter names.
	 *
	 * @return     array An indexed array of parameter names.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function getParameterNames()
	{
		return array_keys($this->parameters);
	}

	/**
	 * Retrieve an array of flattened parameter names. This means when a parameter
	 * is an array you wont get the name of the parameter in the result but 
	 * instead all child keys appended to the name (like foo[0],foo[1][0], ...)
	 *
	 * @return     array An indexed array of parameter names.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getFlatParameterNames()
	{
		return AgaviArrayPathDefinition::getFlatKeyNames($this->parameters);
	}

	/**
	 * Retrieve an array of parameters.
	 *
	 * @return     array An associative array of parameters.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function &getParameters()
	{
		return $this->parameters;
	}

	/**
	 * Indicates whether or not a parameter exists.
	 *
	 * @param      string A parameter name.
	 *
	 * @return     bool true, if the parameter exists, otherwise false.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function hasParameter($name)
	{
		if(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters)) {
			return true;
		}
		$parts = AgaviArrayPathDefinition::getPartsFromPath($name);
		return AgaviArrayPathDefinition::hasValue($parts['parts'], $this->parameters);
	}

	/**
	 * Remove a parameter.
	 *
	 * @param      string A parameter name.
	 *
	 * @return     string A parameter value, if the parameter was removed,
	 *                    otherwise null.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function &removeParameter($name)
	{
		if(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters)) {
			$retval =& $this->parameters[$name];
			unset($this->parameters[$name]);
			return $retval;
		}
		$parts = AgaviArrayPathDefinition::getPartsFromPath($name);
		return AgaviArrayPathDefinition::unsetValue($parts['parts'], $this->parameters);
	}

	/**
	 * Set a parameter.
	 *
	 * If a parameter with the name already exists the value will be overridden.
	 *
	 * @param      string A parameter name.
	 * @param      mixed  A parameter value.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setParameter($name, $value)
	{
		$this->parameters[$name] = $value;
	}

	/**
	 * Append a parameter.
	 *
	 * If this parameter is already set, convert it to an array and append the
	 * new value.  If not, set the new value like normal.
	 *
	 * @param      string A parameter name.
	 * @param      mixed  A parameter value.
	 *
	 * @author     Bob Zoller <bob@agavi.org>
	 * @since      0.10.0
	 */
	public function appendParameter($name, $value)
	{
		if(!isset($this->parameters[$name]) || !is_array($this->parameters[$name])) {
			settype($this->parameters[$name], 'array');
		}
		$this->parameters[$name][] = $value;
	}

	/**
	 * Set a parameter by reference.
	 *
	 * If a parameter with the name already exists the value will be
	 * overridden.
	 *
	 * @param      string A parameter name.
	 * @param      mixed  A reference to a parameter value.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setParameterByRef($name, &$value)
	{
		$this->parameters[$name] =& $value;
	}

	/**
	 * Append a parameter by reference.
	 *
	 * If this parameter is already set, convert it to an array and append the
	 * reference to the new value.  If not, set the new value like normal.
	 *
	 * @param      string A parameter name.
	 * @param      mixed  A reference to a parameter value.
	 *
	 * @author     Bob Zoller <bob@agavi.org>
	 * @since      0.10.0
	 */
	public function appendParameterByRef($name, &$value)
	{
		if(!isset($this->parameters[$name]) || !is_array($this->parameters[$name])) {
			settype($this->parameters[$name], 'array');
		}
		$this->parameters[$name][] =& $value;
	}

	/**
	 * Set an array of parameters.
	 *
	 * If an existing parameter name matches any of the keys in the supplied
	 * array, the associated value will be overridden.
	 *
	 * @param      array An associative array of parameters and their associated
	 *                   values.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = array_merge($this->parameters, $parameters);
	}

	/**
	 * Set an array of parameters by reference.
	 *
	 * If an existing parameter name matches any of the keys in the supplied
	 * array, the associated value will be overridden.
	 *
	 * @param      array An associative array of parameters and references to their
	 *                   associated values.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setParametersByRef(array &$parameters)
	{
		foreach($parameters as $key => &$value) {
			$this->parameters[$key] =& $value;
		}
	}

}

?>