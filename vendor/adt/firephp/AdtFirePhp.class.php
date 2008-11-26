<?php

if (!class_exists('FirePHP')) {
	require('FirePHPCore/FirePHP.class.php');
}

/**
 *
 * AdtFirePhp extends FirePHP and overrides user-agent header
 * fetching.
 * 
 * @author     Michael Stolovitzsky
 */
class AdtFirePhp extends FirePHP
{
	protected $context; 

	public function setContext(AgaviContext $context)
	{
		$this->context = $context;
	}

	public function getUserAgent()
	{
		$rd = $this->context->getRequest()->getRequestData();
		return $rd->getHeader('USER_AGENT');
	}

	/**
	 * Gets singleton instance of FirePHP
	 *
	 * @param boolean $AutoCreate
	 * @return FirePHP
	 */
	public static function getInstance($AutoCreate=false) 
	{
		if($AutoCreate===true && !self::$instance) {
			self::init();
		}
		return self::$instance;
	}
	 
	/**
	 * Creates FirePHP object and stores it for singleton access
	 *
	 * @return FirePHP
	 */
	public static function init() 
	{
		return self::$instance = new self();
	}
	

}
?>