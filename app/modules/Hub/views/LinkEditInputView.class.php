<?php

class Hub_LinkEditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $this->getAttribute('resource');

		$this->setAttribute('url', $this->rt->gen('resources.resource.view', array(
			'ident'	=> $resource['ident']
		) ) );

		$model = $this->getAttribute('link');

		if ($model)
		{
			if ($this->rq->getMethod() == 'read')
			{
				$this->rq->setAttribute('populate', array(
					'form-edit'	=> new AgaviParameterHolder($model)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('url_delete', $this->rt->gen('resources.resource.link.edit', array(
				'delete'	=> '1'
			) ) );
			$this->setAttribute('title', sprintf('Editing Link "%s"', $model['title']) );
		}
		else
		{
			$this->setAttribute('title', sprintf('New Link for "%s"', $resource['title']) );
		}

	}
}

?>