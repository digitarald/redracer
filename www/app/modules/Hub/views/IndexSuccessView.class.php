<?php

class Hub_IndexSuccessView extends OurBaseView
{

	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Browse');
	}

	public function executeRss(AgaviRequestDataHolder $rd)
	{
		$items = array(array(
			'title'			=> 'Resource Title',
			'description'	=> 'Resource Description',
			'link'			=> $this->rt->getBasePath(),
			'date'			=> date('r')
		) );

		$rss = $this->generateRSS(array(
			'title'			=> 'our.mootools.net resources',
			'description'	=> 'resources'
		), $items);

		$this->rs->setContent($rss);
	}

}

?>