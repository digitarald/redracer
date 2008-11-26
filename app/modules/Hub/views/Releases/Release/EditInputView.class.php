<?php

class Hub_Releases_Release_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$this->setAttributeByRef('resource', $resource);

		$release = $this->getAttribute('release');
		if ($release) {
			if ($this->rq->getMethod() == 'read') {
				$this->rq->setAttribute('populate', array(
					'form-edit' => new AgaviParameterHolder($release)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('title', sprintf('Release %s – %s', $release['version'], $resource['title']));
		} else {
			$this->setAttribute('title', sprintf('New Release – %s', $resource['title']));

			if ($this->rq->getMethod() == 'read') {
				$this->rq->setAttribute('populate', array(
					'form-edit' => new AgaviParameterHolder(array(
						'url_source' => $resource['url_source'],
						'stability' => $resource['stability']
					))
				), 'org.agavi.filter.FormPopulationFilter');
			}
		}
	}
}

?>