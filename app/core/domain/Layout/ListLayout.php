<?php

declare(strict_types=1);

namespace app\core\domain\Layout;

use app\core\exception\SystemException;

class ListLayout extends Layout
{
    private $data;

    private $tableColumn;

    private $tableToolbar;

    private $batchToolbar;

    private $dataSource;

    private $meta;

    public function withData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    private function parseDataSource()
    {
        $dataArray = $this->data;
        if (isset($dataArray['dataSource'])) {
            $this->dataSource = $dataArray['dataSource'];
        }
        if (isset($dataArray['pagination'])) {
            $this->meta = $dataArray['pagination'];
        }
    }

    private function parseTableColumn()
    {
        $fields = $this->model->getModule('field');
        if (empty($fields) || !is_array($fields)) {
            throw new SystemException('no fields founded in module: ' . $this->model->getTableName());
        }

        $result = [];
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
        $operations = $this->model->getModule('operation');
        if (empty($operations) || !is_array($operations)) {
            throw new SystemException('no operations founded in module: ' . $this->model->getTableName());
        }

        foreach ($operations as $operation) {
            if ($operation['position'] === 'list.tableToolbar') {
                $this->tableToolbar[] = $operation;
            }
            if ($operation['position'] === 'list.batchToolbar') {
                $this->batchToolbar[] = $operation;
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
                'tableToolBar' => $this->tableToolbar,
                'batchToolBar' => $this->batchToolbar,
            ],
            'dataSource' => $this->dataSource,
            'meta' => $this->meta,
        ];
    }
}
