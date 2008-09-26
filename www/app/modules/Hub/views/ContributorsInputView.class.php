<?php

class Hub_ContributorsInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$model = $this->getAttribute('resource');

		$this->setAttribute('url', $this->rt->gen('hub.resource.edit', array(
			'ident'	=> $model['ident']
		) ) );

		$this->setAttribute('title', 'Editing Contributors');
	}
}

?>