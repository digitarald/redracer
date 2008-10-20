<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2008 the Agavi Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * AgaviValidator allows you to validate input
 *
 * Parameters for use in most validators:
 *   'name'       name of validator
 *   'base'       base path for validation of arrays
 *   'arguments'  an array of input parameter keys to validate
 *   'export'     destination for exportet data
 *   'depends'    list of dependencies needed by the validator
 *   'provides'   list of dependencies the validator provides after success
 *   'severity'   error severity in case of failure
 *   'error'      error message when validation fails
 *   'errors'     an array of errors with the reason as key
 *   'affects'    list of fields that are affected by an error
 *   'required'   if true the validator will fail when the input parameter is 
 *                not set
 *
 * @package    agavi
 * @subpackage validator
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Uwe Mesecke <uwe@mesecke.net>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviValidator.class.php 3037 2008-10-15 20:17:25Z dominik $
 */
abstract class AgaviValidator extends AgaviParameterHolder
{
	/**
	 * validator field success flag
	 */
	const NOT_PROCESSED = -1;

	/**
	 * validator error severity (the validator succeeded)
	 */
	const SUCCESS = 0;

	/**
	 * validator error severity (validator failed but without impact on result
	 * of whole validation process, completely silent and does not remove the 
	 * "failed" parameters from the input parameters)
	 */
	const INFO = 100;
	
	/**
	 * validator error severity (validator failed but without impact on result
	 * of whole validation process and completely silent)
	 */
	const SILENT = 200;
	const NONE = AgaviValidator::SILENT;
	
	/**
	 * validator error severity (validator failed but without impact on result
	 * of whole validation process)
	 */
	const NOTICE = 300;

	/**
	 * validation error severity (validator failed but validation process
	 * continues)
	 */
	const ERROR = 400;

	/**
	 * validation error severty (validator failed and validation process will
	 * be aborted)
	 */
	const CRITICAL = 500;

	/**
	 * @var        AgaviContext An AgaviContext instance.
	 */
	protected $context = null;

	/**
	 * @var        AgaviIValidatorContainer parent validator container (in
	 *                                      most cases the validator manager)
	 */
	protected $parentContainer = null;

	/**
	 * @var        AgaviVirtualArrayPath The current base for input names, 
	 *                                   dependencies etc.
	 */
	protected $curBase = null;

	/**
	 * @var        string The name of this validator instance. This will either
	 *                    be the user supplied name (if any) or a random string
	 */
	protected $name = null;

	/**
	 * @var        AgaviRequestDataHolder The parameters which should be validated
	 *                                  in the current validation run.
	 */
	protected $validationParameters = null;

	/**
	 * @var        array The name of the request parameters serving as argument to
	 *                   this validator.
	 */
	protected $arguments = array();

	/**
	 * @var        array The error messages.
	 */
	protected $errorMessages = array();

	/**
	 * @var        AgaviValidationIncident The current incident.
	 */
	protected $incident = null;
	
	/**
	 * @var        array The affected arguments of this validation run.
	 */
	protected $affectedArguments = array();

