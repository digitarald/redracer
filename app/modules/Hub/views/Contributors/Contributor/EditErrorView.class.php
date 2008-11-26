<?php

class Hub_Contributors_Contributor_EditErrorView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message' => 'Contributor does not exist in the given resource!',
			'return' => $this->rt->gen('resources.resource.view')
		) );
	}
}

?>