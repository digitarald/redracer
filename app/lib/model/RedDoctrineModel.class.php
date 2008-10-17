<?php

/**
 * RedDoctrineModel
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedDoctrineModel extends Doctrine_Record implements AgaviIModel
{

	/**
	 * @var			AgaviContext
	 */
	public $context = null;

	/**
	 * constructor
	 *
	 * @param Doctrine_Table|null $table       a Doctrine_Table object or null,
	 *                                         if null the table object is retrieved from current connection
	 *
	 * @param boolean $isNewEntry              whether or not this record is transient
	 */
	public function __construct($table = null, $isNewEntry = false)
	{
		parent::__construct($table, $isNewEntry);

		if (!$this->context && $table !== null) {
			$this->context = $table->getParam('context', 'org.agavi');
		}
	}

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current AgaviContext instance.
	 */
	public final function getContext()
	{
		return $this->context;
	}
}

?>