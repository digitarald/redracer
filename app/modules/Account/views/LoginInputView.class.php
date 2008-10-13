<?php

class Account_LoginInputView extends RedBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		if ($this->rq->hasAttributeNamespace('org.agavi.controller.forwards.login') )
		{
			// we were redirected to the login form by the controller because the requested action required security
			// so store the input URL in the session for a redirect after login
			$this->us->setAttribute('redirect', $this->rq->getUrl(), 'our.login');
		}
		else
		{
			// clear the redirect URL just to be sure
			// but only if request method is "read", i.e. if the login form is served via GET!
			$this->us->removeAttribute('redirect', 'our.login');
		}

		$this->setAttribute('title', 'Log in');
	}

}

?>