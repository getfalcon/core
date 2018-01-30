<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\console\migrations;

use falcon\core\base\Module;
use Yii;
use yii\base\Component;

class AttachModulesMigrations extends Component {

	/**
	 * @var string the cache key that will be used to cache storage values
	 */
	public $cacheKey;

	/**
	 * @var array Migration Namespaces
	 */
	protected $migrationNamespaces;

	/**
	 * {{@inheritdoc}}
	 */
	public function init() {
		if ( ! isset($this->cacheKey)) {
			$this->cacheKey = ['AttachModulesMigrations'];
		}
	}

	/**
	 * @param Module $module
	 */
	public function attach(Module $module) {
		$name = $module->id;

		if ( ! isset($this->migrationNamespaces[ $name ])) {
			$this->add($name);
		}
	}

    /**
     * @param string $module Module ID
     *
     * @throws \ReflectionException
     */
	protected function add($module) {
        $namespace = new \ReflectionClass(get_class(Yii::$app->getModule($module)));

		$this->migrationNamespaces[ $module ] = $namespace->getNamespaceName() . '\\' . 'migrations';
	}

	/**
	 * @return array
	 */
	public function get() {
		$this->load();

		return $this->migrationNamespaces;
	}

	/**
	 * Load all namespaces
	 */
	private function load() {
		if ( ! isset($this->migrationNamespaces)) {
			$this->migrationNamespaces = $this->getCache()->get($this->cacheKey);

			if ($this->migrationNamespaces === false) {

				$this->migrationNamespaces = [];
				foreach (Yii::$app->modules as $name => $config) {
					$this->add($name);
				}

				$this->cache();
			}
		}
	}

	/**
	 * @return \yii\caching\CacheInterface
	 */
	protected function getCache() {
		return Yii::$app->getCache();
	}

	/**
	 * Caches current.
	 */
	protected function cache() {
		$this->getCache()->set($this->cacheKey, $this->migrationNamespaces);
	}
}