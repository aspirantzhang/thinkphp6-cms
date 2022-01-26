<?php

declare(strict_types=1);

namespace app\api\model;

use think\facade\Lang;
use think\facade\Request;
use think\facade\Config;
use app\common\model\GlobalModel;
use think\model\concern\SoftDelete;
use app\api\service\Model as ModelService;
use app\api\traits\Model as ModelTrait;
use app\api\traits\Logic as LogicTrait;
use app\api\traits\Service as ServiceTrait;
use app\api\traits\View as ViewTrait;
use app\api\traits\Filter as FilterTrait;
use aspirantzhang\octopusRevision\traits\Revision as RevisionTrait;

class Common extends GlobalModel
{
    use SoftDelete;
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;
    use ViewTrait;
    use FilterTrait;
    use RevisionTrait;

    protected $deleteTime = 'delete_time';

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

    private function loadModelConfig()
    {
        $modelName = $this->getModelName();
        Config::load(createPath('api', 'model', $modelName), $modelName);
    }

    protected function isTranslateField($fieldName)
    {
        return in_array($fieldName, $this->getAllowTranslate());
    }

    private function getFieldNameBySearcherName(string $functionName): string
    {
        return parse_name(substr(substr($functionName, 6), 0, -4));
    }

    protected function getQueryFieldName($functionName)
    {
        $fieldName = $this->getFieldNameBySearcherName($functionName);
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

    protected function handleDataFilter(array $data, bool $i18n = false): array
    {
        if (empty($data)) {
            return [];
        }
        $ignoreFilter = $this->getIgnoreFilter();
        if (empty($ignoreFilter)) {
            return $data;
        }
        if ($i18n) {
            // i18n, first level is language
            foreach (array_keys($data) as $langName) {
                foreach ($ignoreFilter as $fieldName) {
                    $data[$langName][$fieldName] = Request::param($langName . '.' . $fieldName, '', null);
                }
            }
            return $data;
        }
        // normal, one level
        $unfiltered = [];
        foreach ($ignoreFilter as $fieldName) {
            $unfiltered[$fieldName] = Request::param($fieldName, '', null);
        }
        return array_merge($data, $unfiltered);
    }

    protected function getModelData()
    {
        $result = ModelService::where('table_name', $this->getTableName())->find();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    protected function isReservedFieldName(string $fieldName)
    {
        if (in_array($fieldName, Config::get('reserved.reserved_field'))) {
            return true;
        }
        return false;
    }
}
