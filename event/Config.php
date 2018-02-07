<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\event;

use falcon\core\event\config\Converter;
use falcon\core\event\config\Reader;

/**
 * Class Config
 * @api
 */
class Config implements ConfigInterface
{
    const CACHE_ID = 'events_config_observers';

    /**
     * @var null|array|mixed
     */
    protected $_observers;

    /**
     * @var Reader
     */
    protected $_configReader;

    /**
     * @var Converter
     */
    protected $_converter;

    /**
     * Config constructor.
     *
     * @param Reader    $configReader
     * @param Converter $converter
     */
    public function __construct(
        Reader $configReader,
        Converter $converter
    )
    {
        $this->_configReader = $configReader;
        $this->_converter = $converter;
    }

    /**
     * Get observers
     *
     * @return array
     */
    public function getObservers()
    {
        $this->_initObservers();
        return $this->_observers;
    }

    /**
     * Initialize Observers object
     *
     * @return void
     */
    protected function _initObservers()
    {
        if (!$this->_observers) {
            $this->_observers = $this->getCache()->get(self::CACHE_ID);

            if ($this->_observers === false) {

                $this->_observers = $this->_converter->convert($this->_configReader->read());
                $this->cache();
            }
        }
    }

    /**
     * @return \yii\caching\CacheInterface
     */
    protected function getCache()
    {
        return \Yii::$app->getCache();
    }

    /**
     * Caches current.
     */
    protected function cache()
    {
        $this->getCache()->set(self::CACHE_ID, $this->_observers);
    }
}
