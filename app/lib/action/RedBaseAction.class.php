<?php

/**
 * RedBaseAction
 *
 * This is the base action all your application's actions should extend.
 * This way, you can easily inject new functionality into all of your actions.
 *
 * @package    redracer
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedBaseAction extends AgaviAction
{

	/**
	 * @var		AgaviWebRequest
	 */
	protected $rq = null;

	/**
	 * @var		AgaviWebRouting
	 */
	protected $rt = null;


	/**
	 * @var		AgaviController
	 */
	protected $ct = null;

	/**
	 * @var		RedUser
	 */
	protected $us = null;

	/**
	 * @var		AgaviValidationManager
	 */
	protected $vm = null;

	/**
	 * @var		Doctrine_Connection
	 */
	protected $cn = null;

	/**
	 * @var		boolean
	 */
	protected $isSlot = false;

	/**
	 * @see		AgaviAction::initialize()
	 */
	public function initialize(AgaviExecutionContainer $container)
	{
		parent::initialize($container);

		$this->rq = $this->context->getRequest();
		$this->rt = $this->context->getRouting();
		$this->ct = $this->context->getController();
		$this->us = $this->context->getUser();
		$this->cn = $this->context->getDatabaseConnection();

		$this->vm = $this->container->getValidationManager();

		$this->isSlot = $this->container->hasParameter('is_slot');

		return true;
	}

}

?>