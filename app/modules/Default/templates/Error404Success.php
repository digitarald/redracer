<h3 class="red">Error 404 - Not Found</h2>

<?php	if (AgaviConfig::get('core.debug') ): ?>
<h3 class="green">Routes</h3>
<pre><?php var_dump($rq->getAttribute('matched_routes', 'org.agavi.routing') ); ?></pre>
<h3 class="green">Parameters</h3>
<pre><?php var_dump($rd->getParameters() ); ?></pre>
<?php	endif; ?>