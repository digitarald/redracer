<?php

class Hub_Links_Link_DeleteSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource')->toArray();
		$link = $this->getAttribute('link');

		$msg = sprintf('Link “%s” successfully removed from “%s”.', $link['title'], $resource['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($resource['url_view']);
	}
}

?>