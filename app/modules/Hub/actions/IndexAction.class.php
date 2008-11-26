<?php

class Hub_IndexAction extends RedBaseAction
{
	public function execute(AgaviRequestDataHolder $rd)
	{
		$peer = $this->context->getModel('Resources');
		$query = $peer->getQuery();

		$page = $rd->getParameter('page', 1);
		$max = $rd->getParameter('per_page', 25);

		$params = array();

		if ($rd->hasParameter('category') ) {
			switch ($rd->getParameter('category') ) {
				case 'project':
					$query->addWhere('resource.category = 0');
					break;
				case 'article':
					$query->addWhere('resource.category = 1');
					break;
				case 'snippet':
					$query->addWhere('resource.category = 2');
					break;
				default:
					$this->vm->setError('category', 'Category not found!');
			}
		}

		if ($rd->hasParameter('tags') ) {
			$peer = $this->context->getModel('Tags');
			$tag = $peer->findOneByWord($rd->getParameter('tags') );
			if ($tag) {
				$query->addWhere('tags.id = ' . $tag['id']);
			} else {
				$this->vm->setError('tags', 'Tag not found!');
			}
		}

		switch ($rd->getParameter('sort') ) {
			case 'popular':
				$query->addOrderBy('resource.hits_count');
				break;
			case 'rating':
				$query->addOrderBy('resource.rating_average');
				break;
			case 'title':
				$query->addOrderBy('resource.title');
				break;
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