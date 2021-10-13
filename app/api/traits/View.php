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

    protected function fieldBuilder(array $data, string $tableName, $type = 'list')
    {
        if (!empty($data)) {
            $result = [];
            foreach ($data as $field) {
                $row = [];
                $row = (array)Builder::field($tableName . '.' . $field['name'])->type($field['type']);
                if (isset($field['data'])) {
                    $row = (array)Builder::field($tableName . '.' . $field['name'])->type($field['type'])->data($field['data']);
                }
                if (isset($field['settings']['display'])) {
                    if ($type === 'list' && in_array('hideInColumn', $field['settings']['display'])) {
                        continue;
                    }
                    if (in_array('listSorter', $field['settings']['display'])) {
                        $row['sorter'] = true;
                    }
                }
                $result[] = $row;
            }
            return $result;
        }
        return [];
    }

    public function buildBlockFields(PageBuilder $builder, array $blockData, string $type, string $tableName, array $addonData)
    {
        foreach ($blockData as $blockName => $blockFields) {
            $fieldSet = [];
            foreach ($blockFields as $field) {
                $thisField = Builder::field($tableName . '.' . $field['name'])->type($field['type']);
                if (isset($field['data'])) {
                    $thisField = $thisField->data($field['data']);
                }
                if (isset($field['settings']['display'])) {
                    if (in_array('editDisabled', $field['settings']['display'])) {
                        $thisField->editDisabled = true;
                    }
                }
                if ($field['name'] === $this->getTitleField()) {
                    $thisField->titleField(true);
                }
                $fieldSet[] = $thisField->toArray();
            }
            if ($blockName === 'basic') {
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

            $action = [];
            foreach ($model['data']['layout']['addAction'] as $addAction) {
                $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->call($addAction['call'])->method($addAction['method']);
                if (isset($addAction['uri'])) {
                    $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->call($addAction['call'])->uri($addAction['uri'])->method($addAction['method']);
                }
                $action[] = $thisAction;
            }
            $result = $result->action('actions', $action);

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
            $result = Builder::page($tableName . '-layout.' . $tableName . '-add');

            $result = $this->buildBlockFields($result, $model['data']['fields']['tabs'], 'tab', $tableName, $addonData);
            $result = $this->buildBlockFields($result, $model['data']['fields']['sidebars'], 'sidebar', $tableName, $addonData);

            $action = [];
            foreach ($model['data']['layout']['editAction'] as $editAction) {
                $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->call($editAction['call'])->method($editAction['method']);
                if (isset($editAction['uri'])) {
                    $editAction['uri'] = str_replace(':id', (string)$id, $editAction['uri']);
                    $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->call($editAction['call'])->uri($editAction['uri'])->method($editAction['method']);
                }

                $action[] = $thisAction;
            }
            $result = $result->action('actions', $action);

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
        if (isset($model['data']['fields']['data'])) {
            $listFields = $this->fieldBuilder($model['data']['fields']['data'], $tableName);
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
        if (isset($model['data']['fields']['data'])) {
            foreach ($model['data']['fields']['data'] as $field) {
                if ($field['allowTranslate'] ?? false) {
                    $translateFields[] = $field;
                }
            }
        }

        $fields = [];
        if (!empty($translateFields)) {
            $fields = $this->fieldBuilder($translateFields, $tableName, 'search');
        }

        return Builder::i18n('admin-layout.admin-i18n')
            ->layout(Config::get('lang.allow_lang_list'), $fields)
            ->toArray();
    }
}
