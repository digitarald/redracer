<?php

class Account_EditSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('user');

		$this->us->addFlash('Profile changes saved successfully.', 'success');

		return $this->redirect($model['url']);
	}
}

?>