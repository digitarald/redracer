<?php

class Hub_EditSuccessView extends OurBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('resource');
		$this->us->addFlash(sprintf('Resource %s saved successfully. Thank you for contributing.', $model['title']), 'success');

		return $this->redirect($model['url']);
	}
}

?>