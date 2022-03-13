<?php

declare(strict_types=1);

namespace app\backend\domain\Layout;

class ListLayout extends Layout
{
    private $data;
    private $tableColumn;
    private $tableToolBar;
    private $batchToolbar;
    private $dataSource;
    private $meta;

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    private function parseDataSource()
    {
        $dataArray = $this->data->toArray();
        if (isset($dataArray['dataSource'])) {
            $this->dataSource = $dataArray['dataSource'];
        }
        if (isset($dataArray['pagination'])) {
            $this->meta = $dataArray['pagination'];
        }
    }

    private function parseTableColumn()
    {
        $fields = $this->model->getModule()->field;
        foreach ($fields as $field) {
            $result[] = [
                'name' => $field['name'],
                'type' => $field['type'],
                'translate' => $field['translate'],
                'order' => $field['order'],
            ];
        }
        $this->tableColumn = $result;
    }

    private function parseOperation()
    {
        $operations = $this->model->getModule()->operation;
        if (!empty($operations) && count($operations) > 0) {
            foreach ($operations as $operation) {
                if ($operation['position'] === 'list.tableToolbar') {
                    $this->tableToolBar[] = $operation;
                }
                if ($operation['position'] === 'list.batchToolbar') {
                    $this->batchToolbar[] = $operation;
                }
            }
        }
    }

    public function jsonSerialize(): array
    {
        $this->parseDataSource();
        $this->parseTableColumn();
        $this->parseOperation();

        return [
            'page' => [],
            'layout' => [
                'tableColumn' => $this->tableColumn,
                'tableToolBar' => $this->tableToolBar,
                'batchToolBar' => $this->batchToolbar,
            ],
            'dataSource' => $this->dataSource,
            'meta' => $this->meta
        ];
    }
}
