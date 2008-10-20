<?php

class Backend_BuildSuccessView extends RedBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->loadLayout();

		$this->setAttribute('title', 'Build Action');
	}

}

?>