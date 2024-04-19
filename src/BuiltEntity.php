<?php

namespace Pim;

use Illuminate\Database\Eloquent\Model;
use Pim\BuiltEntity\Exceptions\InvalidClassModel;
use ReflectionClass;
use ReflectionException;

class BuiltEntity
{
    private array $properties;
    private ?string $model_class;

    /**
     * @param Entity $entity
     * @throws ReflectionException
     */
    public function __construct(public readonly Entity $entity)
    {
        $this->properties = (new ReflectionClass($entity))->getProperty('built_properties')->getValue($entity);
        $this->model_class = $this->entity->modelClass();
    }

    public function toArray()
    {
        return $this->properties;
    }

    public function toJson(): string
    {
        return json_encode($this->properties);
    }

    public function getModel()
    {
        if ($this->model_class === null && !(new $this->model_class() instanceof Model)) {
            throw new InvalidClassModel($this->model_class);
        }

        return new $this->model_class($this->properties);
    }
}
