<?php


class TagModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('tags');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('count', 'integer', 6, array(
			'unsigned' => true,
			'default' => 0
		) );

		$this->hasColumn('word', 'string', 255, array(
			'unique' => true
		) );
	}

	public function setUp()
	{
		$this->hasMany('ResourceModel as rescources', array(
			'local'		=> 'tag_id',
			'foreign'	=> 'resource_id',
			'refClass'	=> 'ResourceTagRefModel'
		) );
	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['url'] = $this->context->getRouting()->gen('hub.index', array(
			'tag'	=> $ret['word']
		) );

		return $ret;
	}

}

?>