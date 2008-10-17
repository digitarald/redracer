<?php

class Account_LoginSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->us->setAttribute('openid_identifier', $rd->getParameter('openid_identifier') );

		if ($this->hasAttribute('redirect_url') ) {
			return $this->redirect($this->getAttribute('redirect_url') );
		}

		$this->setupHtml($rd, 'slot');

		// disable FPF, to prevent form errors
		$this->rq->setAttribute('output_types', false, 'org.agavi.filter.FormPopulationFilter');
	}
}

?>