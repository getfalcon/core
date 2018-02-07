<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\event\config;

use falcon\core\config\FileResolver;
use falcon\core\config\reader\Filesystem;

class Reader extends Filesystem
{

    /**
     * Reader constructor.
     *
     * @param string       $fileName
     * @param FileResolver $fileResolver
     * @param string       $defaultScope
     */
    public function __construct(
        $fileName = 'events.yaml', FileResolver $fileResolver, $defaultScope = 'global'
    )
    {
        parent::__construct($fileName, $fileResolver, $defaultScope);
    }

    public function read($scope = null): array
    {
        return parent::read($scope);
    }
}