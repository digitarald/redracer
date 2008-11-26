<?php

class Hub_Releases_Release_DeleteSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource')->toArray();
		$release = $this->getAttribute('release');

		$msg = sprintf('Release “%s” successfully removed from “%s”.', $release['version'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($resource['url_view']);
	}
}

?>