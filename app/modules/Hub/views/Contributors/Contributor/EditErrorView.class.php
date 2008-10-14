<?php

class Hub_ContributorEditErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message'	=> 'Resource not found!'
		) );
	}
}

?>