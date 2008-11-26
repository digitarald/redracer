<?php

class Hub_Releases_Release_ViewErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message' => 'Release does not exist in the given resource!',
			'return' => $this->rt->gen('resources.resource.view')
		) );
	}
}

?>