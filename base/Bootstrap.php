<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\base;

use Yii;
use yii\web\Request;
use yii\web\UrlNormalizer;
use falcon\backend\app\Application as BackendApplication;
use falcon\frontend\app\Application as FrontendApplication;

class Bootstrap {

	/**
	 * @param $config
	 *
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\di\NotInstantiableException
	 */
	public static function run($config) {

		$container     = Yii::$container;
		$request       = $container->get(Request::class);
		$urlNormalizer = $container->get(UrlNormalizer::class);
		$path          = $request->pathInfo;
		$pathInfo      = $urlNormalizer->normalizePathInfo($path, '');
		$pathInfo      = explode('/', $pathInfo);

		if (preg_match("#^backend$#u", $pathInfo[0], $matches)) {
			(new BackendApplication($config))->run();
		} else {
			(new FrontendApplication($config))->run();
		}
	}
}