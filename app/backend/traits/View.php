<?php

declare(strict_types=1);

namespace app\backend\traits;

use aspirantzhang\TPAntdBuilder\Builder;
use app\backend\service\Model as ModelService;

trait View
{
    public function addBuilder($addonData = [])
    {
        $tableName = parse_name($this->name);
        $modelData = ModelService::where('name', $tableName)->find();
       
        if ($modelData) {
            // Basic Tab
            $basic = [];
            foreach ($modelData->data->fields as $addField) {
                $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type']);
                if (isset($addField['addData']) && $addField['addData'] == 1) {
                    $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type'])->data($addonData[$addField['name']]);
                }
                $basic[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time', 'Create Time')->type('datetime'),
                Builder::field('update_time', 'Update Time')->type('datetime'),
                Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            ];
            $basic = array_merge($basic, $addonFields);

            $action = [];
            foreach ($modelData->data->addAction as $addAction) {
                // dump($addAction['method']);
                $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->action($addAction['action'])->method($addAction['method']);
                if (isset($addAction['uri'])) {
                    $thisAction = Builder::button($addAction['name'])->type($addAction['type'])->action($addAction['action'])->uri($addAction['uri'])->method($addAction['method']);
                }
                $action[] = $thisAction;
            }

            return Builder::page('User Add')
                            ->type('page')
                            ->tab($basic)
                            ->action($action)
                            ->toArray();
        }
        return [];
    }

    public function editBuilder($id, $addonData = [])
    {
        $tableName = parse_name($this->name);
        $modelData = ModelService::where('name', $tableName)->find();
       
        if ($modelData) {
            // Basic Tab
            $main = [];
            foreach ($modelData->data->fields as $addField) {
                $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type']);
                if (isset($addField['addData']) && $addField['addData'] == 1) {
                    $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type'])->data($addonData[$addField['name']]);
                }
                $main[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time', 'Create Time')->type('datetime'),
                Builder::field('update_time', 'Update Time')->type('datetime'),
                Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            ];
            $main = array_merge($main, $addonFields);

            $action = [];
            foreach ($modelData->data->editAction as $editAction) {
                $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->action($editAction['action'])->method($editAction['method']);
                if (isset($editAction['uri'])) {
                    $editAction['uri'] = str_replace(':id', $id, $editAction['uri']);
                    $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->action($editAction['action'])->method($editAction['method'])->uri($editAction['uri']);
                }
                $action[] = $thisAction;
            }
            return Builder::page('User Edit')
                            ->type('page')
                            ->tab($main)
                            ->action($action)
                            ->toArray();
        }
        return [];
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableName = parse_name($this->name);
        $modelData = ModelService::where('name', $tableName)->find();
       
        if ($modelData) {
            $tableData = [];
            foreach ($modelData->data->tableToolbar as $tableToolbar) {
                $thisAction = Builder::button($tableToolbar['name'])->type($tableToolbar['type'])->action($tableToolbar['action'])->method($tableToolbar['method']);
                if (isset($tableToolbar['uri'])) {
                    $thisAction = Builder::button($tableToolbar['name'])->type($tableToolbar['type'])->action($tableToolbar['action'])->method($tableToolbar['method'])->uri($tableToolbar['uri']);
                }
                $tableData[] = $thisAction;
            }

            $batchData = [];
            foreach ($modelData->data->batchToolbar as $batch) {
                $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method']);
                if (isset($batch['uri'])) {
                    $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method'])->uri($batch['uri']);
                }
                $batchData[] = $thisAction;
            }

            if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
                $batchData = [];
                foreach ($modelData->data->batchToolbarTrashed as $batch) {
                    $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method']);
                    if (isset($batch['uri'])) {
                        $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method'])->uri($batch['uri']);
                    }
                    $batchData[] = $thisAction;
                }
            }


            $listFields = [];
            foreach ($modelData->data->fields as $listField) {
                $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type']);
                if (isset($listField['addData']) && $listField['addData'] == 1) {
                    $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type'])->data($addonData[$listField['name']]);
                }
                if (isset($listField['listHideInColumn']) && $listField['listHideInColumn'] === '1') {
                    continue;
                }
                $listFields[] = $thisField;
            }
            $addonFields = [
                Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
                Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
                Builder::field('trash', 'Trash')->type('trash'),
            ];
            $actions = [];
            foreach ($modelData->data->listAction as $listAction) {
                $thisAction = Builder::button($listAction['name'])->type($listAction['type'])->action($listAction['action'])->method($listAction['method']);
                if (isset($listAction['uri'])) {
                    $thisAction = Builder::button($listAction['name'])->type($listAction['type'])->action($listAction['action'])->method($listAction['method'])->uri($listAction['uri']);
                }
                $actions[] = $thisAction;
            }
            $actionFields = Builder::actions($actions)->title('Action');
            $tableColumn = array_merge($listFields, $addonFields, [$actionFields]);

            return Builder::page('User List')
                            ->type('basicList')
                            ->searchBar(true)
                            ->tableColumn($tableColumn)
                            ->tableToolBar($tableData)
                            ->batchToolBar($batchData)
                            ->toArray();
        }
        return [];
    }
}
