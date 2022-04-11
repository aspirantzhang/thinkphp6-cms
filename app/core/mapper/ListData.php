<?php

declare(strict_types=1);

namespace app\core\mapper;

use app\core\CoreModel;

class ListData implements \JsonSerializable
{
    public const PAGINATED = 'paginated';

    private array $params;

    private array $option;

    private string $type;

    private array $listParams;

    public function __construct(protected CoreModel | \think\db\Query $model)
    {
    }

    public function withParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    public function withOption(array $option)
    {
        $this->option = $option;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    private function buildTrash()
    {
        if ($this->listParams['trash'] !== 'withoutTrashed') {
            $this->model = $this->model->{$this->listParams['trash']}();
        }
    }

    private function buildWithModel()
    {
        if (isset($this->option['with'])) {
            $this->model = $this->model->with($this->option['with']);
        }
    }

    private function getTrashParam(): string
    {
        if (!isset($this->params['trash'])) {
            return 'withoutTrashed';
        }

        switch ($this->params['trash']) {
            case 'onlyTrashed':
                return 'onlyTrashed';
            case 'withTrashed':
                return 'withTrashed';
            default:
                return 'withoutTrashed';
        }
    }

    private function getPerPage()
    {
        return $this->params['per_page'] ?? 10;
    }

    private function getVisible()
    {
        return array_diff($this->model->getAllowBrowse(), ['sort', 'order', 'page', 'per_page', 'trash']);
    }

    private function getSearchValues(array $visible)
    {
        return array_intersect_key($this->params, array_flip($visible));
    }

    private function getSearchKeys($values)
    {
        return array_keys($values);
    }

    private function getSearch(array $visible)
    {
        $searchValues = $this->getSearchValues($visible);

        return [
            'values' => $searchValues,
            'keys' => $this->getSearchKeys($searchValues),
        ];
    }

    private function getListParams()
    {
        $result = [];
        $result['trash'] = $this->getTrashParam();
        $result['per_page'] = $this->getPerPage();
        $result['visible'] = $this->getVisible();
        $result['search'] = $this->getSearch($result['visible']);
        $result['sort'] = $this->getSortParam($this->params);
        $this->listParams = $result;
    }

    private function getSortParam(array $data): array
    {
        $sort = [
            'name' => 'id',
            'order' => 'desc',
        ];

        if (isset($data['sort'])) {
            $sort['name'] = in_array($data['sort'], $this->model->getAllowSort()) ? $data['sort'] : 'id';
        }
        if (isset($data['order'])) {
            $sort['order'] = ('asc' === $data['order']) ? 'asc' : 'desc';
        }

        return $sort;
    }

    private function getResult()
    {
        $this->getListParams();
        $this->buildTrash();
        $this->buildWithModel();

        // TODO: add i18n $result = $this->withI18n($result->with($withRelation))
        $this->model = $this->model
            ->withSearch($this->listParams['search']['keys'], $this->listParams['search']['values'])
            ->order($this->listParams['sort']['name'], $this->listParams['sort']['order'])
            ->visible($this->listParams['visible']);

        if ($this->type === 'paginated') {
            return $this->model->paginate($this->listParams['per_page']);
        }

        return $this->model->select();
    }

    public function toArray()
    {
        return $this->getResult()->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
