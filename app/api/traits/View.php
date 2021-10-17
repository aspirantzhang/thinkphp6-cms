<?php

declare(strict_types=1);

namespace app\api\traits;

use aspirantzhang\octopusPageBuilder\Builder;
use think\facade\Config;
use think\Exception;
use aspirantzhang\octopusPageBuilder\PageBuilder;

trait View
{
    protected function actionBuilder(array $data)
    {
        if (!empty($data)) {
            $result = [];
            foreach ($data as $action) {
                $row = [];
                $row = (array)Builder::button($action['name'])
                    ->type($action['type'])
                    ->call($action['call'])
                    ->method($action['method']);
                if (isset($action['uri'])) {
                    $row = (array)Builder::button($action['name'])
                        ->type($action['type'])
                        ->call($action['call'])
                        ->method($action['method'])
                        ->uri($action['uri']);
                }
                $result[] = $row;
            }
            return $result;
        }
        return [];
    }

    private function buildSingleField(string $tableName, array $field, string $type)
    {
        $result = Builder::field($tableName . '.' . $field['name'])->type($field['type']);
        if (isset($field['data'])) {
            $result = $result->data($field['data']);
        }
        $result = $result->toArray();
        if (isset($field['settings']['display'])) {
            if ($type === 'list' && in_array('hideInColumn', $field['settings']['display'])) {
                return false;
            }
            if (in_array('listSorter', $field['settings']['display'])) {
                $result['sorter'] = true;
            }
        }
        return $result;
    }

    protected function fieldBuilder(array $blockData, string $tableName, $type = 'list')
    {
        if (empty($blockData)) {
            return [];
        }
        $result = [];
        foreach ($blockData as $block) {
            foreach ($block as $field) {
                $singleField = $this->buildSingleField($tableName, $field, $type);
                if ($singleField === false) {
                    continue;
                }
                $result[] = $this->buildSingleField($tableName, $field, $type);
            }
        }
        return $result;
    }

    private function buildSingleBlockField(array $field, string $tableName)
    {
        $result = Builder::field($tableName . '.' . $field['name'])->type($field['type']);
        if (isset($field['data'])) {
            $result = $result->data($field['data']);
        }
        if (isset($field['settings']['display']) && in_array('editDisabled', $field['settings']['display'])) {
            $result->editDisabled = true;
        }
        if ($field['name'] === $this->getTitleField()) {
            $result->titleField(true);
        }
        return $result->toArray();
    }

    private function buildBlockFields(PageBuilder $builder, array $blockData, string $type, string $tableName, array $addonData)
    {
        foreach ($blockData as $blockName => $blockFields) {
            $fieldSet = [];
            foreach ($blockFields as $field) {
                $fieldSet[] = $this->buildSingleBlockField($field, $tableName);
            }
            if ($type === 'sidebar' && $blockName === 'basic') {
                $builtInFieldSet = [
                    Builder::field('create_time')->type('datetime'),
                    Builder::field('update_time')->type('datetime'),
                    Builder::field('status')->type('switch')->data($addonData['status']),
                ];
                $fieldSet = array_merge($fieldSet, $builtInFieldSet);
            }
            $builder = $builder->$type($blockName, $fieldSet);
        }
        return $builder;
    }

    private function buildBlockLayout(PageBuilder $builder, array $blockData, string $type, array $addon = [])
    {
        $actionSet = [];
        foreach ($blockData as $layout) {
            $thisAction = Builder::button($layout['name'])->type($layout['type'])->call($layout['call'])->method($layout['method']);
            if (isset($layout['uri'])) {
                if ($type === 'edit') {
                    $layout['uri'] = str_replace(':id', (string)$addon['id'], $layout['uri']);
                }
                $thisAction = $thisAction->uri($layout['uri']);
            }
            $actionSet[] = $thisAction;
        }
        return empty($actionSet) ? $builder : $builder->action('actions', $actionSet);
    }

