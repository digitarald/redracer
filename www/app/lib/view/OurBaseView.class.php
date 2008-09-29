<?php

/**
 * OurBaseView
 *
 * This is the base view all your application's views should extend.
 * This way, you can easily inject new functionality into all of your views.
 *
 * One example would be to extend the initialize() method and assign commonly
 * used objects such as the request as protected class members.
 *
 * Even if you don't need any of the above and this class remains empty, it is
 * strongly recommended you keep it. There shall come the day where you are
 * happy to have it this way ;)
 *
 * This default implementation throws an exception if execute() is called,
 * which means that no execute*() method specific to the current output type
 * was declared in your view, and no such method exists in this class either.
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class OurBaseView extends AgaviView
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
	 * @var		AgaviWebResponse
	 */
	protected $rs = null;

	/**
	 * @var		OurUser
	 */
	protected $us = null;

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
		$this->rs = $this->getResponse();

		$this->isSlot = $this->container->hasParameter('is_slot');

		return true;
	}

	public final function execute(AgaviRequestDataHolder $rd)
	{
		throw new AgaviViewException(sprintf(
			'The View "%1$s" does not implement an "execute%3$s()" method to serve '.
			'the Output Type "%2$s", and the base View "%4$s" does not implement an '.
			'"execute%3$s()" method to handle this situation.',
			get_class($this),
			$this->container->getOutputType()->getName(),
			ucfirst(strtolower($this->container->getOutputType()->getName())),
			get_class()
		));
	}

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		throw new AgaviViewException(sprintf(
			'The View "%1$s" does not implement an "execute%3$s()" method to serve '.
			'the Output Type "%2$s". It is recommended that you change the code of '.
			'the method "execute%3$s()" in the base View "%4$s" that is throwing '.
			'this exception to deal with this situation in a more appropriate '.
			'way, for example by forwarding to the default 404 error action, or by '.
			'showing some other meaningful error message to the user which explains '.
			'that the operation was unsuccessful beacuse the desired Output Type is '.
			'not implemented.',
			get_class($this),
			$this->container->getOutputType()->getName(),
			ucfirst(strtolower($this->container->getOutputType()->getName())),
			get_class()
		));
	}

	public function setupHtml(AgaviRequestDataHolder $rd, $layoutName = null)
	{
		$this->loadLayout($layoutName);

		$profile = $this->us->getProfile();
		if ($profile)
		{
			$profile = $profile->toArray(true);
		}
		$this->setAttributeByRef('user', $profile);

		$table = Doctrine::getTable('TagModel');
		$tags = $table->findAll()->toArray(true);
		$this->setAttributeByRef('tags', $tags);
	}

	public function redirect($url)
	{
		$this->rs->setRedirect($url);
	}


}

?>