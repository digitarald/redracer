<?php

class Hub_LinkEditSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('resource');

		if ($this->getAttribute('deleted') )
		{
			$this->us->addFlash('Link deleted successfully.');
		}
		else
		{
			$this->us->addFlash('Link saved successfully. Thank you for contributing.', 'success');
		}

		return $this->redirect($model['url']);
	}
}

?>