	/**
	 * Returns the base path of this validator.
	 *
	 * @return     AgaviVirtualArrayPath The basepath of this validator
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getBase()
	{
		return $this->curBase;
	}

	/**
	 * Returns the "keys" in the path of the base
	 *
	 * @return     array The keys from left to right
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getBaseKeys()
	{
		$keys = array();
		$l = $this->curBase->length();
		for($i = 1; $i < $l; ++$i) {
			$keys[] = $this->curBase->get($i);
		}

		return $keys;
	}

	/**
	 * Returns the last "keys" in the path of the base
	 *
	 * @return     mixed The key
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getLastKey()
	{
		$base = $this->curBase;
		if($base->length() == 0 || ($base->length() == 1 && $base->isAbsolute()))
			return null;

		return $base->get($base->length() - 1);
	}

	/**
	 * Returns the name of this validator.
	 *
	 * @return     string The name
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Initialize this validator.
	 *
	 * @param      AgaviContext The Context.
	 * @param      array        An array of validator parameters.
	 * @param      array        An array of argument names which should be validated.
	 * @param      array        An array of error messages.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(AgaviContext $context, array $parameters = array(), array $arguments = array(), array $errors = array())
	{
		$this->context = $context;

		$this->arguments = $arguments;
		$this->errorMessages = $errors;

		if(!isset($parameters['depends']) || !is_array($parameters['depends'])) {
			$parameters['depends'] = (!empty($parameters['depends'])) ? explode(' ', $parameters['depends']) : array();
		}
		if(!isset($parameters['provides']) || !is_array($parameters['provides'])) {
			$parameters['provides'] = (!empty($parameters['provides'])) ? explode(' ', $parameters['provides']) : array();
		}

		if(!isset($parameters['source'])) {
			$parameters['source'] = AgaviRequestDataHolder::SOURCE_PARAMETERS;
		}

		$this->setParameters($parameters);

		$this->name = $this->getParameter('name', AgaviToolkit::uniqid());
	}

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current Context instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Retrieve the parent container.
	 *
	 * @return     AgaviIValidatorContainer The parent container.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getParentContainer()
	{
		return $this->parentContainer;
	}

	/**
	 * Sets the parent container.
	 *
	 * @param      AgaviIValidatorContainer The parent container.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setParentContainer(AgaviIValidatorContainer $parent)
	{
		// we need a reference here, so when looping happens in a parent
		// we always have the right base
		$this->curBase = $parent->getBase();
		$this->parentContainer = $parent;
	}

	/**
	 * Validates the input.
	 *
	 * This is the method where all the validation stuff is going to happen.
	 * Inherited classes have to implement their validation logic here. It
	 * returns only true or false as validation results. The handling of
	 * error severities is done by the validator itself and should not concern
	 * the writer of a new validator.
	 *
	 * @return     bool The result of the validation.
	 *
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected abstract function validate();

	/**
	 * Shuts the validator down.
	 *
	 * This method can be used in validators to shut down used models or
	 * other activities before the validator is killed.
	 *
	 * @see        AgaviValidationManager::shutdown()
	 *
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	public function shutdown()
	{
	}

	/**
	 * Returns the specified input value.
	 *
	 * The given parameter is fetched from the request. You should _always_
	 * use this method to fetch data from the request because it pays attention
	 * to specified paths.
	 *
	 * @param      string The name of the parameter to fetch from request.
	 *
	 * @return     mixed The input value from the validation input.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function &getData($paramName)
	{
		$paramType = $this->getParameter('source');
		$array =& $this->validationParameters->getAll($paramType);
		return $this->curBase->getValueByChildPath($paramName, $array);
	}

	/**
	 * Returns true if this validator has multiple arguments which need to be 
	 * validated.
	 *
	 * @return     bool Whether this validator has multiple arguments or not.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function hasMultipleArguments()
	{
		return count($this->arguments) > 1;
	}

	/**
	 * Returns the first argument which should be validated.
	 *
	 * This method is to be used by validators which only expect 1 input
	 * argument.
	 *
	 * @return     string The input argument name.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getArgument()
	{
		$argNames = $this->arguments;
		reset($argNames);
		return current($argNames);
	}

	/**
	 * Returns all arguments which should be validated.
	 *
	 * @return     array A list of input arguments names.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getArguments()
	{
		return $this->arguments;
	}
	
	/**
	 * Sets the arguments which should be flagged with the result of the 
	 * validator
	 * 
	 * @param      array A list of (absolute) argument names
	 * 
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function setAffectedArguments($arguments)
	{
		$this->affectedArguments = $arguments;
	}

	/**
	 * Returns whether all arguments are set in the validation input parameters.
	 * Set means anything but empty string.
	 *
	 * @param      bool Whether an error should be thrown for each missing 
	 *                  argument if this validator is required.
	 *
	 * @return     bool Whether the arguments are set.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function checkAllArgumentsSet($throwError = true)
	{
		$isRequired = $this->getParameter('required', true);
		$paramType = $this->getParameter('source');
		$result = true;

		$baseParts = $this->curBase->getParts();
		foreach($this->getArguments() as $argument) {
			$new = $this->curBase->pushRetNew($argument);
			$pName = $this->curBase->pushRetNew($argument)->__toString();
			if($this->validationParameters->isValueEmpty($paramType, $pName)) {
				if($throwError && $isRequired) {
					$this->throwError(null, $pName);
				}
				$result = false;
			}
		}
		return $result;
	}

	/**
	 * Retrieves the error message for the given index with fallback. 
	 *
	 * If the given index does not exist in the error messages array, it first 
	 * checks if an unnamed error message exists and returns it or falls back the
	 * the backup message.
	 *
	 * @param      string The name of the error.
	 * @param      string The backup error message.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getErrorMessage($index = null, $backupMessage = null)
	{
		if($index !== null && isset($this->errorMessages[$index])) {
			$error = $this->errorMessages[$index];
		} elseif(isset($this->errorMessages[''])) {
			// check if a default error exists.
			$error = $this->errorMessages[''];
		} else {
			$error = $backupMessage;
		}

		return $error;
	}

	/**
	 * Submits an error to the error manager.
	 *
	 * Will look up the index in the errors array with automatic fallback to the
	 * default error. You can optionally specify the fields affected by this 
	 * error. The error will be appended to the current incident.
	 *
	 * @param      string The name of the error parameter to fetch the message 
	 *                    from.
	 * @param      string|array The arguments which are affected by this error.
	 *                          If null is given it will affect all fields.
	 * @param      boolean Whether the argument names in $affectedArgument are
	 *                     relative or absolute.
	 * @param      boolean Whether to set the affected fields of the validator
	 *                     to the $affectedArguments
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function throwError($index = null, $affectedArgument = null, $argumentsRelative = false, $setAffected = false)
	{
		if($affectedArgument === null) {
			$affectedArguments = $this->getFullArgumentNames();
		} else {
			$affectedArguments = (array) $affectedArgument;
			if($argumentsRelative) {
				foreach($affectedArguments as &$arg) {
					$arg = $this->curBase->pushRetNew($arg)->__toString();
				}
			}
		}
		
		if($setAffected) {
			$this->affectedArguments = $affectedArguments;
		}

		$error = $this->getErrorMessage($index);

		if($this->hasParameter('translation_domain')) {
			$error = $this->getContext()->getTranslationManager()->_($error, $this->getParameter('translation_domain'));
		}

		if(!$this->incident) {
			$this->incident = new AgaviValidationIncident($this, self::mapErrorCode($this->getParameter('severity', 'error')));
		}

		foreach($affectedArguments as &$argument) {
			$argument = new AgaviValidationArgument($argument, $this->getParameter('source'));
		}
		
		if($error !== null || count($affectedArguments) != 0) {
			// don't throw empty error messages without affected fields
			$this->incident->addError(new AgaviValidationError($error, $index, $affectedArguments));
		}
	}

	/**
	 * Exports a value back into the request.
	 *
	 * Exports data into the request at the index given in the parameter
	 * 'export'. If there is no such parameter, then the method returns
	 * without exporting.
	 *
	 * Similar to getData() you should always use export() to submit data to
	 * the request because it pays attention to paths and otherwise you could
	 * overwrite stuff you don't want to.
	 *
	 * @param      mixed The value to be exported.
	 * @param      string An optional name which should be used for exporting 
	 *                    instead of the export parameter.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function export($value, $name = null)
	{
		if($name === null) {
			$name = $this->getParameter('export');
		}

		if(!$name) {
			return;
		}

		$paramType = $this->getParameter('source');

		$array =& $this->validationParameters->getAll($paramType);
		$cp = $this->curBase->pushRetNew($name);
		$cp->setValue($array, $value);
		if($this->parentContainer !== null) {
			// make sure the parameter doesn't get removed by the validation manager
			if(is_array($value)) {
				// for arrays all child elements need to be marked as not processed
				foreach(AgaviArrayPathDefinition::getFlatKeyNames($value) as $keyName) {
					$this->parentContainer->addArgumentResult(new AgaviValidationArgument($cp->pushRetNew($keyName)->__toString(), $this->getParameter('source')), AgaviValidator::NOT_PROCESSED, $this);
				}
			}
			$this->parentContainer->addArgumentResult(new AgaviValidationArgument($cp->__toString(), $this->getParameter('source')), AgaviValidator::NOT_PROCESSED, $this);
		}
	}

	/**
	 * Validates this validator in the given base.
	 *
	 * @param      AgaviVirtualArrayPath The base in which the input should be 
	 *                                   validated.
	 *
	 * @return     int AgaviValidator::SUCCESS if validation succeeded or given
	 *                 error severity.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validateInBase(AgaviVirtualArrayPath $base)
	{
		$base = clone $base;
		if($base->length() == 0) {
			// we have an empty base so we do the actual validation
			if($this->getDependencyManager() && (count($this->getParameter('depends')) > 0 && !$this->getDependencyManager()->checkDependencies($this->getParameter('depends'), $this->curBase))) {
				// dependencies not met, exit with success
				return self::SUCCESS;
			}

			$this->affectedArguments = $this->getFullArgumentNames();

			$result = self::SUCCESS;
			$errorCode = self::mapErrorCode($this->getParameter('severity', 'error'));

			if($this->checkAllArgumentsSet(false)) {
				if(!$this->validate()) {
					// validation failed, exit with configured error code
					$result = $errorCode;
				}
			} else {
				if($this->getParameter('required', true)) {
					$this->throwError();
					$result = $errorCode;
				} else {
					// we don't throw an error here because this is not an incident per se
					// but rather a non validated field
					$result = self::NOT_PROCESSED;
				}
			}

			if($this->parentContainer !== null) {
				if($result != self::NOT_PROCESSED) {
					foreach($this->affectedArguments as $fieldname) {
						$this->parentContainer->addArgumentResult(new AgaviValidationArgument($fieldname, $this->getParameter('source')), $result, $this);
					}
				}

				if($this->incident) {
					$this->parentContainer->addIncident($this->incident);
				}
			}

			$this->incident = null;
			// put dependencies provided by this validator into manager
			if($this->getDependencyManager() && ($result == self::SUCCESS && count($this->getParameter('provides')) > 0)) {
				$this->getDependencyManager()->addDependTokens($this->getParameter('provides'), $this->curBase);
			}
			return $result;

		} elseif($base->left() !== '') {
			/*
			 * the next component in the base is no wildcard so we
			 * just put it into our own base and validate further
			 * into the base.
			 */

