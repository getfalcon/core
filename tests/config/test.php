<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

use yii\helpers\ArrayHelper;

$web  = require '../../app/etc/web.php';
$test = require '../../app/etc/test.php';

return ArrayHelper::merge(ArrayHelper::merge($common, $web), $test);