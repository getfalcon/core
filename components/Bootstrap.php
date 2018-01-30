<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\components;

use falcon\core\module\ModuleLoader;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {

    /**
     * @param \yii\base\Application $app
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app) {

        /** @var ModuleLoader $loader */
        $loader = \Yii::$container->get(ModuleLoader::class);

        $loader->run($app);

        // Init Config
        $app->get('errorHandler')->exceptionView = dirname(__FILE__) . '/../views/layouts/exception.php';
        $app->get('errorHandler')->callStackItemView = dirname(__FILE__) . '/../views/layouts/callStackItem.php';
    }
}