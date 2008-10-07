<?php

class Default_PageSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', ucfirst($rd->getParameter('name') ) );
	}
}

?>