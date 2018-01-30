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

class UrlRulesLoader extends Component
{

	const ROUTE_BACKEND = 'backend';
	const ROUTE_FRONTEND = 'frontend';

	/**
	 * @var string the default module name from '/' url
	 */
    public $baseRouteFrontendModuleName = 'Falcon_Frontend';

	/**
	 * @var string the cache key that will be used to cache storage values
	 */
	public $cacheKey;

	/**
	 * @var array modules
	 */
	protected $rules;

	/**
	 * {{@inheritdoc}}
	 */
	public function init() {
		if ( ! isset($this->cacheKey)) {
			$this->cacheKey = ['UrlRulesLoader'];
		}
	}

	/**
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function getRules() {
		$this->load();

		return $this->rules;
	}

	/**
	 * @throws InvalidConfigException
	 */
	private function load() {
		if ( ! isset($this->rules)) {
			$this->rules = $this->getCache()->get($this->cacheKey);
			if ($this->rules === false) {
				$registerRules = ComponentRegistrar::getPaths(ComponentRegistrar::MODULE);
				$this->rules   = [];
				foreach ($registerRules as $name => $path) {
					$fileFrontend = $path . DS . 'etc' . DS . 'frontend' . DS . 'routes.yaml';
					$fileBackend  = $path . DS . 'etc' . DS . 'backend' . DS . 'routes.yaml';

					if (file_exists($fileFrontend)) {
						$this->addRule(Yaml::parse(file_get_contents($fileFrontend)), $name, self::ROUTE_FRONTEND, \yii\web\UrlRule::class);
					}

					if (file_exists($fileBackend)) {
                        $this->addRule(Yaml::parse(file_get_contents($fileBackend)), $name, self::ROUTE_BACKEND, \falcon\backend\app\UrlRule::class);
					}
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
	 * @param array  $urlRules
	 * @param string $moduleName
	 * @param string $type
	 * @param string $urlRuleClass
	 *
	 * @throws InvalidConfigException
	 */
	protected function addRule($urlRules, $moduleName, $type, $urlRuleClass) {

		if (is_array($urlRules)) {

			if ( ! isset($urlRules['router']) || ! isset($urlRules['route']) || ! isset($urlRules['route'])) {
				throw new InvalidConfigException('URL rules error in routes.yaml.');
			}

			$route = $urlRules['route'];

			if ($type == self::ROUTE_BACKEND) {
				$route = 'backend/' . $route . '/';
			} else if ($type == self::ROUTE_FRONTEND && $moduleName == $this->baseRouteFrontendModuleName) {
				$route = '';
			} else {
				$route .= '/';
			}

			$this->rules = ArrayHelper::merge($this->rules, [
				[
					'class'   => $urlRuleClass,
					'pattern' => $route . '<_controller:[\w-]+>/<_action:[\w-]+>',
					'route'   => $moduleName . '/<_controller>/<_action>'
				],
				[
					'class'   => $urlRuleClass,
					'pattern' => $route . '<_controller:[\w-]+>',
					'route'   => $moduleName . '/<_controller>/index'
				],
				[
					'class'   => $urlRuleClass,
					'pattern' => $route,
					'route'   => $moduleName . '/index/index'
				],
			]);
		}
	}

	/**
	 * Caches current.
	 */
	protected function cache() {
		$this->getCache()->set($this->cacheKey, $this->rules);
	}
}