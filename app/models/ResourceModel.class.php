<?php
/**
 * Resource
 *
 * @todo	License as extra relation
 *
 */
class ResourceModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('resource');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned'	=> true,
			'notnull'	=> true,
			'primary'	=> true
		) );

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned'	=> true
		) );
		$this->hasColumn('claimed', 'boolean', array(
			'default'	=> 0
		) );
		$this->hasColumn('author', 'string', 255);

		$this->hasColumn('ident', 'string', 32, array(
			'notnull'	=> true,
			'unique'	=> true
		) );
		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string');

		$this->hasColumn('url_feed', 'string', 255);
		$this->hasColumn('url_repository', 'string', 255);

		$this->hasColumn('license_url', 'string', 255);
		$this->hasColumn('license_text', 'string', 255);

		$this->hasColumn('type', 'integer', 1, array(
			'unsigned'	=> true,
			'notnull'	=> true
		) );

		$this->hasColumn('views', 'integer', 6, array(
			'unsigned'	=> true,
			'notnull'	=> true,
			'default'	=> 0
		) );

		$this->hasColumn('rating', 'integer', 1, array(
			'unsigned'	=> true,
			'notnull'	=> true,
			'default'	=> 0
		) );

		$this->hasColumn('status', 'integer', 1, array(
			'unsigned'	=> true,
			'notnull'	=> true,
			'default'	=> 0
		) );

		$this->hasColumn('core_min', 'string', 16);
		$this->hasColumn('core_max', 'string', 16);
	}

	public function setUp()
	{
		$this->actAs('Timestampable');
		$this->actAs('SoftDelete');

		$this->index('type', array('fields' => 'type') );
		$this->index('status', array('fields' => 'status') );

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local'		=> 'user_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );

		$this->hasMany('UserModel as users', array(
			'local'		=> 'user_id',
			'foreign'	=> 'resource_id',
			'refClass'	=> 'StackModel',
			'onDelete'	=> 'SET NULL'
		) );

		$this->hasMany('ResourceTagRefModel as tag_refs', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('TagModel as tags', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'tag_id',
			'refClass'	=> 'ResourceTagRefModel'
		) );

		$this->hasMany('ContributorModel as contributors', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('ReviewModel as reviews', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('LinkModel as links', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('ReleaseModel as releases', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('DependencyModel as dependencies', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['text_html'] = OurString::format($ret['text']);

		/**
		 * @todo Find an optimized version to cut an intro.
		 */
		$cuts = array();
		$pos = strpos($ret['text_html'], '</p>');
		if ($pos !== false)
		{
			$cuts[] = $pos + 4;
		}
		$pos = strpos($ret['text_html'], '</pre>');
		if ($pos !== false)
		{
			$cuts[] = $pos + 6;
		}
		$pos = strpos($ret['text_html'], '<h');
		if ($pos !== false)
		{
			$cuts[] = $pos;
		}
		$ret['text_intro'] = $ret['text_html'];
		if (count($cuts) )
		{
			$ret['text_intro'] = substr($ret['text_html'], 0, min($cuts) );
		}

		$ret['url'] = $this->context->getRouting()->gen('resources.resource.view', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_release'] = $this->context->getRouting()->gen('resources.resource.release.edit', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_contributor'] = $this->context->getRouting()->gen('resources.resource.contributor.edit', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_link'] = $this->context->getRouting()->gen('resources.resource.link.edit', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_edit'] = $this->context->getRouting()->gen('resources.resource.edit', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_claim'] = $this->context->getRouting()->gen('resources.resource.claim', array(
			'ident'	=> $ret['ident']
		) );


		return $ret;
	}

	public function setTagIds(array $tag_ids)
	{
		foreach ($this['tag_refs'] as $idx => $tag_ref)
		{
			$found = array_search($tag_ref['tag_id'], $tag_ids);

			if ($found === false)
			{
				$tag_ref->delete();
			}
			else
			{
				unset($tag_ids[$found]);
			}
		}

		foreach ($tag_ids as $tag_id)
		{
			$ref = new ResourceTagRefModel();
			$ref['tag_id'] = $tag_id;
			$this['tag_refs'][] = $ref;
		}
	}

}

?>