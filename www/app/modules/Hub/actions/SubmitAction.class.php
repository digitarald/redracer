<?php

class Hub_SubmitAction extends OurBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$model = new ResourceModel();

		$model['title'] = $rd->getParameter('title');
		$model['ident'] = $rd->getParameter('ident');
		$model['text'] = $rd->getParameter('text');
		$model['type'] = (int) $rd->getParameter('type');

		$model['user_id'] = $this->us->getAttribute('id', 'our.user');

		if ($rd->getParameter('authorship') )
		{
			$sub = new ContributorModel();

			$sub['user_id'] = $this->us->getAttribute('id', 'our.user');

			$sub['title'] = 'Author';
			$sub['verified'] = false;

			$model['contributors'][] = $sub;

			$model['claimed'] = true;
		}
		else
		{
			$model['author'] = $rd->getParameter('author', 'Unknown');
			$model['claimed'] = false;
		}

		if ($rd->getParameter('url_homepage') )
		{
			$sub = new LinkModel();

			$sub['user_id'] = $this->us->getAttribute('id', 'our.user');

			$sub['url'] = $rd->getParameter('url_homepage');
			$sub['title'] = 'Homepage';
			$sub['priority'] = 0;

			$model['links'][] = $sub;
		}

		if (!$model->trySave() )
		{
			$this->us->addFlash('Resource was not saved, but the programmer was too lazy to check!', 'error');

			return $this->handleError($rd);
		}

		$this->setAttributeByRef('resource', $model->toArray(true) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ident = $rd->getParameter('ident');

		if ($ident)
		{
			$table = Doctrine::getTable('ResourceModel');
			$model = $table->findOneByIdent($ident);

			if ($model)
			{
				$this->vm->setError('ident', 'This ident is already taken, please choose another one!');

				return false;
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		return $this->executeRead($rd);
	}

	public function isSecure()
	{
		return true;
	}


}

?>