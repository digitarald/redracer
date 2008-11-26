<?php

class Hub_SubmitSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');

		$msg = sprintf('Resource %s was successfully added!', $resource['title']);
		$this->us->addFlash($msg, 'success');

		$this->redirect($resource['url_view']);
	}
}

?>