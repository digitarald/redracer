<?php

class Hub_Releases_AddSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');
		$release = $this->getAttribute('release');

		$msg = sprintf('You successfully added the release %s to %s.', $release['version'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($release['url_view']);
	}
}

?>