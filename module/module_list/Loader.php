<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\module\module_list;

use falcon\core\components\ComponentRegistrar;
use Symfony\Component\Yaml\Yaml;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Loader of module list information from the filesystem
 */
class Loader
{
    /**
     * Parser
     *
     * @var Yaml
     */
    private $parser;

    /**
     * Constructor
     *
     * @param Yaml $yaml
     */
    public function __construct(
        Yaml $yaml
    )
    {
        $this->parser = $yaml;
    }

    /**
     * Loads the full module list information. Excludes modules specified in $exclude.
     *
     * @param array $exclude
     *
     * @return array
     *
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function load(array $exclude = [])
    {
        $result = [];
        foreach ($this->getModuleConfigs() as list($file, $contents)) {
            try {
                $data = Yaml::parse($contents);
            } catch (\Exception $e) {
                throw new InvalidConfigException(
                    \Yii::t('Falcon_Core', 'Invalid Document: {file}{eol} Error: {message}', [
                        'file' => $file,
                        'eol' => PHP_EOL,
                        'message' => $e->getMessage()
                    ]),
                    $e
                );
            }

            $data = $this->convert($data);
            $name = key($data);
            if (!in_array($name, $exclude)) {
                $result[$name] = $data[$name];
            }
        }
        return $this->sortBySequence($result);
    }

    private function convert($data)
    {
        $name = $data['name'];
        unset($data['name']);
        $return[$name] = $data;
        $merge = [$name => ['sequence' => []]];
        return ArrayHelper::merge($merge, $return);
    }

    /**
     * Returns module config data and a path to the module.xml file.
     *
     * Example of data returned by generator:
     *
     * <code>
     *     ['vendor/module/etc/module.xml', '<yaml>contents</yaml>']
     * </code>
     *
     * @return \Traversable
     */
    private function getModuleConfigs()
    {
        $modulePaths = ComponentRegistrar::getPaths(ComponentRegistrar::MODULE);
        foreach ($modulePaths as $modulePath) {
            $filePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $modulePath . '/etc/module.yaml');
            yield [$filePath, file_get_contents($filePath)];
        }
    }

    /**
     * Sort the list of modules using "sequence" key in meta-information
     *
     * @param array $origList
     *
     * @return array
     *
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function sortBySequence($origList)
    {
        ksort($origList);
        $expanded = [];
        foreach ($origList as $moduleName => $value) {
            $expanded[] = [
                'name' => $moduleName,
                'sequence' => $this->expandSequence($origList, $moduleName),
            ];
        }

        // Use "bubble sorting" because usort does not check each pair of elements and in this case it is important
        $total = count($expanded);
        for ($i = 0; $i < $total - 1; $i++) {
            for ($j = $i; $j < $total; $j++) {
                if (in_array($expanded[$j]['name'], $expanded[$i]['sequence'])) {
                    $temp = $expanded[$i];
                    $expanded[$i] = $expanded[$j];
                    $expanded[$j] = $temp;
                }
            }
        }

        $result = [];
        foreach ($expanded as $pair) {
            $result[$pair['name']] = $origList[$pair['name']];
        }

        return $result;
    }

    /**
     * Accumulate information about all transitive "sequence" references
     *
     * @param array  $list
     * @param string $name
     * @param array  $accumulated
     * @return array
     * @throws \Exception
     */
    private function expandSequence($list, $name, $accumulated = [])
    {
        $accumulated[] = $name;
        $result = $list[$name]['sequence'];
        foreach ($result as $relatedName) {
            if (in_array($relatedName, $accumulated)) {
                throw new \Exception("Circular sequence reference from '{$name}' to '{$relatedName}'.");
            }
            if (!isset($list[$relatedName])) {
                continue;
            }
            $relatedResult = $this->expandSequence($list, $relatedName, $accumulated);
            $result = array_unique(array_merge($result, $relatedResult));
        }
        return $result;
    }
}
