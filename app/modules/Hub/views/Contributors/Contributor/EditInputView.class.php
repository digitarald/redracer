<?php

class Hub_Contributors_Contributor_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$resource = $rd->getParameter('resource')->toArray();
		$this->setAttributeByRef('resource', $resource);

		$contributor = $this->getAttribute('contributor');
		if ($contributor) {
			if ($this->rq->getMethod() == 'read') {
				$this->rq->setAttribute('populate', array(
					'form-edit' => new AgaviParameterHolder($contributor)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('title', sprintf('Editing Contributor %s – %s', $contributor['user']['fullname'], $resource['title']));
		} else {
			$user = $this->us->getProfile();
			$this->setAttribute('title', sprintf('New Contributor %s – %s', $user['fullname'], $resource['title']));
		}
	}
}

?>