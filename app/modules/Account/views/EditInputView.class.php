<?php

class Account_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$user = $this->getAttribute('user');

		if ($this->rq->getMethod() == 'read')
		{
			$this->rq->setAttribute('populate', array(
				'form-edit' => new AgaviParameterHolder($user)
			), 'org.agavi.filter.FormPopulationFilter');
		}

		$this->setAttribute('title', sprintf('Editing “%s”', $user['display_name']) );
	}
}

?>