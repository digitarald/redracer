<?php

class Hub_Contributors_Contributor_DeleteInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$model = $this->getAttribute('contributor');

		$this->setAttribute('url', $model['url_edit']);
		$this->setAttribute('title', sprintf('Deleting Contributor “%s”', $model['user']['display_name']) );
	}
}

?>