<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\components;

class ComponentRegistrar {

	/**
	 * Different types of components
	 */
	const MODULE = 'module';
	const THEME = 'theme';
	const LANGUAGE = 'language';

	private static $paths = [
		self::MODULE   => [],
		self::LANGUAGE => [],
		self::THEME    => [],
	];

	/**
	 * Sets the location of a component.
	 *
	 * @param string $type component type
	 * @param string $componentName Fully-qualified component name
	 * @param string $path Absolute file path to the component
	 *
	 * @throws \LogicException
	 * @return void
	 */
	public static function register($type, $componentName, $path) {
		self::validateType($type);
		if (isset(self::$paths[ $type ][ $componentName ])) {
			throw new \LogicException(ucfirst($type) . ' \'' . $componentName . '\' from \'' . $path . '\' ' . 'has been already defined in \'' . self::$paths[ $type ][ $componentName ] . '\'.');
		} else {
			self::$paths[ $type ][ $componentName ] = str_replace('\\', '/', $path);
		}
	}

	/**
	 * Checks if type of component is valid
	 *
	 * @param string $type
	 *
	 * @return void
	 * @throws \LogicException
	 */
	private static function validateType($type) {
		if ( ! isset(self::$paths[ $type ])) {
			throw new \LogicException('\'' . $type . '\' is not a valid component type');
		}
	}

	/**
	 * Get list of registered Magento components
	 *
	 * Returns an array where key is fully-qualified component name and value is absolute path to component
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public static function getPaths($type) {
		self::validateType($type);

		return self::$paths[ $type ];
	}

	/**
	 * Get path of a component if it is already registered
	 *
	 * @param string $type
	 * @param string $componentName
	 *
	 * @return null|string
	 */
	public static function getPath($type, $componentName) {
		self::validateType($type);

		return isset(self::$paths[ $type ][ $componentName ]) ? self::$paths[ $type ][ $componentName ] : null;
	}
}