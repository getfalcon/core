<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\module;

/**
 * Interface \falcon\core\module\ModuleListInterface
 */
interface ModuleListInterface
{
    /**
     * Get list of all modules
     *
     * Returns an array where key is module name and value is an array with module meta-information
     *
     * @return array
     */
    public function getAll();

    /**
     * Get module declaration data
     *
     * Returns an array with meta-information about one module by specified name
     *
     * @param string $name
     * @return array|null
     */
    public function getOne($name);
}
