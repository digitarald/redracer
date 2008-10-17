<?php

class Hub_Contributors_AddSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');
		$contributor = $this->getAttribute('contributor');

		$msg = sprintf('Contributor “%s” successfully added to “%s”.', $contributor['user']['display_name'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($contributor['url_edit']);
	}
}

?>