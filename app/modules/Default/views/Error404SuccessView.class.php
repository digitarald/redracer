<?php

class Default_Error404SuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		if ($rd->hasParameter('message')) {
			$this->us->addFlash($rd->getParameter('message'), 'error');
		}

		// set the title
		$this->setAttribute('title', 'Error 404 - Not Found');
	}
}

?>