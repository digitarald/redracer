<?php

class Hub_Contributors_Contributor_DeleteSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource')->toArray();
		$contributor = $this->getAttribute('contributor');

		$msg = sprintf('Contributor “%s” successfully removed from “%s”.', $contributor['user']['display_name'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($resource['url_view']);
	}
}

?>