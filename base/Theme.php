<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\base;

use Yii;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class Theme extends \yii\base\Theme {
	public $themeName;

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		if ($this->themeName === null) {
			throw new InvalidConfigException('The "themeName" property must be set.');
		}

		$this->registerAliases();
		$this->registerTranslations();
	}

	public function registerAliases() {
		$class = new ReflectionClass(self::className());
	}

	/**
	 *
	 */
	public function registerTranslations() {
		if (preg_match('#app\\\\templates\\\\frontend\\\\(.*)\\\\#', self::className(), $idTemplate)) {
			$template = ArrayHelper::getValue($idTemplate, '1', '');

			\Yii::$app->i18n->translations[ 'tpl_' . $template . '*' ] = [
				'class'          => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath'       => '@app/templates/frontend/' . $template . '/messages',
			];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function applyTo($path) {
		$pathMap = $this->pathMap;
		if (empty($pathMap)) {
			if (($basePath = $this->getBasePath()) === null) {
				throw new InvalidConfigException('The "basePath" property must be set.');
			}
			$pathMap = [Yii::$app->getBasePath() => [$basePath]];
		}

		$path = FileHelper::normalizePath($path);

		foreach ($pathMap as $from => $to) {
			$oldFrom = $from;
			$from    = FileHelper::normalizePath(Yii::getAlias($from)) . DIRECTORY_SEPARATOR;

			if (strpos($path, $from) === 0) {
				$n = strlen($from);

				foreach ((array) $to as $item) {
					$item = FileHelper::normalizePath(Yii::getAlias($item)) . DIRECTORY_SEPARATOR;
					$file = $item . substr($path, $n);

					if ($oldFrom == '@app/modules') {
						$file = str_replace('views' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR, '', $file);
					}

					if (is_file($file)) {
						return $file;
					}
				}
			}
		}

		return $path;
	}
}
