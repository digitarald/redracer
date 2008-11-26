<?php

class Hub_Releases_Release_DeleteErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message' => 'Release not found!'
		) );
	}
}

?>