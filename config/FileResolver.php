<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\config;

use falcon\core\base\Module;
use falcon\core\components\ComponentRegistrar;
use Symfony\Component\Yaml\Yaml;
use yii\helpers\ArrayHelper;

/**
 * Class FileResolver
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FileResolver implements FileResolverInterface {
	/**
	 * {{@inheritdoc}}
	 */
	public function get($filename, $scope) {
		$return          = [];
		$registerModules = ComponentRegistrar::getPaths(ComponentRegistrar::MODULE);

		foreach ($registerModules as $name => $p) {
			$path = $p . DIRECTORY_SEPARATOR . Module::MODULE_ETC_DIR . DIRECTORY_SEPARATOR;

			switch ($scope) {
				case 'global':
					$path .= '';
					break;
				case 'frontend':
					$path .= 'frontend' . DIRECTORY_SEPARATOR;
					break;
				case 'backend':
					$path .= 'backend' . DIRECTORY_SEPARATOR;
					break;
			}

			$path .= $filename;

            if (file_exists($path) && ($yaml = Yaml::parse(file_get_contents($path)))) {
                $return = ArrayHelper::merge($return, $yaml);
			}
		}

		return $return;
	}
}
