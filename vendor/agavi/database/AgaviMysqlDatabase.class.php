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
 * AgaviMysqlDatabase provides connectivity for the MySQL brand database.
 *
 * <b>Optional parameters:</b>
 *
 * # <b>database</b>   - [none]      - The database name.
 * # <b>host</b>       - [localhost] - The database host.
 * # <b>method</b>     - [normal]    - How to read connection parameters.
 *                                     Possible values are normal, server, and
 *                                     env. The normal method reads them from
 *                                     the specified values. server reads them
 *                                     from $_SERVER where the keys to retrieve
 *                                     the values are what you specify the value
 *                                     as in the settings. env reads them from
 *                                     $_ENV and works like $_SERVER.
 * # <b>password</b>   - [none]      - The database password.
 * # <b>persistent</b> - [No]        - Indicates that the connection should be
 *                                     persistent.
 * # <b>username</b>   - [none]      - The database user.
 *
 * @package    agavi
 * @subpackage database
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id: AgaviMysqlDatabase.class.php 2733 2008-08-29 20:16:15Z david $
 */
class AgaviMysqlDatabase extends AgaviDatabase
{
	/**
	 * Connect to the database.
	 *
	 * @throws     <b>AgaviDatabaseException</b> If a connection could not be 
	 *                                           created.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	protected function connect()
	{
		// determine how to get our
		$method = $this->getParameter('method', 'normal');

		switch($method) {
			case 'normal':
				// get parameters normally
				$database = $this->getParameter('database');
				$host     = $this->getParameter('host', 'localhost');
				$password = $this->getParameter('password');
				$user     = $this->getParameter('username');
				break;

			case 'server':
				// construct a connection string from existing $_SERVER values
				// and extract them to local scope
				$parameters = $this->loadParameters($_SERVER);
				extract($parameters);
				break;

			case 'env':
				// construct a connection string from existing $_ENV values
				// and extract them to local scope
				$string = $this->loadParameters($_ENV);
				extract($parameters);
				break;

			default:
				// who knows what the user wants...
				$error = 'Invalid AgaviMySQLDatabase parameter retrieval method ' .
						 '"%s"';
				$error = sprintf($error, $method);
				throw new AgaviDatabaseException($error);
		}

		// let's see if we need a persistent connection
		$persistent = $this->getParameter('persistent', false);
		
		if($password === null) {
			if($user === null) {
				$args = array($host, null, null);
			} else {
				$args = array($host, $user, null);
			}
		} else {
			$args = array($host, $user, $password);
		}
		
		if($persistent) {
			$this->connection = call_user_func_array('mysql_pconnect', $args);
		} else {
			$this->connection = call_user_func_array('mysql_connect', $args + array(true));
		}
		
		// make sure the connection went through
		if($this->connection === false) {
			// the connection's foobar'd
			$error = 'Failed to create a AgaviMySQLDatabase connection';
			throw new AgaviDatabaseException($error);
		}

		// select our database
		if($database !== null && !@mysql_select_db($database, $this->connection)) {
			// can't select the database
			$error = 'Failed to select AgaviMySQLDatabase "%s"';
			$error = sprintf($error, $database);
			throw new AgaviDatabaseException($error);
		}

		// since we're not an abstraction layer, we copy the connection
		// to the resource
		$this->resource =& $this->connection;
		
		foreach((array)$this->getParameter('init_queries') as $query) {
			mysql_query($query, $this->connection);
		}
	}

	/**
	 * Load connection parameters from an existing array.
	 *
	 * @param      array An array containing the connection information.
	 *
	 * @return     array An associative array of connection parameters.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	protected function loadParameters(array $array)
	{
		// list of available parameters
		$available = array('database', 'host', 'password', 'username');

		$parameters = array();

		foreach($available as $parameter) {
			$$parameter = $this->getParameter($parameter);
			$parameters[$parameter] = ($$parameter != null) ? $array[$$parameter] : null;
		}

		return $parameters;
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @throws     <b>AgaviDatabaseException</b> If an error occurs while shutting
	 *                                           down this database.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function shutdown()
	{
		if($this->connection != null) {
			@mysql_close($this->connection);
		}
	}
}

?>