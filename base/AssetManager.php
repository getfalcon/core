<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */


namespace falcon\core\base;

use Yii;
use yii\helpers\FileHelper;
use yii\base\InvalidParamException;

class AssetManager extends \yii\web\AssetManager
{

    /**
     * @var array published assets
     */
    private $_published = [];

    /**
     * @var string the root directory storing the published asset files.
     */
    public $basePath = '@webroot/pub';

    /**
     * @var string the base URL through which the published asset files can be accessed.
     */
    public $baseUrl = '@web/pub';


    /**
     * {@inheritdoc}
     */
    public function publish($path, $options = [])
    {
        $path = Yii::getAlias($path);

        if (isset($this->_published[$path])) {
            return $this->_published[$path];
        }

        if (!is_string($path) || ($src = realpath($path)) === false) {
            throw new InvalidParamException("The file or directory to be published does not exist: $path");
        }

        if (is_file($src)) {
            return $this->_published[$path] = $this->publishFile($src);
        }

        return $this->_published[$path] = $this->publishDirectory($src, $options);
    }

    /**
     * {@inheritdoc}
     */
//	protected function publishDirectory($src, $options)
//	{
//		$ds = DIRECTORY_SEPARATOR;
//
//		$vendor = str_replace(['\\', '/'], $ds, Yii::getAlias('@vendor'));
//
//		$componentName = trim(dirname(str_replace($vendor, '', $src)), $ds);
//		$componentName = explode($ds, $componentName);
//		$componentName = ucfirst($componentName[0]) . '_' . ucfirst($componentName[1]);
//
//		$dstDir = $this->basePath . $ds . $componentName;
//
//		var_dump($src);exit;
//		if ($this->linkAssets) {
//			if (!is_dir($dstDir)) {
//				FileHelper::createDirectory(dirname($dstDir), $this->dirMode, true);
//				try { // fix #6226 symlinking multi threaded
//					symlink($src, $dstDir);
//				} catch (\Exception $e) {
//					if (!is_dir($dstDir)) {
//						throw $e;
//					}
//				}
//			}
//		} elseif (!empty($options['forceCopy']) || ($this->forceCopy && !isset($options['forceCopy'])) || !is_dir($dstDir)) {
//			$opts = array_merge(
//				$options,
//				[
//					'dirMode' => $this->dirMode,
//					'fileMode' => $this->fileMode,
//					'copyEmptyDirectories' => false,
//				]
//			);
//			if (!isset($opts['beforeCopy'])) {
//				if ($this->beforeCopy !== null) {
//					$opts['beforeCopy'] = $this->beforeCopy;
//				} else {
//					$opts['beforeCopy'] = function ($from, $to) {
//						return strncmp(basename($from), '.', 1) !== 0;
//					};
//				}
//			}
//			if (!isset($opts['afterCopy']) && $this->afterCopy !== null) {
//				$opts['afterCopy'] = $this->afterCopy;
//			}
//			FileHelper::copyDirectory($src, $dstDir, $opts);
//		}
//
//		return [$dstDir, $this->baseUrl . '/' . $componentName];
//	}
}