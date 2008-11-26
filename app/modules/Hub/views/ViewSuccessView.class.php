<?php

class Hub_ViewSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $this->getAttribute('resource');
		$this->setAttribute('title', $resource['title']);
	}
}

?>