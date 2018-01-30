<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\module;

/**
 * A list of modules in the Magento application
 *
 * Encapsulates information about whether modules are enabled or not.
 * Represents only enabled modules through its interface
 */
class ModuleList implements ModuleListInterface
{
    /**
     * Loader of module information from source code
     *
     * @var module_list\Loader
     */
    private $loader;

    /**
     * Constructor
     *
     * @param module_list\Loader $loader
     */
    public function __construct(module_list\Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     *
     * Note that this triggers loading definitions of all existing modules in the system.
     * Use this method only when you actually need modules' declared meta-information.
     *
     * @see getNames()
     */
    public function getAll()
    {
        return $this->loader->load();
    }

    /**
     * {@inheritdoc}
     * @see has()
     */
    public function getOne($name)
    {
        $modules = $this->getAll();
        return isset($modules[$name]) ? $modules[$name] : null;
    }
}
