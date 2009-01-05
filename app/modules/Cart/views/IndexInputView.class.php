<?php

class Cart_IndexInputView extends RedCartBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$downloads = $this->getAttribute('downloads');

		$this->setAttribute('title', 'Download Cart');
	}
}

?>