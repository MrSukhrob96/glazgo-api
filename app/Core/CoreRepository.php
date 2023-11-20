<?php

namespace App\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    public function __construct(
        protected readonly Model $model
    ) {
    }

    /**
     * Method getWithPagination
     *
     * @param int $limit
     * @param int $offset
     * @param array $columns
     * @param array $relations
     */
    public function getWithPagination(
        int $limit,
        int $offset = 20,
        array $columns = ['*'],
        array $relations = []
    ): ?LengthAwarePaginator {
        return $this->model->query()
            ->select($columns)
            ->with($relations)
            ->paginate($limit, ['*'], 'page', $offset);
    }

    /**
     * Method GetAll
     *
     * @param array $columns
     * @param array $relations
     */
    public function getAll(array $columns = ['*'], array $relations = []): ?Collection
    {
        return $this->model->query()
            ->select($columns)
            ->with($relations)
            ->get();
    }

    /**
     * Method findById
     *
     * @param int|string $id
     * @param array $columns
     * @param array $relations
     */
    public function findById(int|string $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->query()
            ->select($columns)
            ->with($relations)
            ->first($id);
    }

    /**
     * Method create
     *
     * @param array $data
     * @return ?Model
     */
    public function create(array $data): ?Model
    {
        return $this->model->query()->create($data);
    }

    /**
     * Method firstOrCreate
     * 
     * @param array $data
     * @param array $where
     * @return ?Model
     */
    public function firstOrCreate(array $data, array $where = []): ?Model
    {
        return $this->model->query()->firstOrCreate(
            $where ?: $data,
            $data
        );
    }

    /**
     * Method update
     * 
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $this->model->query()->where($id)->update($data);
    }

    /**
     * Method updateOrCreate
     *
     * @param array $data
     * @param array $where
     * @return array
     */
    public function updateOrCreate(array $data, array $where = []): ?Model
    {
        return $this->model->query()
            ->updateOrCreate(
                $where,
                $data
            );
    }

    /**
     * Method delete
     *
     * @param int|string $id
     * @return void
     */
    public function delete(int|string $id): void
    {
        $this->model->query()->destroy($id);
    }
}
