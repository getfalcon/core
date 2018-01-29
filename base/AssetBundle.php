<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\base;


class AssetBundle extends \yii\web\AssetBundle {

	public function init() {
		$reflector        = new \ReflectionClass($this::className());
		$this->sourcePath = dirname($reflector->getFileName()) . '/../views/web/';
	}
}