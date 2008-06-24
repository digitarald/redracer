<?php

class Hub_IndexSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Browse');
	}
}

?>