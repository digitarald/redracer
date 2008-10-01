<?php

class Hub_ContributorEditSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('resource');

		if ($this->getAttribute('deleted') )
		{
			$this->us->addFlash('Contributor deleted successfully.', 'success');
		}
		else
		{
			$this->us->addFlash('Contributor saved successfully. Thank you for contributing.', 'success');
		}

		return $this->redirect($model['url']);
	}
}

?>