<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Base Repository
 * 
 * Abstract base class for all repositories in the application.
 * Provides common CRUD operations and query building functionality.
 * 
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * The Eloquent model instance.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Get records with optional filtering, relationships, and pagination.
     *
     * @param array $columns Columns to select
     * @param array $where Where conditions
     * @param array $with Relationships to eager load
     * @param int|null $limit Number of records per page
     * @return Collection|LengthAwarePaginator
     */
    public function get(array $columns = [], array $where = [], array $with = [], ?int $limit = null)
    {
        $query = $this->model->query();

        // Eager load relationships if provided
        if (!empty($with)) {
            $query->with($with);
        }

        foreach ($where as $column => $value) {
            $query->where($column, $value);
        }

        // If no columns specified, select all
        $query = empty($columns) ? $query : $query->select($columns);

        // Return paginated results if limit is specified
        return $limit ? $query->paginate($limit) : $query->get();
    }

    /**
     * Find a record by its ID.
     *
     * @param string|int $id
     * @return Model|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        $data['id'] = (string) Str::uuid();
        return $this->model->create($data);
    }

    /**
     * Update a record by its ID.
     *
     * @param string|int $id
     * @param array $data
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    /**
     * Delete a record by its ID.
     *
     * @param string|int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Execute a custom query using the model's table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
    {
        return DB::table($this->model->getTable());
    }
}