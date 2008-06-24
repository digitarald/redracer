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

	public function __construct($table = null, $isNewEntry = false)
	{
		parent::__construct($table, $isNewEntry);

		// $this->context =& $this->_table->context;

		if (!$this->context)
		{
			$this->context =& AgaviContext::getInstance('web');
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