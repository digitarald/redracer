<?php

/**
 * OurDoctrineModel
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class OurDoctrineModel extends Doctrine_Record implements AgaviIModel
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

		if (!$this->context && $table !== null)
		{
			/**
			 * @rant DOCTRINE FAIL
			 * @todo getParam returns null for context, getParams the correct array
			 */
			$params = $table->getConnection()->getParams('org.agavi');
			$this->context = $params['context'];
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