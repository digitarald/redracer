<?php

class Hub_Contributors_Contributor_EditSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$resource = $this->getAttribute('resource');
		$contributor = $this->getAttribute('contributor');

		$msg = sprintf('Contributor “%s” saved successfully.', $contributor['user']['display_name']);
		$this->us->addFlash($msg, 'success');

		return $this->redirect($resource['url']);
	}
}

?>