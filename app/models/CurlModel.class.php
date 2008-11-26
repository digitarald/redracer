<?php

class CurlModel implements AgaviISingletonModel
{
	/**
	 * @var        AgaviContext
	 */
	protected static $context = null;

	public static $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';

	/**
	 * getContext
	 *
	 * @return     AgaviContext
	 */
	public static function initialize(AgaviContext $context, array $parameters = array())
	{
		self::$context = $context;
	}

	/**
	 * getContext
	 *
	 * @return     AgaviContext
	 */
	public function getContext()
	{
		return self::$context;
	}

	public static function fetchUrl($url, array $params = array(), $post = false)
	{
		if (($handle = curl_init()) == false) {
			throw new Exception("curl_init error.");
		}

		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle, CURLOPT_MAXREDIRS, 2);
		curl_setopt($handle, CURLOPT_TIMEOUT, 5);

		if ($post) {
			curl_setopt($handle, CURLOPT_POST, true);

			if ($params) {
				curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($params, null, '&'));
			}
		} else {
			curl_setopt($handle, CURLOPT_HEADER, false);

			$url = self::buildUrl($url, $params);
		}

		curl_setopt($handle, CURLOPT_USERAGENT, self::$user_agent);
		curl_setopt($handle, CURLOPT_URL, $url);

		$content = curl_exec($handle);
		if ($content === false) {
			throw new Exception("curl_exec error for url $url.");
		}

		curl_close($handle);

		return $content;
	}

	public static function buildUrl($url, array $params = array())
	{
		$params = http_build_query($params, null, '&');

		if ($params) {
			if (strpos($url, '?') === false) {
				$url .= '?' . $params;
			} else {
				$url .= '&' . $params;
			}
		}

		return $url;
	}

	public static function fetchJsonFromUrl($url, array $params = array(), $post = false)
	{
		$content = self::fetchUrl($url, $params, $post);

		$ret = json_decode($content, true);
		if ($ret === null) {
			throw new Exception("fetchJsonFromUrl: json_decode failed on the content.");
		}

		return $ret;
	}

	public static function fetchLinksFromUrl($url, array $params = array(), $post = false)
	{
		$content = self::fetchUrl($url, $params, $post);

		$links = array();
		if (preg_match_all('/<a\s+href="([^\.][^"]*)"\s*>\s*(.+?)\s*</', $content, $m, PREG_SET_ORDER)) {
			foreach ($m as $bit) {
				if ($bit[1] == $bit[2]) {
					$links[] = $bit[1];
				}
			}
		}

		return $links;
	}

	public static function fetchDirectories($url, array $params = array(), $post = false)
	{
		$directories = array();

		$links = self::fetchLinksFromUrl($url, $params, $post);

		if (!self::isDirectory($url)) {
			$url .= '/';
		}

		foreach ($links as $link) {
			$attach = false;
			if (self::isDirectory($link)) {
				$attach = self::fetchDirectories($url . $link, $params, $post);
				if (!is_array($attach) || !count($attach)) {
					$attach = null;
				}
			}
			if ($attach === false || is_array($attach)) {
				$directories[$link] = $attach;
			}
		}
		return $directories;
	}

	protected function isDirectory($name) {
		return (strrpos($name, '/') + 1 == strlen($name));
	}

}

?>