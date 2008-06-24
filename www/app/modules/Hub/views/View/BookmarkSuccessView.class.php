<?php

class Hub_View_BookmarkSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		// set the title
		$this->setAttribute('_title', 'View.Bookmark Action');
	}
}

?>