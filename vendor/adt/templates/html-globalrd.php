<h3>Request parameters</h3>
<dl>
<?php foreach( $template['request_data']['request_parameters'] as $parameter => $value ): ?>
	<dt><?php echo $parameter; ?></dt>
	<dd><pre><?php htmlspecialchars(var_dump($value)); ?></pre></dd>
<?php endforeach; ?>
</dl>

<h3>Cookies</h3>
<dl>
<?php foreach( $template['request_data']['cookies'] as $parameter => $value ): ?>
	<dt><?php echo $parameter; ?></dt>
	<dd><pre><?php htmlspecialchars(var_dump($value)); ?></pre></dd>
<?php endforeach; ?>
</dl>

<h3>Headers</h3>
<dl>
<?php foreach( $template['request_data']['headers'] as $parameter => $value ): ?>
	<dt><?php echo $parameter; ?></dt>
	<dd><?php echo htmlspecialchars($value); ?></dd>
<?php endforeach; ?>
</dl>