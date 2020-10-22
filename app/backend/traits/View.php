<?php

declare(strict_types=1);

namespace app\backend\traits;

use aspirantzhang\TPAntdBuilder\Builder;

trait View
{
    public function addBuilder($addonData = [])
    {
        $modelData = $this->getModelData();
       
        if ($modelData) {
            $basic = [];
            foreach ($modelData['fields'] as $addField) {
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
            foreach ($modelData['addAction'] as $addAction) {
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
        $modelData = $this->getModelData();
       
        if ($modelData) {
            $main = [];
            foreach ($modelData['fields'] as $addField) {
                $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type']);
                if (isset($addField['addData']) && $addField['addData'] == 1) {
                    $thisField = Builder::field($addField['name'], $addField['title'])->type($addField['type'])->data($addonData[$addField['name']]);
                }
                if (isset($addField['editDisabled']) && $addField['editDisabled'] == 1) {
                    $thisField->disabled = true;
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
            foreach ($modelData['editAction'] as $editAction) {
                $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->action($editAction['action'])->method($editAction['method']);
                if (isset($editAction['uri'])) {
                    $editAction['uri'] = str_replace(':id', $id, $editAction['uri']);
                    $thisAction = Builder::button($editAction['name'])->type($editAction['type'])->action($editAction['action'])->uri($editAction['uri'])->method($editAction['method']);
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
        $modelData = $this->getModelData();
        if (!$modelData) {
            return [];
        }
        
        $tableData = [];
        if (isset($modelData['tableToolbar'])) {
            foreach ($modelData['tableToolbar'] as $tableToolbar) {
                $thisAction = Builder::button($tableToolbar['name'])->type($tableToolbar['type'])->action($tableToolbar['action'])->method($tableToolbar['method']);
                if (isset($tableToolbar['uri'])) {
                    $thisAction = Builder::button($tableToolbar['name'])->type($tableToolbar['type'])->action($tableToolbar['action'])->method($tableToolbar['method'])->uri($tableToolbar['uri']);
                }
                $tableData[] = $thisAction;
            }
        }

        $batchData = [];
        if ($modelData['batchToolbar']) {
            foreach ($modelData['batchToolbar'] as $batch) {
                $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method']);
                if (isset($batch['uri'])) {
                    $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method'])->uri($batch['uri']);
                }
                $batchData[] = $thisAction;
            }
        }


        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchData = [];
            if ($modelData['batchToolbarTrashed']) {
                foreach ($modelData['batchToolbarTrashed'] as $batch) {
                    $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method']);
                    if (isset($batch['uri'])) {
                        $thisAction = Builder::button($batch['name'])->type($batch['type'])->action($batch['action'])->method($batch['method'])->uri($batch['uri']);
                    }
                    $batchData[] = $thisAction;
                }
            }
        }


        $listFields = [];
        if ($modelData['fields']) {
            foreach ($modelData['fields'] as $listField) {
                $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type']);
                if (isset($listField['addData']) && $listField['addData'] == 1) {
                    $thisField = Builder::field($listField['name'], $listField['title'])->type($listField['type'])->data($addonData[$listField['name']]);
                }
                if (isset($listField['listHideInColumn']) && $listField['listHideInColumn'] === '1') {
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
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
        ];
        
        $actions = [];
        if ($modelData['listAction']) {
            foreach ($modelData['listAction'] as $listAction) {
                $thisAction = Builder::button($listAction['name'])->type($listAction['type'])->action($listAction['action'])->method($listAction['method']);
                if (isset($listAction['uri'])) {
                    $thisAction = Builder::button($listAction['name'])->type($listAction['type'])->action($listAction['action'])->method($listAction['method'])->uri($listAction['uri']);
                }
                $actions[] = $thisAction;
            }
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
}
