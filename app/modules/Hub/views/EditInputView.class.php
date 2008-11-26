<?php

class Hub_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$peer = $this->context->getModel('Tags');
		$this->setAttribute('tags', $peer->findAll()->toArray() );

		$peer = $this->context->getModel('Licences');
		$this->setAttribute('licences', $peer->findAll()->toArray() );

		$resource = $this->getAttribute('resource');

		if ($resource) {
			$suggestions = array();
			if (!count($resource['tags'])) {
				$suggestions[] = 'Select one or more tags to categorise your resource.';
			}
			if (strlen(trim($resource['description'])) < 10) {
				$suggestions[] = 'Fill the description with more details, it is the first thing that people will read about your resource.';
			}
			if (!count($resource['licences'])) {
				$suggestions[] = 'Select one ore more open-source licences.';
			}
			if (count($suggestions)) {
				$msg = 'Please consider the following hints to enhance this resource:';
				$this->us->addFlash($msg . '<ul><li>' . implode('</li><li>', $suggestions) . '</li></ul>');
			}

			if ($this->rq->getMethod() == 'read') {
				$this->rq->setAttribute('populate', array(
					'form-edit' => new AgaviParameterHolder($resource)
				), 'org.agavi.filter.FormPopulationFilter');
			}

			$this->setAttribute('title', sprintf('Editing %s', $resource['title']) );
		} else {
			$this->setAttribute('title', 'New Resource');
		}

	}
}

?>