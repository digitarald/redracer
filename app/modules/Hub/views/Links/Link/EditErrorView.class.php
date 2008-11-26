<?php

class Hub_Links_Link_EditErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message' => 'Link does not exist in the given resource!',
			'return' => $this->rt->gen('resources.resource.view')
		) );
	}
}

?>