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
 * A database adapter for the Doctrine ORM.
 *
 * @package    agavi
 * @subpackage database
 *
 * @author     Ross Lawley <ross.lawley@gmail.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @author     TANAKA Koichi <tanaka@ensites.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviDoctrineDatabase.class.php 2947 2008-09-26 05:42:28Z david $
 */
class AgaviDoctrineDatabase extends AgaviDatabase
{
	/**
	 * @var        Doctrine_Manager The Doctrine Manager instance we should use.
	 */
	protected $doctrineManager;
	
	/**
	 * Connect to the database.
	 *
	 * @throws     <b>AgaviDatabaseException</b> If a connection could not be
	 *                                           created.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function connect()
	{
		// this doesn't do anything, Doctrine is handling the lazy connection stuff
	}
	
	/**
	 * Retrieve a raw database resource associated with this Database
	 * implementation.
	 *
	 * @return     mixed A database resource.
	 *
	 * @throws     <b>AgaviDatabaseException</b> If no resource could be retrieved
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getResource()
	{
		return $this->connection->getDbh();
	}

	/**
	 * Initialize Doctrine set the autoloading
	 *
	 * @param      AgaviDatabaseManager The database manager of this instance.
	 * @param      array                An assoc array of initialization params.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Ross Lawley <ross.lawley@gmail.com>
	 * @author     TANAKA Koichi <tanaka@ensites.com>
	 * @since      0.11.0
	 */
	public function initialize(AgaviDatabaseManager $databaseManager, array $parameters = array())
	{
		parent::initialize($databaseManager, $parameters);
		
		$name = $this->getName();
		
		// try to autoload doctrine
		if(!class_exists('Doctrine')) {
			// okay that didn't work. last resort: include it. we assume it's on the include path by default
			require('Doctrine.php');
		}
		
		// in any case, it's loaded now. maybe we need to register the autoloading stuff for it!
		if(!in_array(array('Doctrine', 'autoload'), spl_autoload_functions())) {
			// we do
			spl_autoload_register(array('Doctrine', 'autoload'));
		}
		
		// cool. Assign the Doctrine Manager instance
		$this->doctrineManager = Doctrine_Manager::getInstance();
		
		// now we're in business. we will set up connections right away, as Doctrine is handling the lazy-connecting stuff for us.
		// that way, you can just start using classes in your code
		try {
			$dsn = $this->getParameter('dsn');
			
			if($dsn === null) {
				// missing required dsn parameter
				$error = 'Database configuration specifies method "dsn", but is missing dsn parameter';
				throw new AgaviDatabaseException($error);
			}
			
			$this->connection = $this->doctrineManager->openConnection($dsn, $name);
			// do not assign the resource here. that would connect to the database
			// $this->resource = $this->connection->getDbh();
			
			// set the context instance as a connection parameter
			$this->connection->setParam('context', $databaseManager->getContext(), 'org.agavi');
			
			// charset
			if($this->hasParameter('charset')) {
				$this->connection->setCharset($this->getParameter('charset'));
			}
			
			// date format
			if($this->hasParameter('date_format')) {
				$this->connection->setDateFormat($this->getParameter('date_format'));
			}
			
			// options
			foreach((array)$this->getParameter('options') as $optionName => $optionValue) {
				$this->connection->setOption($optionName, $optionValue);
			}
			
			foreach((array)$this->getParameter('attributes', array()) as $attributeName => $attributeValue) {
				$this->connection->setAttribute($attributeName, $attributeValue);
			}
			
			foreach((array)$this->getParameter('manager_attributes', array()) as $attributeName => $attributeValue) {
				$this->doctrineManager->setAttribute($attributeName, $attributeValue);
			}
			
			foreach((array)$this->getParameter('impls', array()) as $templateName => $className) {
				$this->connection->setImpl($templateName, $className);
			}
			
			foreach((array)$this->getParameter('manager_impls', array()) as $templateName => $className) {
				$this->doctrineManager->setImpl($templateName, $className);
			}
			
			Doctrine::loadModels($this->getParameter('load_models')); 
			
			foreach((array)$this->getParameter('bind_components', array()) as $componentName) {
				$this->doctrineManager->bindComponent($componentName, $name);
			}
			
			foreach((array)$this->getParameter('init_queries') as $query) {
				$this->connection->exec($query);
			}
		} catch(Doctrine_Exception $e) {
			// the connection's foobar'd
			throw new AgaviDatabaseException($e->getMessage());
		}
	}
	
	/**
	 * Execute the shutdown procedure.
	 *
	 * @throws     <b>AgaviDatabaseException</b> If an error occurs while shutting
	 *                                           down this database.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function shutdown()
	{
		if($this->connection !== null) {
			$this->doctrineManager->closeConnection($this->connection);
			$this->connection = null;
			$this->resource = null;
		}
	}
	
	/**
	 * Get the Doctrine Manager instance.
	 *
	 * @return     Doctrine_Manager The Doctrine Manager instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getDoctrineManager()
	{
		return $this->doctrineManager;
	}
}

?>