<?php

class Account_EditAction extends OurBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{


		return $this->executeRead($rd);
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