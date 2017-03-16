<?php
declare(strict_types=1);

namespace FondBot\Contracts\Database\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait BaseServiceMethods
{

    /** @var Model|\Eloquent */
    protected $entity;

    /**
     * Get all records from database.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->entity->newQuery()->get();
    }

    /**
     * Find record by id.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        return $this->entity->newQuery()->find($id);
    }

    /**
     * Create new record.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $entity = $this->entity->newInstance($attributes);
        $entity->save();

        return $entity;
    }

    /**
     * Create new Entity or update current with attributes found by values.
     *
     * @param array $attributes
     * @param array $values
     * @return Model|mixed
     */
    public function createOrUpdate(array $attributes, array $values): Model
    {
        return $this->entity->updateOrCreate($attributes, $values);
    }

    /**
     * Update record.
     *
     * @param Model $entity
     * @param array $attributes
     * @return Model
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(Model $entity, array $attributes): Model
    {
        $entity->fill($attributes);
        $entity->save();

        return $entity;
    }

    /**
     * Delete record.
     *
     * @param Model $entity
     */
    public function delete(Model $entity): void
    {
        $entity->delete();
    }
}
