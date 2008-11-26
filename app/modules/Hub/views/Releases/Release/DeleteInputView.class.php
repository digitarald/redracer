<?php

class Hub_Releases_Release_DeleteInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$release = $this->getAttribute('release');

		$this->setAttribute('title', sprintf('Deleting Release “%s” » %s', $release['version'], $resource['title']));
	}
}

?>