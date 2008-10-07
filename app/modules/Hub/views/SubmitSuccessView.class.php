<?php

class Hub_SubmitSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('resource');

		$this->us->addFlash('The resource was submitted successfully, thanks a lot for your contribution! You can now add more details and let moderators review your submission.', 'success');

		$this->redirect($model['url_edit']);
	}
}

?>