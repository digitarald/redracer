<?php

class Hub_Contributors_AddInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$user = $this->us->getProfile();

		$this->setAttribute('title', sprintf('Add “%s” as Contributor for “%s”', htmlspecialchars($user['fullname']), htmlspecialchars($resource['title']) ) );
	}
}

?>