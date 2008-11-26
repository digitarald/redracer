<?php

class Hub_Releases_Release_ViewSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$release = $this->getAttribute('release');
		$this->setAttribute('resource', $release['resource']);

		$this->setAttribute('complete', $rd->hasParameter('complete'));

		$this->setAttribute('title', sprintf('Release %s – %s', $release['version'], $release['stability_text'], $release['resource']['title']));
	}
}

?>