<?php
declare(strict_types=1);

namespace FondBot\Database\Services;

use Exception;
use FondBot\Database\Entities\AbstractEntity;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractService
{

    protected $entity;

    public function __construct(AbstractEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get all records from database
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->entity->newQuery()->get();
    }

    /**
     * Find record by id
     *
     * @param int $id
     * @return AbstractEntity|null
     */
    public function findById(int $id): ?AbstractEntity
    {
        return $this->entity->newQuery()->find($id);
    }

    /**
     * Create new record
     *
     * @param array $attributes
     * @return AbstractEntity
     */
    public function create(array $attributes): AbstractEntity
    {
        $entity = $this->entity->newInstance($attributes);
        $entity->save();

        return $entity;
    }

    /**
     * Create new Entity or update current with attributes found by values
     *
     * @param array $attributes
     * @param array $values
     * @return AbstractEntity|mixed
     */
    public function createOrUpdate(array $attributes, array $values): AbstractEntity
    {
        return $this->entity->updateOrCreate($attributes, $values);
    }

    /**
     * Update record
     *
     * @param AbstractEntity $entity
     * @param array $attributes
     * @return AbstractEntity
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(AbstractEntity $entity, array $attributes): AbstractEntity
    {
        $entity->fill($attributes);
        $entity->save();

        return $entity;
    }

    /**
     * Delete record
     *
     * @param AbstractEntity $entity
     * @throws Exception
     */
    public function delete(AbstractEntity $entity): void
    {
        $entity->delete();
    }


}