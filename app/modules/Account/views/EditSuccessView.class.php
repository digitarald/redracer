<?php

class Account_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('user');

		$this->us->addFlash('Profile changes saved successfully.', 'success');

		return $this->redirect($this->rt->gen('index') ); // $model['url']
	}
}

?>