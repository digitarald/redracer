<?php

class Hub_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');

		$this->us->addFlash(sprintf('Resource “%s” saved successfully.', $resource['title']), 'success');

		return $this->redirect($resource['url']);
	}
}

?>