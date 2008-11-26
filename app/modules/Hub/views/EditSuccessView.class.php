<?php

class Hub_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');

		$this->us->addFlash(sprintf('“%s” updated successfully.', $resource['title']), 'success');

		return $this->redirect($resource['url_view']);
	}
}

?>