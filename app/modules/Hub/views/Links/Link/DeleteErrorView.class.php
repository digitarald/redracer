<?php

class Hub_Links_Link_DeleteErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message' => 'Link not found!'
		) );
	}
}

?>