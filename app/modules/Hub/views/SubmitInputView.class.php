<?php

class Hub_SubmitInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Submit New Resource');
	}
}

?>