<?php

class Hub_Edit_BookmarkErrorView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		// set the title
		$this->setAttribute('_title', 'Edit.Bookmark Action');
	}
}

?>