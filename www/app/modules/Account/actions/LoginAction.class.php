<?php

class Account_LoginAction extends OurBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		try
		{
			$redirect = $this->us->getOpenIdRedirect($rd->getParameter('openid_identity') );
		}
		catch (AgaviSecurityException $e)
		{
			$vm = $this->container->getValidationManager();
			/**
			 * @todo Cleaner error message to the frontend
			 */
			$vm->setError('openid_identity',  $e->getMessage() );

			return $this->handleError($rd);
		}

		$this->setAttribute('redirect', $redirect);

		return 'Success';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		/**
		 * @todo Move that somewhere else?
		 */
		if ($rd->hasParameter('login_from') && !$this->us->hasAttribute('redirect', 'our.login') )
		{
			$this->us->setAttribute('redirect', $rd->getParameter('login_from'), 'our.login');
		}

		$this->us->setAttribute('remember', $rd->hasParameter('login_remember'), 'our.login');

		return true;
	}


	/**
	 * This method returns the View name in case the Action doesn't serve the
	 * current Request method.
	 *
	 * @return     mixed - A string containing the view name associated with this
	 *                     action, or...
	 *                   - An array with two indices:
	 *                     0. The parent module of the view that will be executed.
	 *                     1. The view that will be executed.
	 *
	 */
	public function getDefaultViewName()
	{
		return 'Input';
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		return 'Input';
	}

}

?>