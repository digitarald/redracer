<?php

class Account_LogoutSuccessView extends OurBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->redirect($this->rt->gen('account.login') );
	}

}

?>