    public function addBuilder(array $addonData = [])
    {
        $model = $this->getModelData();
        if (empty($model) || !isset($model['table_name'])) {
            throw new Exception(__('unable to get model data'));
        }
        $tableName = $model['table_name'];

        if (isset($model['data']['fields']['tabs'])) {
            $result = Builder::page($tableName . '-layout.' . $tableName . '-add');

            $result = $this->buildBlockFields($result, $model['data']['fields']['tabs'], 'tab', $tableName, $addonData);
            $result = $this->buildBlockFields($result, $model['data']['fields']['sidebars'], 'sidebar', $tableName, $addonData);
            $result = $this->buildBlockLayout($result, $model['data']['layout']['addAction'], 'add');

            return $result->toArray();
        }
        return [];
    }

    public function editBuilder(int $id, array $addonData = [])
    {
        $model = $this->getModelData();
        if (empty($model) || !isset($model['table_name'])) {
            throw new Exception(__('unable to get model data'));
        }
        $tableName = $model['table_name'];

        if (isset($model['data']['fields']['tabs'])) {
            $result = Builder::page($tableName . '-layout.' . $tableName . '-edit');

            $result = $this->buildBlockFields($result, $model['data']['fields']['tabs'], 'tab', $tableName, $addonData);
            $result = $this->buildBlockFields($result, $model['data']['fields']['sidebars'], 'sidebar', $tableName, $addonData);
            $result = $this->buildBlockLayout($result, $model['data']['layout']['editAction'], 'edit', ['id' => $id]);

            return $result->toArray();
        }
        return [];
    }

    public function listBuilder(array $addonData = [], array $params = [])
    {
        $model = $this->getModelData();
        if (empty($model) || !isset($model['table_name'])) {
            throw new Exception(__('unable to get model data'));
        }
        $tableName = $model['table_name'];

        $tableToolbar = [];
        if (isset($model['data']['layout']['tableToolbar'])) {
            $tableToolbar = $this->actionBuilder($model['data']['layout']['tableToolbar']);
        }

        $batchToolbar = [];
        if ($this->isTrash($params)) {
            if (isset($model['data']['layout']['batchToolbarTrashed'])) {
                $batchToolbar = $this->actionBuilder($model['data']['layout']['batchToolbarTrashed']);
            }
        } else {
            if (isset($model['data']['layout']['batchToolbar'])) {
                $batchToolbar = $this->actionBuilder($model['data']['layout']['batchToolbar']);
            }
        }

        $listFields = [];
        if (isset($model['data']['fields']['tabs'])) {
            $listFields = $this->fieldBuilder($model['data']['fields']['tabs'], $tableName);
        }
        if (isset($model['data']['fields']['sidebars'])) {
            $listFields = array_merge($listFields, $this->fieldBuilder($model['data']['fields']['sidebars'], $tableName));
        }

        $addonFields = [
            Builder::field('create_time')->type('datetime')->listSorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('i18n')->type('i18n'),
            Builder::field('trash')->type('trash'),
        ];

        $actions = [];
        if (isset($model['data']['layout']['listAction'])) {
            $actions = $this->actionBuilder($model['data']['layout']['listAction']);
        }
        $actionFields = Builder::field('actions')->data($actions);
        $tableColumn = array_merge($listFields, $addonFields, [$actionFields]);

        return Builder::page($tableName . '-layout.' . $tableName . '-list')
            ->type('basic-list')
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolbar)
            ->batchToolBar($batchToolbar)
            ->toArray();
    }

    public function i18nBuilder()
    {
        $model = $this->getModelData();
        if (empty($model) || !isset($model['table_name'])) {
            throw new Exception(__('unable to get model data'));
        }
        $tableName = $model['table_name'];

        $translateFields = [];
        $allFields = [];
        foreach ($model['data']['fields']['tabs'] as $tab) {
            $allFields = array_merge($allFields, $tab);
        }
        foreach ($model['data']['fields']['sidebars'] as $sidebar) {
            $allFields = array_merge($allFields, $sidebar);
        }
        if (!empty($allFields)) {
            foreach ($allFields as $field) {
                if ($field['allowTranslate'] ?? false) {
                    $translateFields[] = $field;
                }
            }
        }

        $fields = [];
        if (!empty($translateFields)) {
            $fields = $this->fieldBuilder([$translateFields], $tableName, 'search');
        }

        return Builder::i18n($tableName . '-layout.' . $tableName . '-i18n')
            ->layout(Config::get('lang.allow_lang_list'), $fields)
            ->toArray();
    }
}
