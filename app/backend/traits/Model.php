<?php

declare(strict_types=1);

namespace app\backend\traits;

use app\backend\service\Model as ModelService;

trait Model
{
    protected function getTableName()
    {
        return parse_name($this->getName());
    }

    // Accessor
    public function getCreateTimeAttr($value)
    {
        $date = new \DateTime($value);
        return $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }
    public function getUpdateTimeAttr($value)
    {
        $date = new \DateTime($value);
        return $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        $value = (string)$value;
        if (strlen($value)) {
            if (strpos($value, ',')) {
                $query->whereIn('status', $value);
            } else {
                $query->where('status', $value);
            }
        }
    }

    public function searchCreateTimeAttr($query, $value, $data)
    {
        $value = urldecode($value);
        $valueArray = (array)explode(',', $value);
        $query->whereBetweenTime('create_time', $valueArray[0], $valueArray[1]);
    }

    // Other
    protected function getModelData()
    {
        $modelData = ModelService::where('name', $this->getTableName())->find();
        if ($modelData) {
            return $modelData['data'];
        }
        return [];
    }

    protected function getModelFields($type = null)
    {
        $data = $this->getModelData();
        if ($data) {
            switch ($type) {
                case 'home':
                case 'list':
                    $rawArray = array_filter((array)$data['fields'], function ($value) {
                        if (isset($value['listHideInColumn']) && $value['listHideInColumn'] === '1') {
                            return false;
                        }
                        return true;
                    });
                    return extractValues($rawArray, 'name');
                case 'sort':
                    $rawArray = array_filter($data['fields'], function ($value) {
                        if (isset($value['listSorter']) && $value['listSorter'] === '1') {
                            return true;
                        }
                        return false;
                    });
                    return extractValues($rawArray, 'name');
                case 'update':
                    $rawArray = array_filter($data['fields'], function ($value) {
                        if (isset($value['editDisabled']) && $value['editDisabled'] === '1') {
                            return false;
                        }
                        return true;
                    });
                    return extractValues($rawArray, 'name');
                case 'read':
                case 'save':
                case 'search':
                case 'all':
                    return extractValues($data['fields'], 'name');
                default:
                    return [];
            }
        }
        return [];
    }
}
