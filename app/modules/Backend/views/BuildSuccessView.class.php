<?php

class Backend_BuildSuccessView extends OurBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Build Action');
	}

}

?>