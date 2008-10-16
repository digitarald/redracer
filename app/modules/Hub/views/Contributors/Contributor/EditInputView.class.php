<?php

class Hub_Contributors_Contributor_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$model = $this->getAttribute('contributor');

		if ($this->rq->getMethod() == 'read') {
			$this->rq->setAttribute('populate', array(
				'form-edit'	=> new AgaviParameterHolder($model)
			), 'org.agavi.filter.FormPopulationFilter');
		}

		$this->setAttribute('url_delete', $this->rt->gen('resources.resource.contributors.contributor.delete') );
		$this->setAttribute('title', sprintf('Editing Contributor “%s”', $model['user']['fullname']) );
	}
}

?>