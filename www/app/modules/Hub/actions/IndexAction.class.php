<?php

class Hub_IndexAction extends OurBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		$table = Doctrine::getTable('ResourceModel');

		$query = $table->getQuery();

		$page = $rd->getParameter('page', 1);
		$max = $rd->getParameter('per_page', 25);

		$query = $table->getQuery();
		$params = array();

		/**
		 * Kinda bad coding style, but it works for now
		 */

		if ($rd->hasParameter('type') )
		{
			switch ($rd->getParameter('type') )
			{
				case 'project':
					$query->addWhere('resource.type = 0');
					break;
				case 'article':
					$query->addWhere('resource.type = 1');
					break;
				case 'snippet':
					$query->addWhere('resource.type = 2');
			}
		}

		if ($rd->hasParameter('tags') )
		{
			$table = Doctrine::getTable('TagModel');
			$tag = $table->findOneByWord($rd->getParameter('tags') );

			if ($tag)
			{
				$query->addWhere('tags.id = ' . $tag['id']);
			}
		}

		switch ($rd->getParameter('sort') )
		{
			case 'popular':
				$query->addOrderBy('resource.views');
				break;
			case 'rating':
				$query->addOrderBy('resource.rating');
				break;
			case 'title':
				$query->addOrderBy('resource.title');
			default:
				$query->addOrderBy('resource.created_at');
				break;
		}

		$pager = new Doctrine_Pager($query, $page, $max);

		$resources = $pager->execute();

		$this->setAttribute('resources', $resources->toArray(true) );

		return 'Success';
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		return $this->execute($rd);
	}
}

?>