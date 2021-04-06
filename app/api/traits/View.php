<?php

declare(strict_types=1);

namespace app\api\traits;

use aspirantzhang\TPAntdBuilder\Builder;

trait View
{
    protected function actionBuilder($data)
    {
        if (!empty($data)) {
            $result = [];
            foreach ($data as $action) {
                $row = [];
                $row = (array)Builder::button($action['name'], $action['title'])
                                        ->type($action['type'])
                                        ->call($action['call'])
                                        ->method($action['method']);
                if (isset($action['uri'])) {
                    $row = (array)Builder::button($action['name'], $action['title'])
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

    protected function fieldBuilder($data)
    {
        if (!empty($data)) {
            $result = [];
            foreach ($data as $field) {
                $row = [];
                $row = (array)Builder::field($field['name'], $field['title'])->type($field['type']);
                if (isset($field['data'])) {
                    $row = (array)Builder::field($field['name'], $field['title'])->type($field['type'])->data($field['data']);
                }
                if (isset($field['hideInColumn']) && $field['hideInColumn'] === '1') {
                    continue;
                }
                if (isset($field['listSorter']) && $field['listSorter'] === '1') {
                    $row['sorter'] = true;
                }
                $result[] = $row;
            }
            return $result;
        }
        return [];
    }

    public function addBuilder($addonData = [])
    {
        $model = $this->getModelData();

        if ($model['data']) {
            $basic = [];
            foreach ($model['data']['fields'] as $addField) {
                $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type']);
                if (isset($addField['data'])) {
                    $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type'])->data($addField['data']);
                }
                $basic[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time', 'Create Time')->type('datetime'),
                Builder::field('update_time', 'Update Time')->type('datetime'),
                Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            ];
            $basic = array_merge($basic, $addonFields);

            $action = [];
            foreach ($model['data']['addAction'] as $addAction) {
                $thisAction = Builder::button($addAction['name'], $addAction['title'])->type($addAction['type'])->call($addAction['call'])->method($addAction['method']);
                if (isset($addAction['uri'])) {
                    $thisAction = Builder::button($addAction['name'], $addAction['title'])->type($addAction['type'])->call($addAction['call'])->uri($addAction['uri'])->method($addAction['method']);
                }
                $action[] = $thisAction;
            }

            return Builder::page($model['route_name'] . '-add')
                            ->type('page')
                            ->tab('basic', 'Basic', $basic)
                            ->action('actions', 'Actions', $action)
                            ->toArray();
        }
        return [];
    }

    public function editBuilder($id, $addonData = [])
    {
        $model = $this->getModelData();

        if ($model['data']) {
            $basic = [];
            foreach ($model['data']['fields'] as $addField) {
                $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type']);
                if (isset($addField['data'])) {
                    $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type'])->data($addField['data']);
                }
                if (isset($addField['editDisabled']) && $addField['editDisabled'] == 1) {
                    $thisField->editDisabled = true;
                }
                $basic[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time', 'Create Time')->type('datetime'),
                Builder::field('update_time', 'Update Time')->type('datetime'),
                Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            ];
            $basic = array_merge($basic, $addonFields);

            $action = [];
            foreach ($model['data']['editAction'] as $editAction) {
                $thisAction = Builder::button($editAction['name'], $editAction['title'])->type($editAction['type'])->call($editAction['call'])->method($editAction['method']);
                if (isset($editAction['uri'])) {
                    $editAction['uri'] = str_replace(':id', $id, $editAction['uri']);
                    $thisAction = Builder::button($editAction['name'], $editAction['title'])->type($editAction['type'])->call($editAction['call'])->uri($editAction['uri'])->method($editAction['method']);
                }

                $action[] = $thisAction;
            }

            return Builder::page($model['route_name'] . '-edit')
                            ->type('page')
                            ->tab('basic', 'Basic', $basic)
                            ->action('actions', 'Actions', $action)
                            ->toArray();
        }
        return [];
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $model = $this->getModelData();

        $tableToolbar = [];
        if (isset($model['data']['tableToolbar'])) {
            $tableToolbar = $this->actionBuilder($model['data']['tableToolbar']);
        }

        $batchToolbar = [];
        if ($model['data']['batchToolbar']) {
            $batchToolbar = $this->actionBuilder($model['data']['batchToolbar']);
        }

        if ($this->isTrash($params)) {
            $batchToolbar = [];
            if ($model['data']['batchToolbarTrashed']) {
                $batchToolbar = $this->actionBuilder($model['data']['batchToolbarTrashed']);
            }
        }

        $listFields = [];
        if ($model['data']['fields']) {
            $listFields = $this->fieldBuilder($model['data']['fields']);
        }

        $addonFields = [
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
        ];

        $actions = [];
        if ($model['data']['listAction']) {
            $actions = $this->actionBuilder($model['data']['listAction']);
        }
        $actionFields = Builder::field('actions', 'Actions')->data($actions);
        $tableColumn = array_merge($listFields, $addonFields, [$actionFields]);

        return Builder::page($model['route_name'] . '-list')
                        ->type('basicList')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableToolbar)
                        ->batchToolBar($batchToolbar)
                        ->toArray();
    }
}
