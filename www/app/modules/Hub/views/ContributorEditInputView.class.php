<?php

class Hub_ContributorEditInputView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $this->getAttribute('resource');

		$this->setAttribute('url', $this->rt->gen('hub.resource', array(
			'ident'	=> $resource['ident']
		) ) );

		$model = $this->getAttribute('contributor');

		if ($model)
		{
			if ($this->rq->getMethod() == 'read')
			{
				$this->rq->setAttribute('populate', array(
					'form-edit'	=> new AgaviParameterHolder($model)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('url_delete', $this->rt->gen('hub.resource.contributor.edit', array(
				'delete'	=> '1'
			) ) );
			$this->setAttribute('title', sprintf('Editing Contributor "%s"', $model['user']['fullname']) );
		}
		else
		{
			$user = $this->us->getProfile();

			$this->setAttribute('title', sprintf('Add "%s" as Contributor for "%s"', $user['fullname'], $resource['title']) );
		}
	}
}

?>