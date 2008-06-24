<?php

class Backend_BuildAction extends OurBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		/**
		 * Hack hack
		 */
		$classes = Agavi::$autoloads;
		$records = array();

		$dir = AgaviConfig::get('core.model_dir');

		foreach ($classes as $class => $file)
		{
			if ((strpos($file, $dir) !== false) && (strpos($file, 'Model.class.php') !== false) )
			{
				$records[] = $class;
			}
		}

		$sql = array();
		foreach ($this->cn->export->exportClassesSql($records) as $line)
		{
			$match = preg_match('/CREATE TABLE ([^\s]+)/', $line, $bits);
			if ($match)
			{
				$sql[] = 'DROP TABLE IF EXISTS `' . $bits[1] . '`;';
			}

			$sql[] = $line . ';';
		}

		$sql = join("\n\n", $sql);

		file_put_contents(AgaviConfig::get('core.cache_dir') . '/export.sql', $sql);

		$this->setAttributeByRef('sql', $sql);

		return 'Success';
	}

}

?>