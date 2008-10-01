<?php

class Account_LoginResponseAction extends OurBaseAction
{
	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$openid = new SimpleOpenID();

		// $url = $rd->getParameter('openid_identity');
		$url = $this->us->getAttribute('openid_identity', null, $rd->getParameter('openid_identity') );

		$openid->SetIdentity($url);

		$params = $rd->getParameters();

		$result = $openid->ValidateWithServer($params);

		if (!$result)
		{
			if ($openid->IsError() == true)
			{
				$error = $openid->GetError();

				$this->vm->setError('openid_identity', $error['description'] . ' (' . $error['code'] . ')');
			}

			$this->us->addFlash('Please check your Open ID, verification failed.', 'error');

			return $this->handleError($rd);
		}

		$url = SimpleOpenID::OpenID_Standarize($url);

		$table = Doctrine::getTable('UserModel');
		$user = $table->findOneByOpenId($url);

		if (!$user)
		{
			if (isset($params['openid_sreg_email']) )
			{
				$user = $table->findOneEmail($params['openid_sreg_email']);
			}
			if (!$user)
			{
				$user = $this->context->getModel('User');
			}

			$user_id = new UserIdModel();
			$user_id['url'] = $url;

			$user['user_ids'][0] = $user_id;
		}

		if (isset($params['openid_sreg_country']) )
		{
			$user['country'] = $params['openid_sreg_country'];
		}
		if (isset($params['openid_sreg_dob']) )
		{
			$user['dob'] = $params['openid_sreg_dob'];
		}
		if (isset($params['openid_sreg_email']) )
		{
			$user['email'] = $params['openid_sreg_email'];
		}
		if (isset($params['openid_sreg_fullname']) )
		{
			$user['fullname'] = $params['openid_sreg_fullname'];
		}
		if (isset($params['openid_sreg_language']) )
		{
			$user['language'] = $params['openid_sreg_language'];
		}
		if (isset($params['openid_sreg_nickname']) )
		{
			$user['nickname'] = $params['openid_sreg_nickname'];
		}
		if (isset($params['openid_sreg_postcode']) )
		{
			$user['postcode'] = $params['openid_sreg_postcode'];
		}
		if (isset($params['openid_sreg_timezone']) )
		{
			$user['timezone'] = $params['openid_sreg_timezone'];
		}

		if (!$user->isValid() )
		{
			$this->us->addFlash('Could not update the user. If you are already registered, use your original OpenID or contact a moderator.', 'error');

			return $this->handleError($rd);
		}

		if (!$user->exists() )
		{
			$this->us->addFlash(sprintf('Welcome to the community, %s! Your profile was created and linked to <code>%s</code> successfully.', $user['fullname'], $url), 'success');
		}
		else
		{
			$this->us->addFlash(sprintf('Log in successfull! Welcome back, %s, your last login was %s.', $user['fullname'], OurDate::prettyDate($user['login_at']) ), 'success');
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