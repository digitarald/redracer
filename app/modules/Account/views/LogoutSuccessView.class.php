<?php

class Account_LogoutSuccessView extends OurBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$remember = $rd->getCookie('remember');

		if ($remember)
		{
			// @todo invalidate/delete token in db
			$this->rs->setCookie('remember', false);
		}

		return $this->redirect($this->rt->gen('account.login') );
	}

}

?>