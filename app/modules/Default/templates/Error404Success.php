<h2>Error 404 - Page not found</h2>
<?php	if (AgaviConfig::get('core.debug') ): ?>
<h3>Parameters</h3>
<pre><?php var_dump($rq->getAttribute('matched_routes', null, 'org.agavi.routing') ); ?></pre>
<h3>Routes</h3>
<pre><?php var_dump($rd->getParameters() ); ?></pre>
<?php	endif; ?>