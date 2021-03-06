<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\event;


interface ConfigInterface
{
    /**
     * Get observers by event name
     *
     * @return array
     */
    public function getObservers();
}