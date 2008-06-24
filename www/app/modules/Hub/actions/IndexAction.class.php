<?php

class Hub_IndexAction extends OurBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		$table = Doctrine::getTable('ResourceModel');

		$query = $table->getQuery();

		$page = $rd->getParameter('page', 1);
		$max = $rd->getParameter('per_page', 25);

		$pager = new Doctrine_Pager($table->getQuery(), $page, $max);

		$resources = $pager->execute();

		$this->setAttribute('resources', $resources->toArray(true) );

		return 'Success';
	}

	public function getDefaultViewName()
	{
		return 'Success';
	}
}

?>