<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core;

use falcon\core\console\Application;
use falcon\core\event\Manager;
use falcon\core\module\ModuleLoader;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    /**
     * @param \yii\base\Application $app
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        $container = \Yii::$container;
        /**
         * @var ModuleLoader $loader
         * @var Manager      $eventManager
         */
        $loader = $container->get(ModuleLoader::class);
        $eventManager = $container->get(Manager::class);

        $loader->run($app);
        $eventManager->attachEvents();

        // Init Config
        if (!$app instanceof Application) {
            $app->get('errorHandler')->exceptionView = dirname(__FILE__) . '/views/layouts/exception.php';
            $app->get('errorHandler')->callStackItemView = dirname(__FILE__) . '/views/layouts/callStackItem.php';
        }
    }
}