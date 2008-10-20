<?php

class Hub_SubmitSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('resource');

		$msg = sprintf('The resource “%s” was successfully added! Add more details now and publish it to the crowd when it is ready.', htmlspecialchars($model['url_edit']) );
		$this->us->addFlash($msg, 'success');

		$this->redirect($model['url_edit']);
	}
}

?>