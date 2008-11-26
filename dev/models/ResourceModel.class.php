<?php
/**
 * Resource
 *
 * @todo	License as extra relation
 *
 */
class ResourceModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('resource');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('user_id', 'integer', 8, array(
			'unsigned' => true
		) );
		$this->hasColumn('claimed', 'boolean', array(
			'default' => 0
		) );
		$this->hasColumn('author', 'string', 255);

		$this->hasColumn('ident', 'string', 32, array(
			'notnull' => true,
			'unique' => true
		) );
		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string');

		$this->hasColumn('url_feed', 'string', 255);
		$this->hasColumn('url_repository', 'string', 255);

		$this->hasColumn('license_url', 'string', 255);
		$this->hasColumn('license_text', 'string', 255);

		$this->hasColumn('type', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );

		$this->hasColumn('hit_count', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
		$this->hasColumn('release_hit_count', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );

		$this->hasColumn('rating', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
		$this->hasColumn('badges', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );

		$this->hasColumn('status', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
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
			'local' => 'user_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->hasMany('UserModel as users', array(
			'local' => 'user_id',
			'foreign' => 'resource_id',
			'refClass' => 'StackModel',
			'onDelete' => 'SET NULL'
		) );

		$this->hasMany('ResourceTagRefModel as tag_refs', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

		$this->hasMany('TagModel as tags', array(
			'local' => 'resource_id',
			'foreign' => 'tag_id',
			'refClass' => 'ResourceTagRefModel'
		) );

		$this->hasMany('ContributorModel as contributors', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

		$this->hasMany('CommentModel as reviews', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

		$this->hasMany('LinkModel as links', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

		$this->hasMany('ReleaseModel as releases', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

		$this->hasMany('DependencyModel as dependencies', array(
			'local' => 'id',
			'foreign' => 'resource_id'
		) );

	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['text_html'] = RedString::format($ret['text']);

		$is_contributor = false;
		$user_id = $this->context->getUser()->getAttribute('id', 'org.redracer.user');
		if ($user_id && $this->hasContributor($user_id)) {
			$is_contributor = true;
		}
		$ret['is_contributor'] = $is_contributor;

		$ret['url'] = $this->context->getRouting()->gen('resources.resource.view', array(
			'ident' => $ret['ident']
		) );
		$ret['url_release'] = $this->context->getRouting()->gen('resources.resource.releases.add', array(
			'ident' => $ret['ident']
		) );
		$ret['url_contributor'] = $this->context->getRouting()->gen('resources.resource.contributors.add', array(
			'ident' => $ret['ident']
		) );
		$ret['url_link'] = $this->context->getRouting()->gen('resources.resource.link.edit', array(
			'ident' => $ret['ident']
		) );
		$ret['url_edit'] = $this->context->getRouting()->gen('resources.resource.edit', array(
			'ident' => $ret['ident']
		) );

		return $ret;
	}

	public function hasContributor($user_id) {
		foreach ($this['contributors'] as $contributor) {
			if ($contributor['user_id'] == $user_id) {
				return true;
			}
		}
		return false;
	}


	public function setTagIds(array $tag_ids)
	{
		foreach ($this['tag_refs'] as $idx => $tag_ref)
		{
			$found = array_search($tag_ref['tag_id'], $tag_ids);

			if ($found === false) {
				$tag_ref->delete();
			} else {
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