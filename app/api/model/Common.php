<?php

declare(strict_types=1);

namespace app\api\model;

use think\facade\Lang;
use think\facade\Request;
use app\common\model\GlobalModel;
use think\model\concern\SoftDelete;
use app\api\traits\Model as ModelTrait;
use app\api\traits\Logic as LogicTrait;
use app\api\traits\Service as ServiceTrait;
use app\api\traits\View as ViewTrait;
use app\api\traits\Filter as FilterTrait;

class Common extends GlobalModel
{
    use SoftDelete;
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;
    use ViewTrait;
    use FilterTrait;

    protected $deleteTime = 'delete_time';
    protected $titleField = null;
    protected $revisionTable = [];
    protected $uniqueValue = [];
    protected $ignoreFilter = [];

    public function getModelName()
    {
        return $this->getName();
    }

    public function getTableName()
    {
        return parse_name($this->getName());
    }

    public function getLangTableName()
    {
        return $this->getTableName() . '_i18n';
    }

    protected function getCurrentLanguage()
    {
        return Lang::getLangSet();
    }

    protected function isTranslateField($fieldName)
    {
        return in_array($fieldName, $this->getAllowTranslate());
    }

    protected function getQueryFieldName($functionName)
    {
        $fieldName = getFieldNameBySearcherName($functionName);
        if ($this->isTranslateField($fieldName)) {
            return 'i.' . $fieldName;
        }
        return $fieldName;
    }

    protected function withI18n($instance)
    {
        // o = original table
        // i = i18n table
        return $instance->alias('o')
            ->leftJoin($this->getLangTableName() . ' i', 'o.id = i.original_id')
            ->where('i.lang_code', $this->getCurrentLanguage());
    }

    protected function getNoNeedToTranslateFields($scene)
    {
        $sceneMethodName = 'getAllow' . parse_name($scene, 1);
        return array_diff($this->$sceneMethodName(), $this->getAllowTranslate());
    }

    public function scopeStatus($query)
    {
        $tableName = $this->getTableName();
        $query->where($tableName . '.status', 1);
    }

    public function success(string $message = '', array $data = [], array $header = [], $addition = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        $httpBody = array_merge($httpBody, $addition);
        return [$httpBody, 200, $header];
    }

    public function error(string $message = '', array $data = [], array $header = [], $addition = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        $httpBody = array_merge($httpBody, $addition);
        return [$httpBody, 200, $header];
    }

    public function isTrash($params = [])
    {
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            return true;
        }
        return false;
    }

    protected function getTitleField(): string
    {
        return $this->titleField ?? 'id';
    }

    public function getRevisionTable(): array
    {
        return $this->revisionTable ?? [];
    }

    protected function handleDataFilter(array $data, bool $i18n = false): array
    {
        if (empty($data)) {
            return [];
        }
        $ignoreFilter = $this->getIgnoreFilter();
        if (!empty($ignoreFilter)) {
            if ($i18n) {
                // i18n, first level is language
                foreach ($data as $langName => $langFields) {
                    foreach ($ignoreFilter as $fieldName) {
                        $data[$langName][$fieldName] = Request::param($langName . '.' . $fieldName, '', null);
                    }
                }
                return $data;
            } else {
                // normal, one level
                $unfiltered = [];
                foreach ($ignoreFilter as $fieldName) {
                    $unfiltered[$fieldName] = Request::param($fieldName, '', null);
                }
                return array_merge($data, $unfiltered);
            }
        }
        return $data;
    }
}
