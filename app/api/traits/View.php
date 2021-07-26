<?php

declare(strict_types=1);

namespace app\api\traits;

use aspirantzhang\TPAntdBuilder\Builder;
use think\facade\Config;
use think\Exception;

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

    protected function fieldBuilder(array $data, string $tableName)
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
                    if (in_array('hideInColumn', $field['settings']['display'])) {
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

    public function addBuilder(array $addonData = [])
    {
        $model = $this->getModelData();
        if (empty($model) || !isset($model['table_name'])) {
            throw new Exception(__('unable to get model data'));
        }
        $tableName = $model['table_name'];

        if (isset($model['data']['fields']) && isset($model['data']['layout']['addAction'])) {
            $basic = [];
            foreach ($model['data']['fields'] as $addField) {
                $thisField = Builder::field($tableName . '.' . $addField['name'])->type($addField['type']);
                if (isset($addField['data'])) {
                    $thisField = Builder::field($tableName . '.' . $addField['name'])->type($addField['type'])->data($addField['data']);
                }
                $basic[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time')->type('datetime'),
                Builder::field('update_time')->type('datetime'),
                Builder::field('status')->type('switch')->data($addonData['status']),
            ];
            $basic = array_merge($basic, $addonFields);

            $action = [];
            foreach ($model['data']['layout']['addAction'] as $addAction) {
                $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->call($addAction['call'])->method($addAction['method']);
                if (isset($addAction['uri'])) {
                    $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->call($addAction['call'])->uri($addAction['uri'])->method($addAction['method']);
                }
                $action[] = $thisAction;
            }

            return Builder::page($tableName . '-layout.' . $tableName . '-add')
                ->type('page')
                ->tab('basic', $basic)
                ->action('actions', $action)
                ->toArray();
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

        if (isset($model['data']['fields']) && isset($model['data']['layout']['editAction'])) {
            $basic = [];
            foreach ($model['data']['fields'] as $addField) {
                $thisField = Builder::field($tableName . '.' . $addField['name'])->type($addField['type']);
                if (isset($addField['data'])) {
                    $thisField = Builder::field($tableName . '.' . $addField['name'])->type($addField['type'])->data($addField['data']);
                }
                if (isset($addField['settings']['display'])) {
                    if (in_array('editDisabled', $addField['settings']['display'])) {
                        $thisField->editDisabled = true;
                    }
                }
                $basic[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time')->type('datetime'),
                Builder::field('update_time')->type('datetime'),
                Builder::field('status')->type('switch')->data($addonData['status']),
            ];
            $basic = array_merge($basic, $addonFields);

            $action = [];
            foreach ($model['data']['layout']['editAction'] as $editAction) {
                $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->call($editAction['call'])->method($editAction['method']);
                if (isset($editAction['uri'])) {
                    $editAction['uri'] = str_replace(':id', (string)$id, $editAction['uri']);
                    $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->call($editAction['call'])->uri($editAction['uri'])->method($editAction['method']);
                }

                $action[] = $thisAction;
            }

            return Builder::page($tableName . '-layout.' . $tableName . '-edit')
                ->type('page')
                ->tab('basic', $basic)
                ->action('actions', $action)
                ->toArray();
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
        if (isset($model['data']['layout']['batchToolbar'])) {
            $batchToolbar = $this->actionBuilder($model['data']['layout']['batchToolbar']);
        }

        if ($this->isTrash($params)) {
            $batchToolbar = [];
            if (isset($model['data']['layout']['batchToolbarTrashed'])) {
                $batchToolbar = $this->actionBuilder($model['data']['layout']['batchToolbarTrashed']);
            }
        }

        $listFields = [];
        if (isset($model['data']['fields'])) {
            $listFields = $this->fieldBuilder($model['data']['fields'], $tableName);
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
            ->searchBar(true)
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
        if (isset($model['data']['fields'])) {
            foreach ($model['data']['fields'] as $field) {
                if ($field['allowTranslate'] ?? false) {
                    $translateFields[] = $field;
                }
            }
        }

        $fields = [];
        if (!empty($translateFields)) {
            $fields = $this->fieldBuilder($translateFields, $tableName);
        }

        return Builder::i18n('admin-layout.admin-i18n')
            ->layout(Config::get('lang.allow_lang_list'), $fields)
            ->toArray();
    }
}
