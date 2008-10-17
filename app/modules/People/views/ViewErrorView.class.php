<?php

class People_ViewErrorView extends RedPeopleBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'View');
	}
}

?>