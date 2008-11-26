<?php

/**
 * AdtDebugFirePhpFilter renders AdtDebugFilter's log using
 * FirePHP
 *
 * @author     Veikko MÃ¤kinen <veikko@veikko.fi>
 * @author     Harald Kirschner <mail@digitarald.de>
 * @copyright  Authors
 * @version    $Id$
 */
class AdtDebugFirePhpFilter extends AdtDebugFilter implements AgaviIActionFilter
{

	public function render(AgaviExecutionContainer $container)
	{
		$firephp = AdtFirePhp::getInstance(true);
		$firephp->setContext($this->context);
		$firephp->setOptions(array(
			'includeLineNumbers' => false,
			'maxObjectDepth' => 1,
			'maxArrayDepth' => 1
		));
		//$firephp->detectClientExtension()

		$template = $this->log;

		$unshift_key = create_function('$value, $key', 'return array($key, $value);');

		$table = array(array('Name', 'Regexp', 'Matches'));
		foreach($template['routes'] as $routeName => $routeInfo) {
			$table[] = array($routeName, $routeInfo['opt']['reverseStr'], $routeInfo['matches']);
		}
		$firephp->table('Matched Routes', $table);

		if (isset($template['queries'])) {
			if (count($template['queries']['table'])) {
				$firephp->table(
					sprintf('Database Queries (%s - %.6f ms)', count($template['queries']['table']), $template['queries']['total']),
					array_merge(array(array('Time', 'Query', 'Params')), $template['queries']['table'])
				);
			} else {
				$firephp->log('No Database Queries');
			}
		}

		$firephp->group('Request Data');
		$map = $template['request_data']['request_parameters'];
		if (count($map)) {
			$firephp->table(sprintf('Request Parameters (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
		} else {
			$firephp->log('No Request Parameters');
		}
		$map = $template['request_data']['cookies'];
		if (count($map)) {
			$firephp->table(sprintf('Cookies (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
		} else {
			$firephp->log('No Cookies');
		}
		$map = $template['request_data']['headers'];
		if (count($map)) {
			$firephp->table(sprintf('Headers (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
		} else {
			$firephp->log('No Headers');
		}
		$firephp->groupEnd(); //req data


		$firephp->group('Actions');

		foreach($template['actions'] as $action) {
			$firephp->group($action['module'] .'.'.$action['name']);
			if ($action['validation']['has_errors']) {
				$firephp->error('Has Validation Errors');
			} else {
				$firephp->log('No Validation Errors');
			}

			$map = $action['validation']['incidents'];
			if (count($map)) {
				$table = array(array('Name', 'Severity', 'Fields'));
				foreach($action['validation']['incidents'] as $incident) { /* @var $incident AgaviValidationIncident */
					$table[] = array(
						$incident->getValidator() ? $incident->getValidator()->getName() : '(no validator)',
						$action['validation']['severities'][$incident->getSeverity()],
						implode(', ', $incident->getFields())
					);
				}
				$firephp->table(sprintf('Validation Incidents (%s)', count($map)), $table);
			} else {
				$firephp->log('No Validation Incidents');
			}

			$firephp->group('Request Data (from execution container)');

			if ($action['request_data']['request_parameters']) {
				$map = $action['request_data']['request_parameters'];
				$firephp->table(sprintf('Request Parameters (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
			} else {
				$firephp->log('No Request Parameters');
			}

			if ($action['request_data']['cookies']) {
				$map = $action['request_data']['cookies'];
				$firephp->table(sprintf('Cookies (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
			} else {
				$firephp->log('No Cookies');
			}

			if ($action['request_data']['headers']) {
				$map = $action['request_data']['headers'];
				$firephp->table(sprintf('Headers (%s)', count($map)), array_merge(array(array('Name', 'Value')), array_map($unshift_key, $map, array_keys($map))));
			} else {
				$firephp->log('No Headers');
			}

			$firephp->groupEnd(); //req data

			$firephp->groupEnd(); //action
		} // actions
		$firephp->groupEnd(); //actions

		if (count($template['log'])) {
			$log = array_map('array_slice', array_map('array_values', $template['log']), array(1));
			$firephp->table(sprintf('Debug Log (%s)', count($template['log'])), array_merge(array(array('Microtime', 'Message')), $log));
		}
	}

}
?>