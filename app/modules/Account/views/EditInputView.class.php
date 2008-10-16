<?php

class Account_EditInputView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$data =& $this->getAttribute('user');

		if ($this->rq->getMethod() == 'read')
		{
			$this->rq->setAttribute('populate', array(
				'form-edit'	=> new AgaviParameterHolder($data)
			), 'org.agavi.filter.FormPopulationFilter');
		}

		$this->setAttribute('title', sprintf('Editing “%s”', $data['fullname']) );
	}
}

?>