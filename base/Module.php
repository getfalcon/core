<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\base;

use falcon\core\components\ComponentRegistrar;
use Yii;

class Module extends \yii\base\Module implements ModuleInterface {
	const MODULE_ETC_DIR = 'etc';
	const MODULE_I18N_DIR = 'i18n';
	const MODULE_VIEW_DIR = 'views';
	const MODULE_CONTROLLER_DIR = 'Controller';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace;

	/**
	 * @var string the root directory that contains view files for this module
	 */
	private $_viewPath;

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		if ($this->controllerNamespace === null) {
			$controllers = '\\controllers';

			if (Yii::$app instanceof \falcon\core\console\Application) {
				$controllers .= '\\console';
			}

            if (Yii::$app instanceof \falcon\frontend\app\Application) {
				$controllers .= '\\frontend';
			}

            if (Yii::$app instanceof \falcon\backend\app\Application) {
				$controllers .= '\\backend';
			}

			$class = get_class($this);
			if (($pos = strrpos($class, '\\')) !== false) {
				$this->controllerNamespace = substr($class, 0, $pos) . $controllers;
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath() {
        if ($this instanceof \falcon\frontend\app\Application || $this instanceof \falcon\backend\app\Application) {
			$this->_viewPath = '@app/views';

			return $this->_viewPath;
		}

		if ($this->_viewPath === null) {

			$views = '';

            if (Yii::$app instanceof \falcon\frontend\app\Application) {
				$views .= DIRECTORY_SEPARATOR . 'frontend';
			}

            if (Yii::$app instanceof \falcon\backend\app\Application) {
				$views .= DIRECTORY_SEPARATOR . 'backend';
			}

			$this->_viewPath = $this->getDir(self::MODULE_VIEW_DIR) . $views;
		}

		return $this->_viewPath;
	}

	/**
	 * Retrieve full path to a directory of certain type within a module
	 *
	 * @param string $type Type of module's directory to retrieve
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getDir($type = '') {
		$path = ComponentRegistrar::getPath(ComponentRegistrar::MODULE, $this->id);

		if ($type) {
			if ( ! in_array($type, [
				self::MODULE_ETC_DIR,
				self::MODULE_I18N_DIR,
				self::MODULE_VIEW_DIR,
				self::MODULE_CONTROLLER_DIR
			])) {
				throw new \InvalidArgumentException("Directory type '{$type}' is not recognized.");
			}
			$path .= '/' . $type;
		}

		return $path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function events() {
		return [];
	}
}