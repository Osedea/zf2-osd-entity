<?php

namespace Application\Entity;

trait FillableEntityTrait
{
    /**
     * @param $attributes
     * @param array $exclude
     * @return $this
     */
    public function fill($attributes, array $exclude = [])
    {
        $attributes = (array) $attributes;

        foreach ($attributes as $key => $value) {
            if ($value != null && property_exists($this, $key) && !in_array($key, $exclude)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @param $attributes
     * @param array $exclude
     * @return mixed
     */
    public static function create($attributes, array $exclude = [])
    {
        $entity = new static;

        return $entity->fill($attributes, $exclude);
    }

    /**
     * @param $attributes
     * @param array $exclude
     * @return FillableEntityTrait
     */
    public function update($attributes, array $exclude = [])
    {
        return $this->fill($attributes, $exclude);
    }
}
