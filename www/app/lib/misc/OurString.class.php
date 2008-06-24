<?php

require(AgaviConfig::get('core.lib_dir') . '/markdown/markdown.php');
require(AgaviConfig::get('core.lib_dir') . '/markdown/smartypants.php');

class OurString
{

	public function camelCase(&$string)
	{
		return preg_replace_callback('/-\D/g', create_function('$matches','return String::upperCaseCharAt(matches[0], 1);'));
	}

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

	public function hyphenate(&$string)
	{
		return preg_replace_callback('/-\D/g', create_function('$matches','return ($matches[0][0] . \'-\' . String::lowerCaseCharAt($matches[0], 1);'));
	}


	public function format($string)
	{
		return SmartyPants(Markdown($string) );
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

	public function highlight($source, $type = 'javascript')
	{
		$geshi =& new GeSHi($source, $type);

		$geshi->set_encoding('utf-8');
		$geshi->enable_classes();

		return str_replace('&nbsp;', '&#160;', $geshi->parse_code() );
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