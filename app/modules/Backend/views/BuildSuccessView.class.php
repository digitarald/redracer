<?php

class Backend_BuildSuccessView extends RedBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Build Action');
	}

}

?>