			$this->curBase->push($base->shift());
			$ret = $this->validateInBase($base);
			$this->curBase->pop();

			return $ret;

		} else {
			/*
			 * now we have a wildcard as next component so we collect
			 * all defined value names in the request at the path
			 * specified by our own base and validate in each of that
			 * names
			 */
			$names = $this->getKeysInCurrentBase();

			// if the names array is empty this means we need to throw an error since
			// this means the input doesn't exist
			if(count($names) == 0) {
				if($this->getParameter('required', true)) {
					$this->throwError();
					return self::mapErrorCode($this->getParameter('severity', 'error'));
				} else {
					return self::NOT_PROCESSED;
				}
			}

			// throw the wildcard away
			$base->shift();

			$ret = self::SUCCESS;

			// validate in every name defined in the request
			foreach($names as $name) {
				$newBase = clone $base;
				$newBase->unshift($name);
				$t = $this->validateInBase($newBase);

				if($t == self::CRITICAL) {
					return $t;
				}

				// remember the highest error severity
				$ret = max($ret, $t);
			}

			return $ret;
		}
	}

	/**
	 * Executes the validator.
	 *
	 * @param      AgaviRequestDataHolder The data which should be validated.
	 *
	 * @return     int The validation result (see severity constants).
	 *
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	public function execute(AgaviRequestDataHolder $parameters)
	{
		if($this->getParameter('source') != AgaviRequestDataHolder::SOURCE_PARAMETERS && !in_array($this->getParameter('source'), $parameters->getSourceNames())) {
			throw new AgaviConfigurationException('Unknown source "' . $this->getParameter('source') . '" specified in validator ' . $this->getName());
		}

		$this->validationParameters = $parameters;
		$base = new AgaviVirtualArrayPath($this->getParameter('base'));

		$res = $this->validateInBase($base);
		if($this->incident && $this->parentContainer) {
			$this->parentContainer->addIncident($this->incident);
			$this->incident = null;
		}
		return $res;
	}

	/**
	 * Converts string severity codes into integer values
	 * (see severity constants)
	 *
	 * critical -> AgaviValidator::CRITICAL
	 * error    -> AgaviValidator::ERROR
	 * notice   -> AgaviValidator::NOTICE
	 * none     -> AgaviValidator::NONE
	 * success  -> not allowed to be specified by the user.
	 *
	 * @param      string The error severity as string.
	 *
	 * @return     int The error severity as in (see severity constants).
	 *
	 * @throws     <b>AgaviValidatorException</b> if the input was no known 
	 *                                           severity
	 *
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	public static function mapErrorCode($code)
	{
		switch(strtolower($code)) {
			case 'critical':
				return self::CRITICAL;
			case 'error':
				return self::ERROR;
			case 'notice':
				return self::NOTICE;
			case 'none':
			case 'silent':
				return self::SILENT;
			case 'info':
				return self::INFO;
			default:
				throw new AgaviValidatorException('unknown error code: '.$code);
		}
	}

	/**
	 * Returns all available keys in the currently set base.
	 *
	 * @return     array The available keys.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getKeysInCurrentBase()
	{
		$paramType = $this->getParameter('source');

		$array = $this->validationParameters->getAll($paramType);
		$names = $this->curBase->getValue($array, array());

		return array_keys($names);
	}

	/**
	 * Returns all arguments with their full path.
	 *
	 * @return     array The arguments.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function getFullArgumentNames()
	{
		$arguments = array();
		foreach($this->getArguments() as $argument) {
			if($argument) {
				$arguments[] = $this->curBase->pushRetNew($argument)->__toString();
			} else {
				$arguments[] = $this->curBase->__toString();
			}
		}

		return $arguments;
	}

	/**
	 * Returns the depency manager of the parent container if any.
	 * 
	 * @return     AgaviDependencyManager The parent's dependency manager.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getDependencyManager()
	{
		if($this->parentContainer instanceof AgaviIValidatorContainer) {
			return $this->parentContainer->getDependencyManager();
		}
		return null;
	}
}

?>