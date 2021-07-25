<?php

declare(strict_types=1);

namespace app\api\traits;

use app\api\service\Model as ModelService;

trait Model
{
    // Accessor
    public function getCreateTimeAttr(string $value)
    {
        return convertTime($value, 'Y-m-d\TH:i:sP');
    }
    public function getUpdateTimeAttr(string $value)
    {
        return convertTime($value, 'Y-m-d\TH:i:sP');
    }

    // Mutator
    public function setCreateTimeAttr(string $value)
    {
        return convertTime($value);
    }

    // Searcher
    public function searchIdAttr($query, $value)
    {
        $query->where('id', $value);
    }

    public function searchStatusAttr($query, $value)
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

    public function searchCreateTimeAttr($query, $value)
    {
        $value = urldecode($value);
        $valueArray = (array)explode(',', $value);
        $query->whereBetweenTime('create_time', $valueArray[0], $valueArray[1]);
    }

    // Other
    protected function getModelData(string $fieldName = '')
    {
        if ($fieldName) {
            $result = ModelService::where('table_name', $this->getTableName())->find();
            return $result['data'] ?? [];
        } else {
            $result = ModelService::where('table_name', $this->getTableName())->find();
            return $result ?: [];
        }
    }
}
