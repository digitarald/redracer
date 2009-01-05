<?php

class Cart_IndexErrorView extends RedCartBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Index');
	}
}

?>