<?php

class Account_LoginResponseSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		if ($rd->hasParameter('remember') )
		{
			/**
			 * @todo Token-based remember cookie
			 */
			$this->response->setCookie('autologon', $rd->getParameter('openid_identity'), 3600 * 24 * 14);
		}

		if ($this->us->hasAttribute('redirect', 'our.login') )
		{
			return $this->redirect($this->us->removeAttribute('redirect', 'our.login'));
		}

		return $this->redirect($this->rt->gen('account.index') );
	}
}

?>