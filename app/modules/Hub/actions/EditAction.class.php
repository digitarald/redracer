<?php

class Hub_EditAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	protected $licenses;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$model = $this->resource;

		$model['title'] = $rd->getParameter('title');
		$model['text'] = $rd->getParameter('text');

		$model['type'] = $rd->getParameter('type');
		$model['claimed'] = $rd->hasParameter('claimed');
		$model['author'] = $rd->getParameter('author');

		$model['url_feed'] = $rd->getParameter('url_feed');
		$model['url_repository'] = $rd->getParameter('url_repository');

		$core_min = $rd->getParameter('core_min');
		$core_max = $rd->getParameter('core_max');

		$model['core_max'] = $model['core_min'] = null;

		if ($core_max && $core_min)
		{
			$model['core_min'] = $core_min;
			$model['core_max'] = $core_max;
		}
		elseif ($core_max || $core_min)
		{
			$model['core_min'] = ($core_min) ? $core_min : $core_max;
			$model['core_max'] = null;
		}

		$model['license_text'] = $rd->getParameter('license_text');
		$model['license_url'] = $rd->getParameter('license_url');

		$tag_ids = $rd->getParameter('tag_ids', array() );

		$model->setTagIds($tag_ids);

		if (!$model->trySave() )
		{
			$this->us->addFlash('Resource was not saved, but the programmer was too lazy to check!', 'error');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('resource', $model->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$model = $this->resource->toArray(true);

		$model['tag_ids'] = array();
		foreach ($model['tags'] as $tag)
		{
			$model['tag_ids'][] = $tag['id'];
		}

		$this->setAttributeByRef('resource', $model);

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		$license = $rd->getParameter('license');
		if ($license)
		{
			$license_text = array_search($license, $this->licenses);

			$rd->setParameter('license_text', $license_text);
			$rd->setParameter('license_url', $license);
		}

		return $ret;
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		$this->licenses = json_decode(file_get_contents(AgaviConfig::get('core.config_dir') . '/licenses.json'), true);
		$this->setAttributeByRef('licenses', $this->licenses);

		if ($rd->hasParameter('ident') )
		{
			$table = Doctrine::getTable('ResourceModel');
			$this->resource = $table->findOneByIdent($rd->getParameter('ident') );

			if (!$this->resource)
			{
				return false;
			}
			/**
			 * @todo check credentials
			 */
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->resource)
		{
			return 'Error';
		}

		return 'Input';
	}

	public function isSecure()
	{
		return true;
	}

}

?>