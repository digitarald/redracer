<?php

interface AgaviIDebugSource
{
	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current AgaviContext instance.
	 */
	public function getContext();

	/**
	 * Initialize this Filter.
	 *
	 * @param      AgaviContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>AgaviInitializationException</b> If an error occurs while
	 *                                                 initializing this Filter.
	 */
	public function initialize(AgaviContext $context, array $parameters = array());

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function preExecuteOnce(AgaviExecutionContainer $container);

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function postExecuteOnce(AgaviExecutionContainer $container);

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function preExecute(AgaviExecutionContainer $container);

	/**
	 * @param      AgaviExecutionContainer The current execution container.
	 */
	public function postExecute(AgaviExecutionContainer $container);
}

?>