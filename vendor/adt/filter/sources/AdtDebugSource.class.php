<?php

abstract class AgaviDebugSource extends AgaviParameterHolder implements AgaviIDebugSource
{
	/**
	 * @var        AgaviContext An AgaviContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current Context instance.
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Initialize this DebugSource.
	 *
	 * @param      AgaviContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>AgaviInitializationException</b> If an error occurs while
	 *                                                 initializing this DebugSource.
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		$this->context = $context;

		$this->setParameters($parameters);
	}

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function preExecuteOnce(AgaviExecutionContainer $container) {}

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function postExecuteOnce(AgaviExecutionContainer $container) {}

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function preExecute(AgaviExecutionContainer $container) {}

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function postExecute(AgaviExecutionContainer $container) {}
}

?>