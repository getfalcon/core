<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\module;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ModuleLoader extends Component
{

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
     * @var \yii\base\Application
     */
    protected $app;

    /**
     * @var ModuleList
     */
    protected $list;

    public function __construct(
        ModuleList $list,
        array $config = []
    )
    {
        $this->list = $list;
        parent::__construct($config);
    }

    /**
	 * {{@inheritdoc}}
	 */
	public function init() {
		if ( ! isset($this->cacheKey)) {
			$this->cacheKey = ['ModulesLoader'];
		}
	}

    /**
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function run($app)
    {
        $this->app = $app;
        $this->initModules();
        $this->initBootstrap();
    }

    /**
     * @return array
     */
    public function getBootstraps()
    {
        $this->load();

        return $this->bootstrap;
    }

    /**
     * @return array
	 */
    private function getModules()
    {
		$this->load();

		return $this->modules;
	}

    /**
     * @throws \yii\base\InvalidConfigException
     */
    private function initBootstrap()
    {
        foreach ($this->getBootstraps() as $mixed) {
            $component = \Yii::createObject($mixed);

            if ($component instanceof BootstrapInterface) {
                \Yii::trace('Bootstrap with ' . get_class($component) . '::bootstrap()', __METHOD__);
                $component->bootstrap($this->app);
            } else {
                \Yii::trace('Bootstrap with ' . get_class($component), __METHOD__);
            }
        }
    }

    /**
     * @return void
     */
    public function initModules()
    {
        $this->app->setModules($this->getModules());
    }

    /**
     * Load All modules
     */
	private function load() {

		if ( ! isset($this->modules) || ! isset($this->bootstrap)) {
			$cache = $this->getCache()->get($this->cacheKey);
			if ($cache['modules'] === null || $cache['bootstrap'] === null) {
                foreach ($this->list->getAll() as $name => $data) {
                    $this->modules[$name] = ['class' => ArrayHelper::getValue($data, 'moduleClass')];
                    $this->bootstrap[] = ArrayHelper::getValue($data, 'bootstrapClass');
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
}