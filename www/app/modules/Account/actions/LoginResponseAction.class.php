<?php

class Account_LoginResponseAction extends OurBaseAction
{
	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$openid = new SimpleOpenID();

		$vm = $this->container->getValidationManager();

		$url = $rd->getParameter('openid_identity');
		$openid->SetIdentity($url);

		$params = $rd->getParameters();

		$result = $openid->ValidateWithServer($params);

		if (!$result)
		{
			if ($openid->IsError() == true)
			{
				$error = $openid->GetError();

				$vm->setError('openid_identity', $error['description'] . ' (' . $error['code'] . ')');
			}

			$this->us->addFlash(sprintf('Please check your Open ID, verification failed.'), 'error');

			return $this->handleError($rd);
		}

		$url = SimpleOpenID::OpenID_Standarize($url);

		$table = Doctrine::getTable('UserModel');
		$user = $table->findOneByOpenId($url);

		if (!$user)
		{
			$user = $this->context->getModel('User');

			$user['user_ids'][0]['url'] = $url;

			$this->us->addFlash(sprintf('Welcome to the community, %s! Your profile was created successfully.', $user['fullname']), 'success');
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

		$this->us->login($user);

		$this->us->addFlash(sprintf('Log in successfull! Welcome back, %s.', $user['fullname']), 'success');

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