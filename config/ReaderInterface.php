<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\config;

/**
 * Config reader interface.
 *
 * @api
 */
interface ReaderInterface {
	/**
	 * Read configuration scope
	 *
	 * @param string|null $scope
	 *
	 * @return array
	 */
	public function read($scope = null): array;
}
