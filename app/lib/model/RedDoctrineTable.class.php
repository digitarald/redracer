<?php

/**
 * OurDoctrineTable
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class OurDoctrineTable extends Doctrine_Table implements AgaviIModel
{

	/**
	 * @var		string
	 */
	protected $alias = null;

	/**
	 * @var		string
	 */
	protected $model = null;

	/**
	 * @var		string
	 */
	protected $name = null;

	/**
	 * @var		AgaviContext An AgaviContext instance.
	 */
	public $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current AgaviContext instance.
	 */
	public final function getContext()
	{
		return $this->context;
	}

	public function __construct($name = null, $conn = null, $initDefinition = null)
	{
		parent::__construct($name, $conn, $initDefinition);
		
		$this->context = $conn->getParam('context', 'org.agavi');
		
		$class = get_class($this);
		$this->name = substr($class, 0, strpos($class, 'ModelTable') );

		if ($this->alias === null)
		{
			$this->alias = strtolower($this->name);
		}

		$this->model = $this->name . 'Model';
	}

	/**
	 * Create query with from statement.
	 *
	 * @return		Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query($this->_conn);

		$from = $this->model . ' ' . $this->alias;

		$query->from($from);

		return $query;
	}

}

?>