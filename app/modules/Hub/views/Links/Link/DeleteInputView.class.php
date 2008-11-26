<?php

class Hub_Links_Link_DeleteInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$link = $this->getAttribute('link');

		$this->setAttribute('title', sprintf('Deleting Link “%s” » %s', $link['title'], $resource['title']));
	}
}

?>