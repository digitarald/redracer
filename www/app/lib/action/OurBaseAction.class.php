<?php

/**
 * OurBaseAction
 *
 * This is the base action all your application's actions should extend.
 * This way, you can easily inject new functionality into all of your actions.
 *
 * One example would be to extend the initialize() method and assign commonly
 * used objects such as the request as protected class members.
 *
 * Another example would be a custom isSimple() method that returns true if the
 * current container has the "is_slot" parameter set - that way, all actions
 * run as a slot would automatically be switched to "simple" mode.
 *
 * Even if you don't need any of the above and this class remains empty, it is
 * strongly recommended you keep it. There shall come the day where you are
 * happy to have it this way ;)
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class OurBaseAction extends AgaviAction
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
	 * @var		OurUser
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
	 * Initialize this action.
	 *
	 * @param		AgaviContext The current application context.
	 *
	 * @return		bool true, if initialization completes successfully, otherwise false.
	 */
	public function initialize($context)
	{
		parent::initialize($context);

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