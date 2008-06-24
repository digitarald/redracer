<?php


class BookmarkModel extends ResourceChildModel
{
	const TYPE_ID = 0;

	public function setTableDefinition()
	{
		parent::setTableDefinition();

		$this->setTableName('bookmarks');

		$this->hasColumn('url', 'string', 255);
	}

}

?>