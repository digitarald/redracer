<?php

class Cart_IndexSuccessView extends RedCartBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->redirect($this->rt->gen(null, array(
			'file_ids' => null,
			'release_ids' => null,
			'resource_ids' => null
		)));
	}
}

?>