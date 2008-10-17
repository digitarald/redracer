<?php

class Account_LoginResponseErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		/**
		 * @todo Cleaner error handling
		 */
		$errors = array();
		foreach ($this->container->getValidationManager()->getErrorMessages() as $error)
		{
			$errors[] = $error['message'];
		}
		if (count($errors) ) {
			$this->us->addFlash('Log in failed. You did not provide the following entries in your OpenID profile:<ul><li>' . implode('</li><li>', $errors) . '</li></ul>', 'error');
		}

		return $this->redirect($this->rt->gen('account.login') );
	}
}

?>