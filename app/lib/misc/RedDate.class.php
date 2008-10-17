<?php

/**
 * RedDate
 *
 * @package    our
 *
 * @copyright  Harald Kirschner <mail@digitarald.de>
 */
class RedDate
{

	public function prettyDate($past, $default = 'F jS, Y')
	{
		if (!is_object($past) ) {
			$past = new DateTime($past);
		}

		$diff = time() - $past->format('U');
		$diff_day = floor($diff / 86400);

		switch ($diff_day)
		{
			case 0:
				if ($diff < 60) return 'just now';
				if ($diff < 120) return '1 minute ago';
				if ($diff < 3600) return round($diff / 60) . ' minutes ago';
				if ($diff < 7200) return '1 hour ago';
				return round($diff / 3600) . ' hours ago';
			case 1:
				return 'Yesterday';
		}

		if ($diff_day < 7) return $diff_day . ' days ago';
		if ($diff_day < 31) return round($diff_day / 7) . ' weeks ago';
		if ($diff_day < 360) return round($diff_day / 31) . ' months ago';

		return $past->format($default);
	}

}

?>