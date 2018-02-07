<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\event;

use yii\base\Event;
use yii\helpers\ArrayHelper;

class Manager
{
    /**
     * @var Config
     */
    protected $_config;

    /**
     * Manager constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    public function attachEvents()
    {
        $eventConfig = $this->_config->getObservers();

        foreach ($eventConfig as $eventName => $event) {

            foreach ($event as $observer) {

                $class = ArrayHelper::getValue($observer, 'class');
                $instance = ArrayHelper::getValue($observer, 'instance');
                $method = ArrayHelper::getValue($observer, 'method', 'execute');
                $append = ArrayHelper::getValue($observer, 'append', true);

                var_dump($instance, $eventName);

                Event::on($class, $eventName, [$instance, $method], null, $append);
            }
        }
    }
}