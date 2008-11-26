<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Resource extends BaseResource
{
	public static $categories = array('Project', 'Article', 'Snippet');

	public static $flags = array(
		1 => 'Approved',
		2 => 'Official',
		4 => 'Role Model'
	);

	public static $stabilities = array('Stable', 'Beta', 'Alpha', 'Development');

	/**
	 * toArray
	 *
	 * @param      boolean $deep - Return also the relations
	 * @return     array
	 */
	public function toArray($deep = false, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		if (!$ret['ident']) {
			return $ret;
		}

		if ($ret['description']) {
			$ret['description_html'] = RedString::format($ret['description']);
		}
		if ($ret['readme']) {
			$ret['readme_html'] = RedString::format($ret['readme']);
		}


		$is_contributor = false;
		$user_id = $this->getContext()->getUser()->getAttribute('id', 'org.redracer.user');
		if ($user_id && $this->hasContributor($user_id)) {
			$is_contributor = true;
		}
		$ret['is_contributor'] = $is_contributor;

		$ret['repository_dir'] = $this->getRepositoryDir();
		$ret['flag_mask'] = $this->getFlagMask();
		$ret['category_text'] = $this->getCategoryText();
		$ret['stability_text'] = $this->getStabilityText();

		$ret['tag_ids'] = $this->getTagIds();
		$ret['licence_ids'] = $this->getLicenceIds();

		$ret['url_view'] = $this->getContext()->getRouting()->gen('resources.resource.view', array(
			'resource' => $ret['ident']
		));
		$ret['url_contributor'] = $this->getContext()->getRouting()->gen('resources.resource.contributors.add', array(
			'resource' => $ret['ident']
		));
		$ret['url_release'] = $this->getContext()->getRouting()->gen('resources.resource.releases.add', array(
			'resource' => $ret['ident']
		));
		$ret['url_link'] = $this->getContext()->getRouting()->gen('resources.resource.links.add', array(
			'resource' => $ret['ident']
		));
		$ret['url_edit'] = $this->getContext()->getRouting()->gen('resources.resource.edit', array(
			'resource' => $ret['ident']
		));

		return $ret;
	}

	public function postInsert($event)
	{
		AgaviToolkit::mkdir($this->getRepositoryDir());
	}

	public function postDelete($event)
	{
		$dir = $this->getRepositoryDir();
		if (is_dir($dir)) {
			unlink($dir);
		}
	}

	/**
	 * @return     string
	 */
	public function getRepositoryDir()
	{
		return AgaviConfig::get('org.redracer.config.resource.repository_dir', '') . '/' . $this['id'];
	}

	public function hasContributor($user_id) {
		foreach ($this['contributors'] as $contributor) {
			if ($contributor['user_id'] == $user_id) {
				return true;
			}
		}
		return false;
	}

	public function getCategoryText()
	{
		return self::$categories[$this['category']];
	}

	public function getStabilityText()
	{
		return self::$stabilities[$this['stability']];
	}

	public function getFlagMask()
	{
		$flag = (int) $this['flag'];
		$mask = array();
		foreach (self::$flags as $bit => $text) {
			if ($flag & $bit) {
				$mask[] = $bit;
			}
		}
		return $mask;
	}

	public function setFlagMask(array $mask)
	{
		$bits = 0;
		foreach ($mask as $bit) {
			$bits .= $bit;
		}
		$this['flag'] = (string) $bits;
	}

	public function getTagIds()
	{
		return $this->getIdsByRelations($this['tags']);
	}

	public function setTagIds(array $ids)
	{
		$this->setRelationsByIds($this['resource_tag_ref'], $ids, 'tag_id', 'ResourceTagRef');
	}

	public function getLicenceIds()
	{
		return $this->getIdsByRelations($this['tags']);
	}

	public function setLicenceIds(array $ids)
	{
		$this->setRelationsByIds($this['resource_licence_ref'], $ids, 'licence_id', 'ResourceLicenceRef');
	}
}