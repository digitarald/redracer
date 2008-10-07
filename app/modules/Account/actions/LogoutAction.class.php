<?php

class Account_LogoutAction extends OurBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		$this->us->logout();

		return 'Success';
	}

}

?>