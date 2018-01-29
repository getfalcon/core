<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\components;

use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ModulesLoader extends Component {

	/**
	 * @var string the cache key that will be used to cache storage values
	 */
	public $cacheKey;

	/**
	 * @var array modules
	 */
	protected $modules;

	/**
	 * @var array bootstrap init for modules
	 */
	protected $bootstrap;

	/**
	 * {{@inheritdoc}}
	 */
	public function init() {
		if ( ! isset($this->cacheKey)) {
			$this->cacheKey = ['ModulesLoader'];
		}
	}

	/**
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function getModules() {
		$this->load();

		return $this->modules;
	}

	/**
	 * @throws InvalidConfigException
	 */
	private function load() {

		if ( ! isset($this->modules) || ! isset($this->bootstrap)) {
			$cache = $this->getCache()->get($this->cacheKey);
			if ($cache['modules'] === null || $cache['bootstrap'] === null) {
				$registerModules = ComponentRegistrar::getPaths(ComponentRegistrar::MODULE);

				foreach ($registerModules as $name => $path) {
					$moduleFile = $path . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'module.yaml';

					if ( ! file_exists($moduleFile)) {
						throw new InvalidConfigException('Not found module yaml file');
					}

					$dataModuleFile = Yaml::parse(file_get_contents($moduleFile));

					if ($name === ArrayHelper::getValue($dataModuleFile, 'name')) {
						$this->modules[ $name ] = [
							'class' => ArrayHelper::getValue($dataModuleFile, 'moduleClass')
						];
						$this->bootstrap[]      = ArrayHelper::getValue($dataModuleFile, 'bootstrapClass');
					}
				}
				$this->cache();
			} else {
				$this->modules   = $this->getCache()->get($this->cacheKey)['modules'];
				$this->bootstrap = $this->getCache()->get($this->cacheKey)['bootstrap'];
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
		$this->getCache()->set($this->cacheKey, ['modules' => $this->modules, 'bootstrap' => $this->bootstrap]);
	}

	/**
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function getBootstraps() {
		$this->load();

		return $this->bootstrap;
	}
}