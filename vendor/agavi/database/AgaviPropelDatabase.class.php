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
 * An Agavi Database driver for Propel, derived from the native Creole driver. 
 * 
 * <b>Optional parameters:</b>
 *
 * # <b>config</b>         - [none]    - path to the Propel runtime config file
 * # <b>datasource</b>     - [default] - datasource to use for the connection
 * # <b>use_as_default</b> - [false]   - use as default if multiple connections
 *                                       are specified. The configuration file
 *                                       that has been flagged using this param
 *                                       is be used when Propel is initialized
 *                                       via PropelAutoload. By default, the
 *                                       last config file in database.ini will
 *                                       be used.
 * # <b>use_autoload</b>   - [true]    - set this to false if you don't want to
 *                                       use the Propel autoloading feature.
 *                                       Instead, Propel will be initialized 
 *                                       on connect(). This is for 0.9.0 B/C.
 * 
 *
 * @package    agavi
 * @subpackage database
 * 
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviPropelDatabase.class.php 2733 2008-08-29 20:16:15Z david $
 */
class AgaviPropelDatabase extends AgaviDatabase
{
	/**
	 * Stores the actual AgaviCreoleDatabase implementation for Propel 1.2.
	 *
	 * @var        agaviCreoleDatabase The agaviCreoleDatabase instance used internally.
	 */
	protected $agaviCreoleDatabase = null;
	
	/**
	 * Stores the path of the configuration file that will be passed to
	 * Propel::init() when using Propel autoloading magic
	 *
	 * @var        string The filesystem path to the default runtime config.
	 */
	private static $defaultConfigPath = null;

	/**
	 * Stores whether a Propel configuration file path has been explicitly set
	 * as default for use with Propel::init() in database.xml
	 *
	 * @var        bool A flag indicating whether a default config path was set.
	 */
	private static $defaultConfigPathSet = false;

	/**
	 * Returns the path to the config file that is passed to Propel::init() when
	 * PropelAutoload.php is used in autoload.xml
	 *
	 * @return     mixed The path if one has been set, otherwise null
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public static function getDefaultConfigPath()
	{
		return self::$defaultConfigPath;
	}

	/**
	 * Sets the path to the config file that is passed to Propel::init() when
	 * PropelAutoload.php is used in autoload.xml
	 *
	 * @param      string The path to the configuration file
	 *
	 * @return     mixed The old path if one was set previously, otherwise null
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	protected static function setDefaultConfigPath($path)
	{
		$return = self::getDefaultConfigPath();
		self::$defaultConfigPath = $path;
		return $return;
	}

	/**
	 * Returns whether a Propel configuration file path has been explicitly set
	 * as default for use with Propel::init() in database.xml
	 *
	 * @return     bool true, if a Propel configuration file path has explicitely
	 *                  been set as default in database.ini, otherwise false
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	protected static function isDefaultConfigPathSet()
	{
		return self::$defaultConfigPathSet;
	}

	/**
	 * Sets a flag indicating a Propel configuration file path has been
	 * explicitly set as default for use with Propel::init() in database.xml
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	protected static function setDefaultConfigPathSet()
	{
		self::$defaultConfigPathSet = true;
	}

	/**
	 * Connect to the database.
	 * 
	 *
	 * @throws     <b>agaviCreoleDatabaseException</b> If a connection could not be 
	 *                                           created.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	protected function connect()
	{
		if($this->agaviCreoleDatabase) {
			// make concrecte adapter connect
			$this->connection = $this->agaviCreoleDatabase->getConnection();
		} else {
			// trigger Propel autoload and go go go
			if(class_exists('Propel')) {
				$this->connection = Propel::getConnection();
				
				foreach((array)$this->getParameter('init_queries') as $query) {
					$this->connection->exec($query);
				}
			}
		}
	}

	/**
	 * Retrieve the database connection associated with this Database
	 * implementation.
	 *
	 * When this is executed on a Database implementation that isn't an
	 * abstraction layer, a copy of the resource will be returned.
	 *
	 * @return     mixed A database connection.
	 *
	 * @throws     <b>agaviCreoleDatabaseException</b> If a connection could not be
	 *                                           retrieved.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function getConnection()
	{
		if($this->connection === null) {
			$this->connect();
		}
		return parent::getConnection();
	}

	/**
	 * Load Propel config
	 * 
	 * @param      AgaviDatabaseManager The database manager of this instance.
	 * @param      array                An assoc array of initialization params.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function initialize(AgaviDatabaseManager $databaseManager, array $parameters = array())
	{
		parent::initialize($databaseManager, $parameters);
		$configPath = AgaviToolkit::expandDirectives($this->getParameter('config'));
		$datasource = $this->getParameter('datasource', null);
		$use_as_default = $this->getParameter('use_as_default', false);
		$config = require($configPath);
		if($datasource === null || $datasource == 'default') {
			$datasource = $config['propel']['datasources']['default'];
		}
		$is12 = true;
		if(isset($config['propel']['generator_version']) && version_compare($config['propel']['generator_version'], '1.3.0-dev') >= 0) {
			$is12 = false;
		}
		if($is12) {
			// Propel 1.1 or 1.2, so let's use Creole for the connection.
			$this->agaviCreoleDatabase = new AgaviCreoleDatabase();
			$this->agaviCreoleDatabase->initialize($databaseManager, $parameters);
			foreach($config['propel']['datasources'][$datasource]['connection'] as $key => $value) {
				$this->agaviCreoleDatabase->setParameter($key, $this->getParameter('overrides[connection][' . $key . ']', $value));
			}
			$this->agaviCreoleDatabase->setParameter('method', 'normal');
		}
		
		if(!self::isDefaultConfigPathSet()) {
			self::setDefaultConfigPath($configPath);
			if($use_as_default) {
				self::setDefaultConfigPathSet();
			}
		}
		if(!$is12) {
			// it's Propel 1.3 or later, let's autoload or include Propel
			if(!class_exists('Propel')) {
				include('propel/Propel.php');
			}
			if(!Propel::isInit()) {
				// that wasn't PropelAutoload, so init it
				Propel::init(self::getDefaultConfigPath());
			}
		}
		
		// grab the configuration values and inject possibly defined overrides for this data source
		$config = Propel::getConfiguration();
		$config['datasources'][$datasource]['adapter'] = $this->getParameter('overrides[adapter]', $config['datasources'][$datasource]['adapter']);
		$config['datasources'][$datasource]['connection'] = array_merge($config['datasources'][$datasource]['connection'], $this->getParameter('overrides[connection]', array()));
		
		if(!$is12) {
			// for 1.3+, also the autoload classes
			$config['datasources'][$datasource]['classes'] = array_merge($config['datasources'][$datasource]['classes'], $this->getParameter('overrides[classes]', array()));
		}
		
		// set the new config
		Propel::setConfiguration($config);
	}

	/**
	 * Get the path to the Propel config file for this connection which has been
	 * specified in databases.xml.
	 *
	 * @return     string The path to the Propel configuration file
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function getConfigPath()
	{
		return $this->getParameter('config');
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @throws     <b>agaviCreoleDatabaseException</b> If an error occurs while shutting
	 *                                           down this database.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function shutdown()
	{
		if($this->agaviCreoleDatabase) {
			$this->agaviCreoleDatabase->shutdown();
		} else {
			$this->connection = null;
		}
	}
}

?>