<?php

class Account_LoginAction extends OurBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$store = new Auth_OpenID_FileStore(AgaviConfig::get('core.cache_dir') . '/openid');
		$consumer = new Auth_OpenID_Consumer($store);

		$url = $rd->getParameter('openid_identity');

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($url);

		// No auth request means we can't begin OpenID.
		if (!$auth_request)
		{
			/**
			 * @todo Cleaner error message to the frontend
			 */
			$this->vm->setError('openid_identity',  'Authentication error, not a valid OpenID!');

			return $this->handleError($rd);
		}

		$sreg_request = Auth_OpenID_SRegRequest::build(
			 array('fullname', 'nickname'), // Required
			 array('email', 'country', 'dob', 'language', 'postcode', 'timezone') // Optional
		);
		if ($sreg_request)
		{
			$auth_request->addExtension($sreg_request);
		}

		// from example code
		$policy_uris = array(
			// PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			// PAPE_AUTH_MULTI_FACTOR,
			// PAPE_AUTH_PHISHING_RESISTANT
		);

		$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
		if ($pape_request)
		{
			$auth_request->addExtension($pape_request);
		}

		$realm = $this->rt->gen('index', array(), array(
			'relative' => false
		) );
		$return_to = $this->rt->gen('account.login_response', array(), array(
			'relative' => false
		) );

		// Redirect the user to the OpenID server for authentication.
		// Store the token for this authentication so we can verify the
		// response.

		// For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
		// form to send a POST request to the server.
		if ($auth_request->shouldSendRedirect() )
		{
			$redirect_url = $auth_request->redirectURL($realm, $return_to);

			// If the redirect URL can't be built, display an error
			// message.
			if (Auth_OpenID::isFailure($redirect_url) )
			{
				$this->vm->setError('openid_identity',  'Could not redirect to server: ' . $redirect_url->message);
				return $this->handleError($rd);
			}
			else
			{
				// Send redirect.
				$this->setAttribute('redirect_url', $redirect_url);
			}
		}
		else
		{
			// Generate form markup and render it.
			$form_html = $auth_request->htmlMarkup($realm, $return_to, false);

			// Display an error if the form markup couldn't be generated;
			// otherwise, render the HTML.
			if (Auth_OpenID::isFailure($form_html) )
			{
				$this->vm->setError('openid_identity',  'Could not redirect to server: ' . $form_html->message);
				return $this->handleError($rd);
			}
			else
			{
				$this->setAttribute('redirect_form', $form_html);
			}
		}

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