<?php

class Hub_Contributors_AddSuccessView extends RedBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$model = $this->getAttribute('contributor');

		$this->us->addFlash('Contributor added successfully. Thank you for contributing.', 'success');

		return $this->redirect($model['url_edit']);
	}
}

?>