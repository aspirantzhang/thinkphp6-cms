<?php

declare(strict_types=1);

namespace app\core\domain\Basic\layout;

class ListLayout extends BaseLayout
{
    private array $data = [];

    private array $columnKey = ['name', 'type', 'order', 'position', 'hideInColumn'];

    private array $tableColumn = [];

    private array $tableToolbar = [];

    private array $batchToolbar = [];

    private array $dataSource = [];

    private array $meta = [];

    public function setData(array $data)
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

    public function setColumnKey(array $keyNames)
    {
        $this->columnKey = $keyNames;

        return $this;
    }

    private function buildColumnValue(array $field)
    {
        $result = [];
        foreach ($this->columnKey as $key) {
            $result[$key] = $field[$key] ?? null;
        }

        return $result;
    }

    private function parseTableColumn()
    {
        $fields = $this->model->getModuleField();

        $result = [];
        foreach ($fields as $field) {
            $result[] = $this->buildColumnValue($field);
        }
        $this->tableColumn = $result;
    }

    private function parseOperation()
    {
        $operations = $this->model->getModuleOperation();

        foreach ($operations as $operation) {
            if ($operation['position'] === 'list.tableToolbar') {
                $this->tableToolbar[] = $operation;
            }
            if ($operation['position'] === 'list.batchToolbar') {
                $this->batchToolbar[] = $operation;
            }
        }
    }

    public function toArray(): array
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
