<?php

class Hub_EditInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$table = Doctrine::getTable('TagModel');
		$this->setAttribute('tags', $table->findAll()->toArray(true) );

		$model = $this->getAttribute('resource');

		if ($this->rq->getMethod() == 'read')
		{
			$this->rq->setAttribute('populate', array(
				'form-edit'	=> new AgaviParameterHolder($model)
			), 'org.agavi.filter.FormPopulationFilter');
		}

		$this->setAttribute('url', $this->rt->gen('hub.resource', array(
			'ident'	=> $model['ident']
		) ) );

		$this->setAttribute('title', sprintf('Editing %s', $model['title']) );
	}
}

?>