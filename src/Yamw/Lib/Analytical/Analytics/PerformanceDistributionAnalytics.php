<?php
namespace Yamw\Lib\Analytical\Analytics;

/**
 * Description of PerformanceAnalytics
 *
 * @author AnhNhan
 */
class PerformanceDistributionAnalytics extends AbstractAnalytics
{
    public function getCollection()
    {
        return 'yamw_stats';
    }

    public function getFields()
    {
        return array(
            'page' => true,
            'max_memory' => true,
            'numqueries' => true,
            'pagetime' => true,

            /* '_id' => false,
            'template' => false,
            'uses_template' => false,
            'abspath' => false,
            'statgroup' => false,
            'abspath' => false,
            'global_request' => false,
            'global_get' => false,
            'global_cookies' => false,
            'global_server' => false,*/
        );
    }

    public function getName()
    {
        return 'Performance Distribution';
    }

    public function getQuery()
    {
        $specifics = $this->getSpecifics();

        if ($specifics) {
            if (count($specifics) === 1) {
                return array('page' => $specifics[0]);
            } else {
                $query = array('$or' => array());

                foreach ($specifics as $value) {
                    $query['$or'][] = array('page' => $value);
                }

                return $query;
            }
        } else {
            return array();
        }
    }

    protected function process()
    {
        $data = $this->getData();

        $processed = array();

        foreach ($data as $value) {
            unset($value['_id']);

            if (!isset($processed[$value['page']])) {
                $processed[$value['page']] = array();
            }

            $page = & $processed[$value['page']];

            foreach ($value as $key => $val) {
                if ($key === 'page') {
                    continue;
                }

                $kv = $this->getKey($value[$key]);
                if (!isset($page[$key])) {
                    $page[$key] = array();
                }

                if (!isset($page[$key][$kv])) {
                    $page[$key][$kv] = 0;
                }

                $page[$key][$kv]++;
            }
        }

        $this->setProcessed($this->sortKeys($processed));
    }

    private function sortKeys(array $array)
    {
        foreach ($array as $key => &$value) {
            foreach ($value as $key2 => &$value2) {
                ksort($value2);
            }
            ksort($value);
        }
        return $array;
    }

    private function getKey($value)
    {
        return (string)round($value, 4);
    }
}
