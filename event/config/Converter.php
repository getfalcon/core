<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\event\config;

use yii\helpers\ArrayHelper;

class Converter
{
    /**
     * Convert dom node tree to array
     *
     * @param array $source
     * @return array
     * @throws \InvalidArgumentException
     */
    public function convert(array $source)
    {
        $output = [];
        foreach ($source['events'] as $eventConfig) {
            $eventName = ArrayHelper::getValue($eventConfig, ['event', 'name'], '');

            $eventObservers = [];
            foreach (ArrayHelper::getValue($eventConfig, ['event', 'observers'], []) as $observerConfig) {

                if (!($observerName = ArrayHelper::getValue($observerConfig, ['observer', 'name']))) {
                    throw new \InvalidArgumentException('Attribute name is missed');
                }

                if (!($observerClass = ArrayHelper::getValue($observerConfig, ['observer', 'class']))) {
                    throw new \InvalidArgumentException('Attribute class is missed');
                }

                $config = $this->_convertObserverConfig($observerConfig);
                $config['name'] = $observerName;
                $config['class'] = $observerClass;
                $eventObservers[$observerName] = $config;
            }

            if (isset($output[$eventName])) {
                $output[$eventName] = array_merge($output[mb_strtolower($eventName)], $eventObservers);
            } else {
                $output[$eventName] = $eventObservers;
            }
        }
        return $output;
    }

    /**
     * Convert observer configuration
     *
     * @param array $observerConfig
     * @return array
     */
    public function _convertObserverConfig($observerConfig)
    {
        $output = [];
        /** Parse instance configuration */
        if ($instance = ArrayHelper::getValue($observerConfig, ['observer', 'instance'])) {
            $output['instance'] = $instance;
        }

        /** Parse instance method configuration */
        if ($method = ArrayHelper::getValue($observerConfig, ['observer', 'method'])) {
            $output['method'] = $method;
        }

        /** Parse disabled/enabled configuration */
        if (($disabled = ArrayHelper::getValue($observerConfig, ['observer', 'disabled'])) && $disabled == 'true') {
            $output['disabled'] = true;
        }

        /** Parse shareability configuration */
        if (($append = ArrayHelper::getValue($observerConfig, ['observer', 'append'])) && $shared == 'false') {
            $output['append'] = false;
        }

        return $output;
    }
}
