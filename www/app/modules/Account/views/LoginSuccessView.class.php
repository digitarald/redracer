<?php

class Account_LoginSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->redirect($this->getAttribute('redirect') );
	}
}

?>