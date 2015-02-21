<?php

namespace OsdEntity;

class BasicEntity
{
    use FillableEntityTrait;

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
     * Converts an entity to an array. Accepts a $with parameter,
     * that defines all associated relations that should be
     * converted to arrays as well.
     *
     * @param array $with
     * @return array
     */
    public function toArray(array $with = [])
    {
        $result = [];
        $excluded = isset($this->exclude) ? $this->exclude : [];

        foreach ($this->attributes as $attr) {
            if ( ! in_array($attr, $excluded)) {
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
     * array('user.friend', 'user.family')
     *
     * returns array('user' => array('friend', 'family'))
     *
     * @param $relations
     * @return array
     */
    protected function buildNestedRelationsArray($relations)
    {
        $response = [];

        foreach($relations as $relation) {
            $nestedRelations = explode('.', $relation);
            $attr = array_shift($nestedRelations);

            if ( ! isset($response[$attr])) {
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
        if ( ! isset($this->relations[$attr])) {
            throw new \InvalidArgumentException($attr . ' is not set as a relation on ' . get_class($this));
        };

        if ($this->relations[$attr] === self::RELATION_MANY){
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
        $hasManyRelations = $this->$relation ? $this->$relation->toArray() : [];

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
        return $this->$relation ? $this->$relation->toArray($nestedRelations) : [];
    }

    /**
     * Attempts to convert getter or setter calls to their associated attributes
     * Throws InvalidArgumentException if function cannot be translated
     * to an attribute of the given entity.
     *
     * @param $function
     * @return null|string
     */
    function translateFunctionToAttribute($function)
    {
        $attribute = null;

        $substr = substr($function, 0, 3);

        if ($substr === 'get' || $substr === 'set') {
            $attribute = lcfirst(substr($function, 3));
        }

        if ( ! property_exists($this, $attribute)) {
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
    function translateToGetter($value)
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
    function translateToSetter($value)
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
    function snakeToCamel($val) {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
    }
}
