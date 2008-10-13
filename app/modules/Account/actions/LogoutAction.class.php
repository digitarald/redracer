<?php

class Account_LogoutAction extends RedBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		$this->us->logout();

		return 'Success';
	}

}

?>