<?php

class Hub_Edit_BookmarkInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		// set the title
		$this->setAttribute('_title', 'Edit.Bookmark Action');
	}
}

?>