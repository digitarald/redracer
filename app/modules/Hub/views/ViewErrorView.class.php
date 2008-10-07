<?php

class Hub_ViewErrorView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->createForwardContainer('Default', 'Error404', array(
			'message'	=> 'Resource not found!'
		) );
	}
}

?>