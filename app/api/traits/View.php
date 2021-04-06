<?php

declare(strict_types=1);

namespace app\api\traits;

use aspirantzhang\TPAntdBuilder\Builder;

trait View
{
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

        $tableData = [];
        if (isset($model['data']['tableToolbar'])) {
            foreach ($model['data']['tableToolbar'] as $tableToolbar) {
                $thisAction = Builder::button($tableToolbar['name'], $tableToolbar['title'])->type($tableToolbar['type'])->call($tableToolbar['call'])->method($tableToolbar['method']);
                if (isset($tableToolbar['uri'])) {
                    $thisAction = Builder::button($tableToolbar['name'], $tableToolbar['title'])->type($tableToolbar['type'])->call($tableToolbar['call'])->method($tableToolbar['method'])->uri($tableToolbar['uri']);
                }
                $tableData[] = $thisAction;
            }
        }

        $batchData = [];
        if ($model['data']['batchToolbar']) {
            foreach ($model['data']['batchToolbar'] as $batch) {
                $thisAction = Builder::button($batch['name'], $batch['title'])->type($batch['type'])->call($batch['call'])->method($batch['method']);
                if (isset($batch['uri'])) {
                    $thisAction = Builder::button($batch['name'], $batch['title'])->type($batch['type'])->call($batch['call'])->method($batch['method'])->uri($batch['uri']);
                }
                $batchData[] = $thisAction;
            }
        }

        if ($this->isTrash($params)) {
            $batchData = [];
            if ($model['data']['batchToolbarTrashed']) {
                foreach ($model['data']['batchToolbarTrashed'] as $batch) {
                    $thisAction = Builder::button($batch['name'], $batch['title'])->type($batch['type'])->call($batch['call'])->method($batch['method']);
                    if (isset($batch['uri'])) {
                        $thisAction = Builder::button($batch['name'], $batch['title'])->type($batch['type'])->call($batch['call'])->method($batch['method'])->uri($batch['uri']);
                    }
                    $batchData[] = $thisAction;
                }
            }
        }

        $listFields = [];
        if ($model['data']['fields']) {
            foreach ($model['data']['fields'] as $listField) {
                $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type']);
                if (isset($listField['data'])) {
                    $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type'])->data($listField['data']);
                }
                if (isset($listField['hideInColumn']) && $listField['hideInColumn'] === '1') {
                    continue;
                }
                if (isset($listField['listSorter']) && $listField['listSorter'] === '1') {
                    $thisField->sorter = true;
                }
                $listFields[] = $thisField;
            }
        }
        $addonFields = [
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
        ];

        $actions = [];
        if ($model['data']['listAction']) {
            foreach ($model['data']['listAction'] as $listAction) {
                $thisAction = Builder::button($listAction['name'], $listAction['title'])->type($listAction['type'])->call($listAction['call'])->method($listAction['method']);
                if (isset($listAction['uri'])) {
                    $thisAction = Builder::button($listAction['name'], $listAction['title'])->type($listAction['type'])->call($listAction['call'])->method($listAction['method'])->uri($listAction['uri']);
                }
                $actions[] = $thisAction;
            }
        }
        $actionFields = Builder::field('actions', 'Actions')->data($actions);
        $tableColumn = array_merge($listFields, $addonFields, [$actionFields]);

        return Builder::page($model['route_name'] . '-list')
                        ->type('basicList')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableData)
                        ->batchToolBar($batchData)
                        ->toArray();
    }
}
