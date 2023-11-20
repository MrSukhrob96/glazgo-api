<?php

namespace App\Core\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CoreRepositoryInterface
{
    public function getWithPagination(int $limit, int $offset = 20, array $columns = ['*'], array $relations = []): ?LengthAwarePaginator;

    public function getAll(array $columns = ['*'], array $relations = []): ?Collection;

    public function findById(int|string $id, array $columns = ['*'], array $relations = []): ?Model;

    public function firstOrCreate(array $data, array $where = []): ?Model;

    public function create(array $data): ?Model;

    public function update(int $id, array $data): void;

    public function updateOrCreate(array $data, array $where = []): ?Model;

    public function delete(int|string $id): void;
}
