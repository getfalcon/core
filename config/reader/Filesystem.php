<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\config\reader;

use falcon\core\config\FileResolverInterface;
use falcon\core\config\ReaderInterface;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @api
 */
class Filesystem implements ReaderInterface {
	/**
	 * The name of file that stores configuration
	 *
	 * @var string
	 */
	protected $_fileName;

	/**
	 * @var string
	 */
	protected $_defaultScope;

	/**
	 * File locator
	 *
	 * @var FileResolverInterface
	 */
	protected $_fileResolver;

	/**
	 * Constructor
	 *
	 * @param string                $fileName
	 * @param FileResolverInterface $resolver
	 * @param string                $defaultScope
	 */
	public function __construct(
		$fileName, FileResolverInterface $resolver, $defaultScope = 'global'
	) {
		$this->_fileName     = $fileName;
		$this->_fileResolver = $resolver;
		$this->_defaultScope = $defaultScope;
	}

	/**
	 * Load configuration scope
	 *
	 * @param string|null $scope
	 *
	 * @return array
	 */
	public function read($scope = null): array {
		$scope  = $scope ?: $this->_defaultScope;
		$output = $this->_fileResolver->get($this->_fileName, $scope);
		if ( ! count($output)) {
			return [];
		}

		return $output;
	}
}
