<?php


class TagModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('tags');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('count', 'integer', 8, array(
			'unsigned' => true,
			'default' => 0
		) );

		$this->hasColumn('word', 'string', 255, array(
			'unique' => true
		) );

		$this->hasColumn('status', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
	}

	public function setUp()
	{
		$this->index('status', array('fields' => 'status') );

		$this->hasMany('ResourceModel as resources', array(
			'local' => 'tag_id',
			'foreign' => 'resource_id',
			'refClass' => 'ResourceTagRefModel'
		) );
	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['word_clear'] = $this->getWordClear();

		$ret['url'] = $this->context->getRouting()->gen('resources.index', array(
			'tags' => $ret['word']
		) );

		return $ret;
	}

	public function getWordClear()
	{
		return preg_replace_callback('/[-_](\D)/', create_function('$match','return (\' \' . strtoupper($match[1]));'), ucfirst($this['word']) );
	}


}

?>