<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\helpers;

class Serialize {
	/**
	 * Serialize data into string
	 *
	 * @param string|int|float|bool|array|null $data
	 *
	 * @return string|bool
	 * @throws \InvalidArgumentException
	 */
	public static function serialize($data) {
		if (is_resource($data)) {
			throw new \InvalidArgumentException('Unable to serialize value.');
		}

		return serialize($data);
	}

	/**
	 * Unserialize the given string
	 *
	 * @param string $string
	 *
	 * @return string|int|float|bool|array|null
	 * @throws \InvalidArgumentException
	 */
	public static function unserialize($string) {
		if ($string === false || $string === null || $string === '') {
			throw new \InvalidArgumentException('Unable to unserialize value.');
		}
		set_error_handler(function () {
			restore_error_handler();
			throw new \InvalidArgumentException('Unable to unserialize value, string is corrupted.');
		}, E_NOTICE);

		$result = unserialize($string, ['allowed_classes' => false]);
		restore_error_handler();

		return $result;
	}
}