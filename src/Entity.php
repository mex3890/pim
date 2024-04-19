<?php

namespace Pim;

use Pim\Entity\Exceptions\InvalidPropertyBuild;
use ReflectionException;

abstract class Entity
{
    protected array $built_properties;

    abstract protected function toArray(): array;

    public function modelClass(): ?string
    {
        return null;
    }

    /**
     * Method that call mutations on property to build.
     * @return BuiltEntity
     * @throws ReflectionException
     */
    public function build(): BuiltEntity
    {
        $this->built_properties = $this->mutateProperties($this->toArray());

        return new BuiltEntity($this);
    }

    public function __get(string $name): mixed
    {
        return $this->built_properties[$name] ?? $this->$name ?? null;
    }

    public function __isset(string $name): bool
    {
        return (bool)($this->built_properties[$name] ?? $this->$name ?? false);
    }

    /**
     * Array of properties of the Entity who need build before toArray() and toJson().
     * @return string[]
     */
    protected function propertiesBuild(): array
    {
        return [];
    }

    private function mutateProperties(array $properties): array
    {
        $properties = $this->mutateBeforeBuild($properties);

        foreach ($this->propertiesBuild() as $property) {
            if (isset($this->$property) && $this->$property instanceof Entity) {
                $properties[$property] = $this->$property->build();

                continue;
            }

            throw new InvalidPropertyBuild($property);
        }

        return $this->mutateAfterBuild($properties);
    }

    /**
     * Method will be called after start build properties.
     * @param array $properties
     * @return array
     */
    protected function mutateAfterBuild(array $properties): array
    {
        return $properties;
    }

    /**
     * Method will be called before start build properties.
     * @param array $properties
     * @return array
     */
    protected function mutateBeforeBuild(array $properties): array
    {
        foreach ($properties as $property => $value) {
            if ($value === null) {
                unset($properties[$property]);
            }
        }

        return $properties;
    }
}
