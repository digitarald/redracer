<?php

class Account_LoginResponseErrorView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->us->appendAttribute('messages', 'Log in failed', 'our.flash');

		return $this->redirect($this->rt->gen('account.login') );
	}
}

?>