<?php

class Hub_Links_AddSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');
		$link = $this->getAttribute('link');

		$msg = sprintf('You successfully added the link %s to “%s”.', $link['title'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($this->rt->gen('resources.resource.view'));
	}
}

?>