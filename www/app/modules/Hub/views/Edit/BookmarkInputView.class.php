<?php

class Hub_Edit_BookmarkInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Add/Edit Bookmark');
	}
}

?>