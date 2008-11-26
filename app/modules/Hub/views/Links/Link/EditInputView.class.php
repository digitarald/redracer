<?php

class Hub_Links_Link_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$this->setAttributeByRef('resource', $resource);

		$link = $this->getAttribute('link');
		if ($link) {
			if ($this->rq->getMethod() == 'read') {
				$this->rq->setAttribute('populate', array(
					'form-edit' => new AgaviParameterHolder($link)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('title', sprintf('Link %s – %s', $link['title'], $resource['title']));
		} else {
			$this->setAttribute('title', sprintf('New Link – %s', $resource['title']));
		}
	}
}

?>