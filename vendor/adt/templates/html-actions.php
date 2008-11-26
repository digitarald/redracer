<?php foreach($template['actions'] as $action): ?>
<h3><?php echo $action['module']; ?>.<?php echo $action['name']; ?></h3>
<div>
	View name: <?php echo $action['view']['view_name']; ?>
	<br />
	Output type: <a href="#"><?php echo $action['view']['output_type']; ?></a>
	<?php if ( strcmp($action['view']['output_type'], $action['view']['default_output_type']) == 0 ): ?>
	( default )
	<?php endif; ?>
	<br />
	Has renderers: <?php echo $action['view']['has_renders']?'True':'False'; ?>
	<br />
	Default layout name: <?php echo $action['view']['default_layout_name']; ?>
	
	<h4>Validation</h4>
	<div>
		<p>
		Has validation errors: <?php var_export($action['validation']['has_errors']); ?>
		</p>
		<?php if(count($action['validation']['incidents'])): ?>
			<h5>Validation Incidents</h5>
			<?php foreach($action['validation']['incidents'] as $incident): /* @var $incident AgaviValidationIncident */ ?>
			<dl>
				<dt>Validator:</dt>
				<dd><?php echo $incident->getValidator()->getName() ?></dd>
				<dt>Severity:</dt>
				<dd><?php echo $action['validation']['severities'][$incident->getSeverity()] ?></dd>							
				<dt>Field(s):</dt>
				<dd><?php echo implode(', ', $incident->getFields()); ?></dd>
			</dl>							
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<h4>Request Data (from execution container)</h4>
	<div>
		<h5>Request parameters</h5>
		<?php if ($action['request_data']['request_parameters']): ?>
		<dl>
		<?php foreach( $action['request_data']['request_parameters'] as $parameter => $value ): ?>
			<dt><?php echo $parameter; ?></dt>
			<dd><pre><?php htmlspecialchars(var_dump($value)); ?></pre></dd>
		<?php endforeach; ?>
		</dl>
		<?php else: ?>
			<p>-</p>
		<?php endif; ?>

		<h5>Cookies</h5>
		<?php if ($action['request_data']['cookies']): ?>
		<dl>
		<?php foreach( $action['request_data']['cookies'] as $parameter => $value ): ?>
			<dt><?php echo $parameter; ?></dt>
			<dd><pre><?php htmlspecialchars(var_dump($value)); ?></pre></dd>
		<?php endforeach; ?>
		</dl>
		<?php else: ?>
			<p>-</p>
		<?php endif; ?>

		<h5>Headers</h5>
		<?php if ($action['request_data']['headers']): ?>
		<dl>
		<?php foreach( $action['request_data']['headers'] as $parameter => $value ): ?>
			<dt><?php echo $parameter; ?></dt>
			<dd><?php echo htmlspecialchars($value); ?></dd>
		<?php endforeach; ?>
		</dl>
		<?php else: ?>
			<p>-</p>
		<?php endif; ?>
	</div>
	
</div>
<?php endforeach; ?>