<?php

declare(strict_types=1);

namespace app\core\domain\Layout;

class ListLayout extends BaseLayout
{
    private array $data = [];

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

    private function parseTableColumn()
    {
        $fields = $this->model->getModuleField();

        $result = [];
        foreach ($fields as $field) {
            $result[] = [
                'name' => $field['name'],
                'type' => $field['type'],
                'order' => $field['order'],
            ];
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
