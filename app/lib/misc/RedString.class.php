<?php

/**
 * @todo Move that to autoload?
 */
require(AgaviConfig::get('core.vendor_dir') . '/markdown/smartypants.php');

require(AgaviConfig::get('core.vendor_dir') . '/geshi/geshi.php');

/**
 * RedString
 *
 * @package    our
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

	public static function strip(&$string)
	{
		$string = strtolower($string);

		$string = html_entity_decode($string);
		$string = preg_replace('/&#([0-9]+);/', '', $string);
		$string = preg_replace('/\W/', ' ', $string);
		$string = preg_replace('/\ +/', '-', $string);
		$string = preg_replace('/^-|-$/', '', $string);

		return $string;
	}

	public function format($string, $indent_headers = null)
	{
		if (self::$markdown === null) {
			self::$markdown = new RedMarkdown();
			self::$markdown->setCodeCallback(array('RedString', 'formatHighlight') );

			self::$geshi = new GeSHi();
			self::$geshi->set_encoding('utf-8');
			self::$geshi->enable_keyword_links();
			self::$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
			self::$geshi->enable_ids();;

			self::$geshi->enable_classes();
		}

		return self::$markdown->transform($string, $indent_headers);
	}

	/**
	 * URL normalization
	 *
	 * @param      URL
	 */
	public function normalizeURL($url, $strict = false)
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


	static function formatHighlight($source, $language)
	{
		self::$geshi->set_source($source);
		self::$geshi->set_language($language);

		return self::$geshi->parse_code();
	}

	public function simpleInflect($singular, $amount)
	{
		switch ((int) $amount) {
			case 0;
				return 'No ' . $singular . 's';
			case 1;
				return '1 ' . $singular;
		}
		return $amount . ' ' . $singular . 's';
	}

	public function isUtf8($string)
	{
		return (utf8_encode(utf8_decode($string)) == $string);
	}

	public function truncate($string, $length = 25, $end = '...')
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