<?php

class Hub_View_BookmarkErrorView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		// set the title
		$this->setAttribute('_title', 'View.Bookmark Action');
	}
}

?>