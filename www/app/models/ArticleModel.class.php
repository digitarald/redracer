<?php


class ArticleModel extends ResourceChildModel
{
	const TYPE_ID = 1;

	public function setTableDefinition()
	{
		parent::setTableDefinition();

		$this->setTableName('articles');

		$this->hasColumn('text', 'string');
	}

}

?>