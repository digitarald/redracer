<?php

class Hub_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$table = Doctrine::getTable('TagModel');
		$this->setAttribute('tags', $table->findAll()->toArray(true) );

		$model = $this->getAttribute('resource');

		if (!count($model['tags']) ) {
			$this->us->addFlash('Please add select some tags for that resource!');
		}
		if (strlen($model['text']) < 10) {
			$this->us->addFlash('Please add some more words to your description!');
		}
		if (!$model['license_text']) {
			$this->us->addFlash('Please select an open-source license for resource!');
		}

		if ($this->rq->getMethod() == 'read') {
			$this->rq->setAttribute('populate', array(
				'form-edit' => new AgaviParameterHolder($model)
			), 'org.agavi.filter.FormPopulationFilter');
		}

		$this->setAttribute('url', $this->rt->gen('resources.resource.view', array(
			'ident' => $model['ident']
		) ) );

		$this->setAttribute('title', sprintf('Editing %s', $model['title']) );
	}
}

?>