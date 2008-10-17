<?php

class Hub_Contributors_Contributor_DeleteErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message'	=> 'Contributor not found!'
		) );
	}
}

?>