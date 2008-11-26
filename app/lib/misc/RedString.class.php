<?php

/**
 * RedString
 *
 * @package    redracer
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedString
{

	/**
	 * @var		MarkdownExtra_Parser
	 */
	static $markdown = null;

	/**
	 * @var		GeSHi
	 */
	static $geshi = null;

	public static function format($string, $indent_headers = null)
	{
		if (self::$markdown === null) {
			self::$markdown = new RedMarkdown();
			self::$markdown->setCodeCallback(array('RedString', 'formatHighlight') );

			self::$geshi = new GeSHi();
			self::$geshi->set_encoding('utf-8');
			self::$geshi->enable_keyword_links();
			self::$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
			self::$geshi->enable_ids();

			self::$geshi->enable_classes();
		}

		$ret = self::$markdown->transform($string, $indent_headers);
		$ret = strip_tags($ret, '<a><abbr><acronym><b><blockquote><cite><code><del><dd><dl><dt><em><h1><h2><h3><h4><h5><h6><i><kbd><li><ol><p><pre><s><sup><sub><strong><strike><ul><br/><hr/><img>');

		return $ret;
	}

	public static function detectLinks($string, $truncate = 50, $truncate_pad = '…')
	{
	  $callback = '
			if (preg_match("/<a\s/i", $match[1])) {
				return $match[0];
			}
			$text = $match[2].$match[3];';

	  if ($truncate != null) {
			$callback .= '
			  if (strlen($text) > '.$truncate.') {
					$text = substr(text, 0, '.$truncate.').\''.$truncate_pad.'\';
			  }';
		}

		$callback .= '
			return $match[1].\'<a href="\'.($match[2] == "www." ? "http://www." : $match[2]).$match[3].\'" title="\'.$match[2].$match[3].\'">\'.$text.\'</a>\'.$match[4];';

		return preg_replace_callback('~
		(                       # leading text
		  <\w+.*?>|             #   leading HTML tag, or
		  [^=!:\'"/]|           #   leading punctuation, or
		  ^                     #   beginning of line
		)
		(
		  (?:https?://)|        # protocol spec, or
		  (?:www\.)             # www.*
		)
		(
		  [-\w]+                   # subdomain or domain
		  (?:\.[-\w]+)*            # remaining subdomains or domain
		  (?::\d+)?                # port
		  (?:/(?:(?:[\~\w\+%-]|(?:[,.;:][^\s$]))+)?)* # path
		  (?:\?[\w\+%&=.;-]+)?     # query string
		  (?:\#[\w\-]*)?           # trailing anchor
		)
		([[:punct:]]|\s|<|$)    # trailing text
	   ~x', create_function('$match', $callback), $string);
	}

	/**
	 * URL normalization
	 *
	 * @param      URL
	 */
	public static function normalizeURL($url, $strict = false)
	{
		$bits = parse_url(trim($url) );

		if (!isset($bits['scheme']) || !strlen($bits['scheme'])) {
			$bits['scheme'] = 'http';
		} else {
			$bits['scheme'] = strtolower($bits['scheme']);
		}

		$norm = $bits['scheme'] . '://' . strtolower($bits['host']);

		if (isset($bits['port']) && strlen($bits['port']) && AgaviToolkit::isPortNecessary($bits['scheme'], (int) $bits['port'])) {
			$norm .= ':' . $bits['port'];
		}

		if (!isset($bits['path']) || !strlen($bits['path'])) {
			$norm .= '/';
		} else {
			$norm .= $bits['path'];
		}

		if (isset($bits['query'])) {
			// If there is a query string, then use identity as is
			$norm .= '?' . $bits['query'];
		}

		return $norm;
	}

	public static function strip($text)
	{
		return Doctrine_Inflector::urlize($text);
	}

	public static function formatHighlight($source, $language)
	{
		self::$geshi->set_source($source);
		self::$geshi->set_language($language);

		return self::$geshi->parse_code();
	}

	public static function simpleInflect($singular, $amount)
	{
		switch ((int) $amount) {
			case 0;
				return 'No ' . $singular . 's';
			case 1;
				return '1 ' . $singular;
		}
		return $amount . ' ' . $singular . 's';
	}

	public static function isUtf8($string)
	{
		return (utf8_encode(utf8_decode($string)) == $string);
	}

	public static function truncate($string, $length = 25, $end = '...')
	{
		$string = trim($string);

		if (strlen($string) <= $length) {
			return $string;
		}

		$string = substr($string, 0, $length);
		$string = substr($string, 0, strrpos($string, ' ') );

		return $string . $end;
	}

}

?>