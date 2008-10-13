<?php

class Account_EditAction extends RedBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$model = $this->us->getProfile();

		$model['github_user'] = $rd->getParameter('github_user');
		$model['paypal_user'] = $rd->getParameter('paypal_user');

		if (!$model->trySave() )
		{
			$this->vm->setError('id', 'Resource was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('user', $model->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$model = $this->us->getProfile();

		$data = $model->toArray();
		$data['open_ids'] = $model['user_ids']->toArray(true);

		$this->setAttribute('user', $data);

		return 'Input';
	}

	public function isSecure()
	{
		return true;
	}

}

?>