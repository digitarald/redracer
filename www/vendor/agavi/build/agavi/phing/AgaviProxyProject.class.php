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
 * Proxies a project from an external build file.
 *
 * @package    agavi
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      1.0.0
 *
 * @version    $Id: AgaviProxyProject.class.php 2605 2008-07-10 16:43:48Z impl $
 */
class AgaviProxyProject extends Project
{
	protected $proxied = null;
	
	protected static $protectedProperties = array(
		'phing.file',
		'basedir'
	);
	
	/**
	 * Creates a new proxied project.
	 *
	 * @param      Project The project to proxy.
	 */
	public function __construct(Project $proxied)
	{
		$this->proxied = $proxied;
		
		parent::__construct();
		
		foreach($proxied->getBuildListeners() as $listener) {
			parent::addBuildListener($listener);
		}
		$this->setInputHandler($proxied->getInputHandler());
		foreach($proxied->getTaskDefinitions() as $name => $class) {
			parent::addTaskDefinition($name, $class);
		}
		foreach($proxied->getDataTypeDefinitions() as $name => $class) {
			parent::addDataTypeDefinition($name, $class);
		}
		
		/* Assign properties for consistency. */
		$proxied->copyUserProperties($this);
		$proxied->copyInheritedProperties($this);
		foreach($proxied->getProperties() as $name => $property) {
			if(!AgaviProxyProject::isPropertyProtected($name) && $this->getProperty($name) === null) {
				parent::setNewProperty($name, $property);
			}
		}
		
		/* Add proxy targets to the new project. */
		foreach($proxied->getTargets() as $name => $target) {
			$proxy = new AgaviProxyTarget();
			$proxy->setName($name);
			$proxy->setDescription($target->getDescription());
			$proxy->setTarget($target);
			parent::addTarget($name, $proxy);
		}
		
		parent::setUserProperty('phing.version', $proxied->getProperty('phing.version'));
		$this->setSystemProperties();
	}
	
	/**
	 * Initializes this project.
	 */
	public function init()
	{
		
	}
	
	/**
	 * Determines whether a given property is protected (that is, should not be
	 * copied from one project to the other).
	 *
	 * @param      string The name of the property.
	 *
	 * @return     bool True if the property is protected, false otherwise.
	 */
	public static function isPropertyProtected($property)
	{
		return in_array($property, self::$protectedProperties);
	}
	
	/**
	 * Sets a property. Proxies the request to the underlying project.
	 *
	 * @param      string The name of the property.
	 * @param      mixed The value of the property.
	 */
	public function setProperty($name, $value)
	{
		if(!self::isPropertyProtected($name)) {
			$this->proxied->setProperty($name, $value);
		}
		parent::setProperty($name, $value);
	}
	
	/**
	 * Sets a new property. Proxies the request to the underlying project.
	 *
	 * @param      string The name of the property.
	 * @param      mixed The value of the property.
	 */
	public function setNewProperty($name, $value)
	{
		if(!self::isPropertyProtected($name)) {
			$this->proxied->setNewProperty($name, $value);
		}
		parent::setNewProperty($name, $value);
	}
	
	/**
	 * Sets a user property. Proxies the request to the underlying project.
	 *
	 * @param      string The name of the property.
	 * @param      mixed The value of the property.
	 */
	public function setUserProperty($name, $value)
	{
		if(!self::isPropertyProtected($name)) {
			$this->proxied->setUserProperty($name, $value);
		}
		parent::setUserProperty($name, $value);
	}
	
	/**
	 * Sets an inherited property. Proxies the request to the underlying project.
	 *
	 * @param      string The name of the property.
	 * @param      mixed The value of the property.
	 */
	public function setInheritedProperty($name, $value)
	{
		if(!self::isPropertyProtected($name)) {
			$this->proxied->setInheritedProperty($name, $value);
		}
		parent::setInheritedProperty($name, $value);
	}
	
	/**
	 * Adds a new task to the project. Proxies the request to the underlying
	 * project.
	 *
	 * @param      string The name of the task.
	 * @param      string The name of the class.
	 * @param      string The classpath to use when resolving the class.
	 */
	public function addTaskDefinition($name, $class, $classpath = null)
	{
		$this->proxied->addTaskDefinition($name, $class, $classpath);
		parent::addTaskDefinition($name, $class, $classpath);
	}
	
	/**
	 * Adds a new datatype to the project. Proxies the request to the underlying
	 * project.
	 *
	 * @param      string The name of the task.
	 * @param      string The name of the class.
	 * @param      string The classpath to use when resolving the class.
	 */
	public function addDataTypeDefinition($name, $class, $classpath = null)
	{
		$this->proxied->addDataTypeDefinition($name, $class, $classpath);
		parent::addDataTypeDefinition($name, $class, $classpath);
	}
	
	/**
	 * Adds a new build listener to the project. Proxies the request to the
	 * underlying project.
	 *
	 * @param      BuildListener The build listener to add.
	 */
	public function addBuildListener(BuildListener $listener)
	{
		$this->proxied->addBuildListener($listener);
		parent::addBuildListener($listener);
	}
}

?>
