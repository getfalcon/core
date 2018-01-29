<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\config;

/**
 * File resolver interface.
 *
 * @api
 */
interface FileResolverInterface {
	/**
	 * Retrieve the list of configuration files with given name that relate to specified scope
	 *
	 * @param string $filename
	 * @param string $scope
	 *
	 * @return array
	 */
	public function get($filename, $scope);
}
