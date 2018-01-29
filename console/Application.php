<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\console;

use falcon\core\console\migrations\AttachModulesMigrations;
use yii\console\controllers\MigrateController;

class Application extends \yii\console\Application {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
		$container = \Yii::$container;
		/** @var AttachModulesMigrations $attachModulesMigrations */
		$attachModulesMigrations = $container->get(AttachModulesMigrations::class);

		\Yii::$container->set(MigrateController::class, [
			'migrationNamespaces' => $attachModulesMigrations->get()
		]);
	}
}