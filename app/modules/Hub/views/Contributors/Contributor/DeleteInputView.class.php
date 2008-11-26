<?php

class Hub_Contributors_Contributor_DeleteInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$contributor = $this->getAttribute('contributor');

		$this->setAttribute('title', sprintf('Deleting Contributor “%s” » %s', $contributor['user']['display_name'], $resource['title']));
	}
}

?>