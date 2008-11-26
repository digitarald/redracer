<?php

class Hub_Links_Link_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$link = $this->getAttribute('link');

		$msg = sprintf('Link “%s” saved successfully.', $link['title']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($this->rt->gen('resources.resource.view'));
	}
}

?>