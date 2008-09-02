<?php

class Hub_EditInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$model = $this->getAttribute('resource');

		$this->setAttribute('url', $this->rt->gen('hub.resource', array(
			'ident'	=> $model['ident']
		) ) );

		$this->setAttribute('title', 'Editing');
	}
}

?>