<?php

class People_IndexSuccessView extends RedPeopleBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Index');
	}
}

?>