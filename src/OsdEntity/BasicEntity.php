<?php

namespace OsdEntity;

class BasicEntity
{
    const RELATION_ONE = 0;

    const RELATION_MANY = 1;

    /**
     * Called if an attempt to set value directly on an attribute
     * is made. Takes the attribute and calls the associated
     * setter instead.
     *
     * @param $attribute
     * @param $value
     */
    public function __set($attribute, $value)
    {
        $setter = $this->translateToSetter($attribute);

        $this->$setter($value);
    }

    /**
     * Magic call for getters and setters
     *
     * @param $function
     * @param $args
     * @return mixed
     */
    public function __call($function, $args)
    {
        $attribute = $this->translateFunctionToAttribute($function);

        // For Getters
        if (substr($function, 0, 3) === 'get') {
            return $this->$attribute;
        }

        // For Setters
        if (substr($function, 0, 3) === 'set') {
            $this->$attribute = $args[0];

            return $this;
        }

        throw new \InvalidArgumentException($function . ' is not a function of ' . get_class($this));
    }

    /**
     *
     * Accepts an array of key/values to fill the entity with.
     * Takes an optional exclude array, which prevents attributes
     * from being skipped
     *
     * @param $attributes
     * @param array $exclude
     * @return $this
     */
    public function fill($attributes, array $exclude = array())
    {
        $attributes = (array)$attributes;

        foreach ($attributes as $key => $value) {
            if ($value != null && property_exists($this, $key) && !in_array($key, $exclude)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     *
     * Creates an entity and fills it with the array of
     * key values passed.
     *
     * @param $attributes
     * @param array $exclude
     * @return mixed
     */
    public static function create($attributes, array $exclude = array())
    {
        $entity = new static;

        return $entity->fill($attributes, $exclude);
    }

    /**
     * Updates an entity with the array of
     * key values passed.
     *
     * @param $attributes
     * @param array $exclude
     * @return $this
     */
    public function update($attributes, array $exclude = array())
    {
        return $this->fill($attributes, $exclude);
    }

    /**
     * Converts an entity to an array. Accepts a $with parameter,
     * that defines all associated relations that should be
     * converted to arrays as well.
     *
     * Example:
     *
     * $user->toArray(['friends', 'comments.tags', 'comments.responses'])
     *
     * @param array $with
     * @return array
     */
    public function toArray(array $with = array())
    {
        $result = array();
        $excluded = isset($this->exclude) ? $this->exclude : array();

        foreach ($this->attributes as $attr) {
            if (!in_array($attr, $excluded)) {
                $getter = $this->translateToGetter($attr);
                $result[$attr] = $this->$getter();
            }
        }

        $relations = $this->buildNestedRelationsArray($with);

        foreach ($relations as $attr => $nestedRelations) {
            $this->addRelationArray($result, $attr, $nestedRelations);
        }

        return $result;
    }

    /**
     * Combines all nested relationships for a given key.
     * Example:
     *
     * buildNestedArray(array('user.friend', 'user.family'))
     * >> array('user' => array('friend', 'family'))
     *
     * @param $relations
     * @return array
     */
    protected function buildNestedRelationsArray($relations)
    {
        $response = array();

        foreach ($relations as $relation) {
            $nestedRelations = explode('.', $relation);
            $attr = array_shift($nestedRelations);

            if (!isset($response[$attr])) {
                $response[$attr] = array_values($nestedRelations);
            } else {
                $response[$attr] = array_merge($response[$attr], array_values($nestedRelations));
            }
        }

        return $response;
    }

    /**
     * @param $relation
     * @param $nestedRelations
     * @return array
     */
    protected function addRelationArray(&$result, $attr, $nestedRelations)
    {
        if (!isset($this->relations[$attr])) {
            throw new \InvalidArgumentException($attr . ' is not set as a relation on ' . get_class($this));
        };

        if ($this->relations[$attr] === self::RELATION_MANY) {
            $result[$attr] = $this->getHasManyRelation($attr, $nestedRelations);
        }

        if ($this->relations[$attr] === self::RELATION_ONE) {
            $result[$attr] = $this->getHasOneRelation($attr, $nestedRelations);

            $idAttr = $attr . '_id';
            $result[$idAttr] = isset($result[$attr]['id']) ? $result[$attr]['id'] : null;
        }
    }

    /**
     * @param $relation
     * @param $nestedRelations
     * @return array
     */
    protected function getHasManyRelation($relation, $nestedRelations)
    {
        $hasManyRelations = $this->$relation ? $this->$relation->toArray() : array();

        return array_map(function ($item) use ($nestedRelations) {
            return $item->toArray($nestedRelations);
        }, $hasManyRelations);
    }

    /**
     * @param $relation
     * @param $nestedRelations
     * @return mixed
     */
    protected function getHasOneRelation($relation, $nestedRelations)
    {
        return $this->$relation ? $this->$relation->toArray($nestedRelations) : array();
    }

    /**
     * Attempts to convert getter or setter calls to their associated attributes
     * Throws InvalidArgumentException if function cannot be translated
     * to an attribute of the given entity.
     *
     * @param $function
     * @return null|string
     */
    protected function translateFunctionToAttribute($function)
    {
        $attribute = null;

        $substr = substr($function, 0, 3);

        if ($substr === 'get' || $substr === 'set') {
            $attribute = lcfirst(substr($function, 3));
        }

        if (!property_exists($this, $attribute)) {
            throw new \InvalidArgumentException($attribute . ' is not an attribute of ' . get_class($this));
        }

        return $attribute;
    }

    /**
     * Translates to camelCase and prepend 'get'.
     *
     * @param $attribute
     * @return string
     */
    protected function translateToGetter($value)
    {
        $value = $this->snakeToCamel($value);

        return 'get' . ucfirst($value);
    }

    /**
     * Translates value to camelCase and prepend 'set'.
     *
     * @param $attribute
     * @return string
     */
    protected function translateToSetter($value)
    {
        $value = $this->snakeToCamel($value);

        return 'set' . ucfirst($value);
    }

    /**
     * Take a string_like_this and return a StringLikeThis
     *
     * @param string
     * @return string
     */
    protected function snakeToCamel($val)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
    }
}
