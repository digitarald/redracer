<?php

require(AgaviConfig::get('core.lib_dir') . '/vendor/smartypants.php');

require(AgaviConfig::get('core.lib_dir') . '/vendor/geshi.php');

class OurString
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
		if (self::$markdown === null)
		{
			self::$markdown = new OurMarkdown();
			self::$markdown->setCodeCallback(array('OurString', 'formatHighlight') );

			self::$geshi = new GeSHi();
			self::$geshi->set_encoding('utf-8');
			self::$geshi->enable_classes();
		}

		return self::$markdown->transform($string, $indent_headers);
	}

	static function formatHighlight($source, $language)
	{
		self::$geshi->set_source($source);
		self::$geshi->set_language($language);

		return self::$geshi->parse_code();
	}

	public function simpleInflect($singular, $amount)
	{
		switch ((int) $amount)
		{
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

		if (strlen($string) <= $length)
		{
			return $string;
		}

		$string = substr($string, 0, $length);
		$string = substr($string, 0, strrpos($string, ' ') );

		return $string . $end;
	}

}

?>