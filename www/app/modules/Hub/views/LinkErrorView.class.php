<?php

class Hub_LinkErrorView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message'	=> 'Link not found!'
		) );
	}
}

?>