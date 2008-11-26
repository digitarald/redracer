<?php foreach($template['routes'] as $routeName => $routeInfo): ?>
<h3><?php echo $routeName; ?></h3>
<div>
	<dl>
		<dt>Module:</dt>
			<dd><?php echo !empty($routeInfo['opt']['module']) ? $routeInfo['opt']['module'] : '&#160;'; ?></dd>
		<dt>Action:</dt>
			<dd><?php echo !empty($routeInfo['opt']['action']) ? $routeInfo['opt']['action'] : '&#160;'; ?></dd>
		<dt>Stop:</dt>
			<dd><?php echo $routeInfo['opt']['stop']==true ? 'True' : 'False'; ?></dd>
		<dt>Parameters:</dt>
		<dd>
			<?php if(count($routeInfo['par'])): ?>
			<ul>
			<?php foreach( $routeInfo['par'] as $oneParameter ): ?>
				<li><strong style="text-decoration: underline;"><?php echo $oneParameter ?></strong><br />
				Default:
				<?php if ( isset($routeInfo['opt']['defaults'][$oneParameter]) ): ?>
				<ul>
					<li>Pre: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['pre']; ?></li>
					<li>Value: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['val']; ?></li>
					<li>Post: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['post']; ?></li>
				</ul>
				<?php else: ?>
				No default
				<?php endif; ?>
				<br />
				Required: <?php echo isset($routeInfo['opt']['optional_parameters'][$oneParameter]) ? 'False' : 'True'; ?>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php else: ?>
			No Parameters
			<?php endif; ?>
		</dd>

		<?php
			$otTimeName = time()+rand();
			$otName = empty($routeInfo['opt']['output_type']) ? $this->getContext()->getController()->getOutputType()->getName() : $routeInfo['opt']['output_type'];
			$outputType = $this->getContext()->getController()->getOutputType($otName);
		?>
		<dt>Output type: <a id="adtMatchedRouteOutputTypeShow_<?php echo $otTimeName; ?>" href="#"><?php echo $otName;	?></a></dt>
		<dd>
		<div id="adtMatchedRouteOutputTypeInfo_<?php echo $otTimeName; ?>" >
			Has renderers:	<?php echo $outputType->hasRenderers()==true ? 'True' : 'False'; ?><br />
			Default layout name: <?php echo $outputType->getDefaultLayoutName(); ?>
		</div>
		<?php
		if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $routeInfo['opt']['output_type']) == 0 ) {
			echo ' ( default ) ';
		}
		?>
		</dd>

		<dt>Ignores:</dt>
		<dd>
		<?php if ( count( $routeInfo['opt']['ignores'] ) != 0 ): ?>
		<ul>
		<?php foreach( $routeInfo['opt']['ignores'] as $ignore ): ?>
			<li><?php echo $ignore; ?></li>
		<?php endforeach; ?>
		</ul>
		<?php else: ?>
			No ignores
		<?php endif; ?>
		</dd>

		<dt>Children:</dt>
		<dd>
		<?php if (count($routeInfo['opt']['childs']) != null ): ?>
			<ul>
			<?php foreach( $routeInfo['opt']['childs'] as $children ): ?>
				<li><?php echo $children; ?></li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			No children
		<?php endif; ?>
		</dd>

		<dt>Callback:</dt>
		<dd>
		<?php echo !empty( $routeInfo['opt']['callback'] )?$routeInfo['opt']['callback']:' No callback '; ?>
		</dd>

		<dt>Imply:</dt>
		<dd>
		<?php echo $routeInfo['opt']['imply']==true?'True':'False'; ?>
		</dd>

		<dt>Cut:</dt>
		<dd>
		<?php echo $routeInfo['opt']['cut']==true?'True':'False'; ?>
		</dd>

		<dt>Pattern:</dt>
		<dd>
		<?php echo htmlspecialchars($routeInfo['rxp']); ?>
		</dd>
	</dl>
</div><!-- route -->
<?php endforeach; //routes ?>