<?php

/**
 * RedDoctrineTable
 *
 * @package    redracer
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedRacerDoctrineTable extends Doctrine_Table
{

	/**
	 * @var			AgaviContext
	 */
	public $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current AgaviContext instance.
	 */
	public final function getContext()
	{
		if ($this->context === null) {
			$this->context = $this->getConnection()->getParam('context', 'org.agavi');
		}

		return $this->context;
	}

}

?>