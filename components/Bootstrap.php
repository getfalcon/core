<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\components;

use yii\base\BootstrapInterface;
use yii\console\controllers\MigrateController;

class Bootstrap implements BootstrapInterface {
	/**
	 * @param \yii\base\Application $app
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	public function bootstrap($app) {
		$container = \Yii::$container;
		/** @var ModulesLoader $moduleLoader */
		$moduleLoader = $container->get(ModulesLoader::class);

		// Init Modules
		$app->setModules($moduleLoader->getModules());

		// Init Bootstrap
		foreach ($moduleLoader->getBootstraps() as $mixed) {
			$component = \Yii::createObject($mixed);
			if ($component instanceof BootstrapInterface) {
				\Yii::trace('Bootstrap with ' . get_class($component) . '::bootstrap()', __METHOD__);
				$component->bootstrap($app);
			} else {
				\Yii::trace('Bootstrap with ' . get_class($component), __METHOD__);
			}
		}
	}
}