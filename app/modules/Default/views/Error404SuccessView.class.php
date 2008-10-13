<?php

class Default_Error404SuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		// set the title
		$this->setAttribute('title', 'Error404 Action');
	}
}

?>