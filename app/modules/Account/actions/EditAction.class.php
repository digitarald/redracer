<?php

class Account_EditAction extends RedBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$model = $this->us->getProfile();

		$model['github_user'] = $rd->getParameter('github_user');
		$model['paypal_user'] = $rd->getParameter('paypal_user');

		if (!$model->trySave() ) {
			$this->vm->setError('resource', 'Resource was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('user', $model->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$model = $this->us->getProfile();

		$user = $model->toArray(true);
		$user['user_auths'] = $model['user_auths']->toArray(true);

		$this->setAttributeByRef('user', $data);

		return 'Input';
	}

	public function isSecure()
	{
		return true;
	}

}

?>