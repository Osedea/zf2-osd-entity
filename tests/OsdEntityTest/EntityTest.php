<?php

include '../../src/OsdEntity/BasicEntity.php';

class EntityTest extends \OsdEntity\BasicEntity
{
    protected $attribute_one;

    protected $attributeTwo;

    protected $attributeThree;

    protected $attribute_number_four;

    protected $attributeNumberFive;

    protected $attributes = [
        'attribute_one',
        'attributeTwo',
        'attributeThree',
        'attribute_number_four',
        'attributeNumberFive',
    ];
}