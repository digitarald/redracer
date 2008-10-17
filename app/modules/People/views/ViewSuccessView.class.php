<?php

class People_ViewSuccessView extends RedPeopleBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'View');
	}
}

?>