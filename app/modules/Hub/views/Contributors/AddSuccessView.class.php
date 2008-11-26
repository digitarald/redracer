<?php

class Hub_Contributors_AddSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');
		$contributor = $this->getAttribute('contributor');

		$msg = sprintf('You successfully added the contributor %s to %s.', $contributor['user']['display_name'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($this->rt->gen('resources.resource.view'));
	}
}

?>