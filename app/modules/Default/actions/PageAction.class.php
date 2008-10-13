<?php

class Default_PageAction extends RedBaseAction
{

	protected $file;

	public function execute(AgaviRequestDataHolder $rd)
	{
		$contents = RedString::format(file_get_contents($this->file), 2);

		$this->setAttributeByRef('content', $contents);

		return 'Success';
	}

	public function validate(AgaviRequestDataHolder $rd)
	{
		$name = $rd->getParameter('name');

		if (!$this->vm->hasError('name') )
		{
			$dir = AgaviConfig::get('core.app_dir') . '/../';

			if (is_readable($dir . strtoupper($name) ) )
			{
				$this->file = $dir . strtoupper($name);
			}
			else
			{
				$this->vm->setError('name', 'Name does not exist!');
				return false;
			}
		}

		return true;
	}

}

?>