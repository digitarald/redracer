<?php

class Account_LoginResponseAction extends RedBaseAction
{
	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$store = new Auth_OpenID_FileStore(AgaviConfig::get('core.cache_dir') . '/openid');
		$consumer = new Auth_OpenID_Consumer($store);

		/**
		 * Weird clean-up for OpenID validation, it checks all kind of request variables,
		 * and they have to match the return_to value!
		 *
		 * They come as . and PHP translates them to _
		 */
		$sreg = $rd->getParameters();
		foreach ($sreg as $var => $val)
		{
			if (strpos($var, 'openid_') !== false)
			{
				$repl = str_replace('openid_', 'openid.', $var);
				$repl = str_replace('.ns_', '.ns.', $repl);
				$repl = str_replace('.pape_', '.pape.', $repl);

				$sreg[$repl] = $val;
				unset($sreg[$var]);
			}
		}
		unset($sreg['module']);
		unset($sreg['action']);

		// Begin the OpenID authentication process.
		$response = $consumer->complete($sreg['openid.return_to'], $sreg);

		// Check the response status.
		switch ($response->status)
		{
			case Auth_OpenID_CANCEL:
				// This means the authentication was cancelled.
				$this->us->addFlash('Verification cancelled.', 'error');
				return $this->handleError($rd);

			case Auth_OpenID_FAILURE:
				// Authentication failed; display the error message.
				$this->us->addFlash('OpenID authentication failed: ' . $response->message, 'error');
				return $this->handleError($rd);
		}

		// This means the authentication succeeded; extract the
		// identity URL and Simple Registration data (if it was
		// returned).
		$url = RedString::normalizeURL($response->getDisplayIdentifier());

		$table = Doctrine::getTable('UserModel');
		$user = $table->findOneByOpenId($url);

		if (!$user)
		{
			if (isset($sreg['email']) )
			{
				$user = $table->findOneByEmail($sreg['email']);
			}
			if (!$user)
			{
				$user = $this->context->getModel('User');
			}

			$user_id = new UserIdModel();
			$user_id['url'] = $url;

			$user['user_ids'][0] = $user_id;
		}

		$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
		$sreg = $sreg_resp->contents();

		if (isset($sreg['nickname']) )
		{
			$user['nickname'] = $sreg['nickname'];
		}
		elseif (!$user['nickname'])
		{
			$user['nickname'] = $url;
		}

		if (isset($sreg['email']) )
		{
			$user['email'] = $sreg['email'];
		}

		if (isset($sreg['country']) )
		{
			$user['country'] = $sreg['country'];
		}
		if (isset($sreg['dob']) )
		{
			$user['dob'] = $sreg['dob'];
		}
		if (isset($sreg['fullname']) )
		{
			$user['fullname'] = $sreg['fullname'];
		}
		if (isset($sreg['language']) )
		{
			$user['language'] = $sreg['language'];
		}
		if (isset($sreg['postcode']) )
		{
			$user['postcode'] = $sreg['postcode'];
		}
		if (isset($sreg['timezone']) )
		{
			$user['timezone'] = $sreg['timezone'];
		}

		/**
		 * @todo More error checking and better messages
		 */
		if (!$user->isValid() )
		{
			$this->us->addFlash('Could not save the user. If you are already registered, use your original OpenID or contact a moderator.', 'error');

			return $this->handleError($rd);
		}

		if (!$user->exists() )
		{
			$this->us->addFlash(sprintf('Welcome to the community, %s! Your profile was created and linked to <code>%s</code> successfully.', $user['fullname'], $url), 'success');
		}
		else
		{
			$this->us->addFlash(sprintf('Welcome back, %s, your last login was %s.', $user['fullname'], RedDate::prettyDate($user['login_at']) ), 'success');
		}
		$this->us->login($user);

		return 'Success';
	}

	public function getDefaultViewName()
	{
		return 'Error';
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		return 'Error';
	}

}

?>