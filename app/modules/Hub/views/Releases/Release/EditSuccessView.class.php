<?php

class Hub_Releases_Release_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$release = $this->getAttribute('release');

		$msg = sprintf('Release “%s” saved successfully.', $release['version']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($this->rt->gen('resources.resource.view'));
	}
}

?>